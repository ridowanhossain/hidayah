<?php
/**
 * Compile .po files to .mo files
 * This script scans the current directory for .po files and compiles them.
 */

$po_files = glob(__DIR__ . '/*.po');

if (empty($po_files)) {
    die("Error: No .po files found in " . __DIR__ . "\n");
}

foreach ($po_files as $poFile) {
    echo "Compiling " . basename($poFile) . "... ";
    
    $moFile = str_replace('.po', '.mo', $poFile);
    $content = file_get_contents($poFile);
    
    if (empty($content)) {
        echo "Empty file, skipping.\n";
        continue;
    }

    $lines = explode("\n", $content);
    $messages = [];
    $currentId = null;
    $currentStr = null;
    $inMsgid = false;
    $inMsgstr = false;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        if (preg_match('/^msgid "(.*)"$/', $line, $m)) {
            if ($currentId !== null) {
                $messages[$currentId] = $currentStr;
            }
            $currentId = stripcslashes($m[1]);
            $currentStr = '';
            $inMsgid = true;
            $inMsgstr = false;
        } elseif (preg_match('/^msgstr "(.*)"$/', $line, $m)) {
            $currentStr = stripcslashes($m[1]);
            $inMsgid = false;
            $inMsgstr = true;
        } elseif (preg_match('/^"(.*)"$/', $line, $m)) {
            if ($inMsgstr) {
                $currentStr .= stripcslashes($m[1]);
            } elseif ($inMsgid) {
                $currentId .= stripcslashes($m[1]);
            }
        }
    }

    if ($currentId !== null) {
        $messages[$currentId] = $currentStr;
    }

    // Sort messages by msgid (required for some MO readers and good practice)
    ksort($messages);

    $originals = array_keys($messages);
    $translations = array_values($messages);
    $n = count($originals);

    $origTabOffset = 28;
    $transTabOffset = $origTabOffset + $n * 8;
    $dataOffset = $transTabOffset + $n * 8;
    $origData = $transData = '';
    $origOffsets = $transOffsets = [];

    foreach ($originals as $s) {
        $origOffsets[] = [strlen($s), strlen($origData)];
        $origData .= $s . "\0";
    }
    foreach ($translations as $s) {
        $transOffsets[] = [strlen($s), strlen($transData)];
        $transData .= $s . "\0";
    }

    $mo = pack('V', 0x950412de) . 
         pack('V', 0) . 
         pack('V', $n) . 
         pack('V', $origTabOffset) . 
         pack('V', $transTabOffset) . 
         pack('V', 0) . 
         pack('V', $dataOffset);

    foreach ($origOffsets as $o) {
        $mo .= pack('V', $o[0]) . pack('V', $dataOffset + $o[1]);
    }

    $ts = $dataOffset + strlen($origData);
    foreach ($transOffsets as $o) {
        $mo .= pack('V', $o[0]) . pack('V', $ts + $o[1]);
    }

    $mo .= $origData . $transData;

    file_put_contents($moFile, $mo);
    echo "Done: " . $n . " strings.\n";
}

echo "\nCompilation complete.\n";
