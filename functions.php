<?php
/**
 * Hidayah Theme - Functions
 * Main entry point: loads the framework and all inc/ files.
 *
 * @package Hidayah
 * @version 1.0.0
 */

// Define theme directory constants
define( 'HIDAYAH_DIR', get_template_directory() );
define( 'HIDAYAH_URI', get_template_directory_uri() );

// ── Load Codestar Framework (Theme Options) ───────────────
if ( file_exists( HIDAYAH_DIR . '/inc/codestar-framework/codestar-framework.php' ) ) {
    require_once HIDAYAH_DIR . '/inc/codestar-framework/codestar-framework.php';
}

// ── Load inc/ files ───────────────────────────────────────
require_once HIDAYAH_DIR . '/inc/theme-support.php';
require_once HIDAYAH_DIR . '/inc/enqueue.php';
require_once HIDAYAH_DIR . '/inc/menus.php';
require_once HIDAYAH_DIR . '/inc/custom-post-types.php';
require_once HIDAYAH_DIR . '/inc/taxonomies.php';
require_once HIDAYAH_DIR . '/inc/widgets.php';
require_once HIDAYAH_DIR . '/inc/shortcodes.php';
require_once HIDAYAH_DIR . '/inc/helpers.php';

// ── Theme Options Panel (Codestar) ────────────────────────
require_once HIDAYAH_DIR . '/inc/cs-options.php';
