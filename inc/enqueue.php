<?php

/**
 * Scripts & Styles Enqueue
 * Loads all CSS and JS files for the Hidayah theme.
 *
 * @package Hidayah
 */

if (! function_exists('hidayah_scripts')) :
    function hidayah_scripts()
    {

        // ── Google Fonts (Bangla typography) ─────────────────
        wp_enqueue_style(
            'hidayah-google-fonts',
            'https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;600;700&display=swap',
            array(),
            null
        );

        // ── Material Symbols ──────────────────────────────────
        wp_enqueue_style(
            'hidayah-material-symbols',
            'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
            array(),
            null
        );

        // ── Main Stylesheet ───────────────────────────────────
        wp_enqueue_style(
            'hidayah-style',
            get_stylesheet_uri(),
            array('hidayah-google-fonts'),
            filemtime(get_stylesheet_directory() . '/style.css')
        );

        // ── Main JavaScript (Core UI) ──────────────────────
        wp_enqueue_script(
            'hidayah-scripts',
            HIDAYAH_URI . '/assets/js/script.js',
            array('jquery'),
            filemtime(HIDAYAH_DIR . '/assets/js/script.js'),
            true
        );

        // ── Checkout Specific Script ────────────────────────
        if (is_checkout()) {
            wp_enqueue_script(
                'hidayah-checkout',
                HIDAYAH_URI . '/assets/js/checkout.js',
                array('jquery', 'hidayah-scripts'),
                filemtime(HIDAYAH_DIR . '/assets/js/checkout.js'),
                true
            );
        }

        // ── Comment reply script ──────────────────────────────
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // ── Pass PHP data to JavaScript ───────────────────────
        wp_localize_script('hidayah-scripts', 'hidayahData', array(
            'ajaxUrl'      => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('hidayah_nonce'),
            'homeUrl'      => home_url(),
            'locale'       => get_locale(), // e.g. 'bn_BD' or 'en_US'
            'banglaDigits' => preg_split('//u', __('0123456789', 'hidayah'), -1, PREG_SPLIT_NO_EMPTY),
        ));

        // ── Prayer Widget i18n strings (overrides JS defaults) ──
        wp_localize_script('hidayah-scripts', 'PRAYER_WIDGET_CONFIG', array(
            'enabled'               => true,
            'apiBase'               => 'https://api.aladhan.com/v1',
            'method'                => 3,
            'school'                => 1,
            'floatingPosition'      => 'left',
            'use24Hour'             => false,
            'autoRequestLocation'   => true,
            'highAccuracy'          => false,
            'geoTimeoutMs'          => 20000,
            'geoMaximumAgeMs'       => 300000,
            'refreshIntervalMinutes' => 15,
            'fallbackCoords'        => array('latitude' => 24.3745, 'longitude' => 88.6042),
            'fallbackCity'          => __('Rajshahi', 'hidayah'),
            'prayerNames'           => array(
                'Fajr'    => __('Fajr', 'hidayah'),
                'Dhuhr'   => __('Dhuhr', 'hidayah'),
                'Asr'     => __('Asr', 'hidayah'),
                'Maghrib' => __('Maghrib', 'hidayah'),
                'Isha'    => __('Isha', 'hidayah'),
            ),
            'labels'                => array(
                'nextPrayerPrefix'    => __('Next Prayer', 'hidayah'),
                'locationPending'     => __('Waiting for location permission...', 'hidayah'),
                'locationDenied'      => __('Location permission denied', 'hidayah'),
                'locationUnavailable' => __('Location not supported in this browser', 'hidayah'),
                'locationTimeout'     => __('Location timeout, please try again', 'hidayah'),
                'locationRetrying'    => __('Retrying location...', 'hidayah'),
                'loading'             => __('Loading prayer times...', 'hidayah'),
                'fetchError'          => __('Could not load prayer times', 'hidayah'),
                'retryHint'           => __('Click the floating button to retry', 'hidayah'),
                'prayerTimesTitle'    => __("Today's Prayer Times", 'hidayah'),
                'nextPrayerText'      => __('Next Prayer', 'hidayah'),
                'sehri'               => __('Sehri Ends', 'hidayah'),
                'iftar'               => __('Iftar', 'hidayah'),
                'tomorrowFajr'        => __("Tomorrow's Fajr", 'hidayah'),
                'am'                  => __('AM', 'hidayah'),
                'pm'                  => __('PM', 'hidayah'),
                'gps'                 => __('GPS', 'hidayah'),
                'hijri'               => __('Hijri', 'hidayah'),
            ),
            'districts'             => array(
                'Dhaka' => __('Dhaka', 'hidayah'),
                'Faridpur' => __('Faridpur', 'hidayah'),
                'Gazipur' => __('Gazipur', 'hidayah'),
                'Gopalganj' => __('Gopalganj', 'hidayah'),
                'Kishoreganj' => __('Kishoreganj', 'hidayah'),
                'Madaripur' => __('Madaripur', 'hidayah'),
                'Manikganj' => __('Manikganj', 'hidayah'),
                'Munshiganj' => __('Munshiganj', 'hidayah'),
                'Narayanganj' => __('Narayanganj', 'hidayah'),
                'Narsingdi' => __('Narsingdi', 'hidayah'),
                'Rajbari' => __('Rajbari', 'hidayah'),
                'Shariatpur' => __('Shariatpur', 'hidayah'),
                'Tangail' => __('Tangail', 'hidayah'),
                'Banderban' => __('Banderban', 'hidayah'),
                'Brahmanbaria' => __('Brahmanbaria', 'hidayah'),
                'Chandpur' => __('Chandpur', 'hidayah'),
                'Chittagong' => __('Chittagong', 'hidayah'),
                'Chattogram' => __('Chattogram', 'hidayah'),
                'Comilla' => __('Comilla', 'hidayah'),
                'Cumilla' => __('Cumilla', 'hidayah'),
                'Cox\'s Bazar' => __('Cox\'s Bazar', 'hidayah'),
                'Feni' => __('Feni', 'hidayah'),
                'Khagrachari' => __('Khagrachari', 'hidayah'),
                'Lakshmipur' => __('Lakshmipur', 'hidayah'),
                'Noakhali' => __('Noakhali', 'hidayah'),
                'Rangamati' => __('Rangamati', 'hidayah'),
                'Habiganj' => __('Habiganj', 'hidayah'),
                'Moulvibazar' => __('Moulvibazar', 'hidayah'),
                'Sunamganj' => __('Sunamganj', 'hidayah'),
                'Sylhet' => __('Sylhet', 'hidayah'),
                'Bagerhat' => __('Bagerhat', 'hidayah'),
                'Chuadanga' => __('Chuadanga', 'hidayah'),
                'Jessore' => __('Jessore', 'hidayah'),
                'Jashore' => __('Jashore', 'hidayah'),
                'Jhenaidah' => __('Jhenaidah', 'hidayah'),
                'Khulna' => __('Khulna', 'hidayah'),
                'Kushtia' => __('Kushtia', 'hidayah'),
                'Magura' => __('Magura', 'hidayah'),
                'Meherpur' => __('Meherpur', 'hidayah'),
                'Narail' => __('Narail', 'hidayah'),
                'Satkhira' => __('Satkhira', 'hidayah'),
                'Bogra' => __('Bogra', 'hidayah'),
                'Bogura' => __('Bogura', 'hidayah'),
                'Joypurhat' => __('Joypurhat', 'hidayah'),
                'Naogaon' => __('Naogaon', 'hidayah'),
                'Natore' => __('Natore', 'hidayah'),
                'Chapai Nawabganj' => __('Chapai Nawabganj', 'hidayah'),
                'Pabna' => __('Pabna', 'hidayah'),
                'Sirajganj' => __('Sirajganj', 'hidayah'),
                'Dinajpur' => __('Dinajpur', 'hidayah'),
                'Gaibandha' => __('Gaibandha', 'hidayah'),
                'Kurigram' => __('Kurigram', 'hidayah'),
                'Lalmonirhat' => __('Lalmonirhat', 'hidayah'),
                'Nilphamari' => __('Nilphamari', 'hidayah'),
                'Panchagarh' => __('Panchagarh', 'hidayah'),
                'Rangpur' => __('Rangpur', 'hidayah'),
                'Thakurgaon' => __('Thakurgaon', 'hidayah'),
                'Berguna' => __('Berguna', 'hidayah'),
                'Barguna' => __('Barguna', 'hidayah'),
                'Barisal' => __('Barisal', 'hidayah'),
                'Barishal' => __('Barishal', 'hidayah'),
                'Bhola' => __('Bhola', 'hidayah'),
                'Jhalokati' => __('Jhalokati', 'hidayah'),
                'Patuakhali' => __('Patuakhali', 'hidayah'),
                'Pirojpur' => __('Pirojpur', 'hidayah'),
                'Jamalpur' => __('Jamalpur', 'hidayah'),
                'Mymensingh' => __('Mymensingh', 'hidayah'),
                'Netrokona' => __('Netrokona', 'hidayah'),
                'Sherpur' => __('Sherpur', 'hidayah'),
            ),

            'months'                => array(
                'Gregorian' => array(
                    'January' => __('January', 'hidayah'),
                    'February' => __('February', 'hidayah'),
                    'March' => __('March', 'hidayah'),
                    'April' => __('April', 'hidayah'),
                    'May' => __('May', 'hidayah'),
                    'June' => __('June', 'hidayah'),
                    'July' => __('July', 'hidayah'),
                    'August' => __('August', 'hidayah'),
                    'September' => __('September', 'hidayah'),
                    'October' => __('October', 'hidayah'),
                    'November' => __('November', 'hidayah'),
                    'December' => __('December', 'hidayah'),
                ),
                'Hijri'     => array(
                    'Muharram' => __('Muharram', 'hidayah'),
                    'Safar' => __('Safar', 'hidayah'),
                    "Rabi' al-awwal" => __("Rabi' al-awwal", 'hidayah'),
                    "Rabi' al-thani" => __("Rabi' al-thani", 'hidayah'),
                    'Jumada al-ula' => __('Jumada al-ula', 'hidayah'),
                    'Jumada al-akhira' => __('Jumada al-akhira', 'hidayah'),
                    'Rajab' => __('Rajab', 'hidayah'),
                    "Sha'ban" => __("Sha'ban", 'hidayah'),
                    'Ramadan' => __('Ramadan', 'hidayah'),
                    'Shawwal' => __('Shawwal', 'hidayah'),
                    'Dhu al-Qi\'dah' => __('Dhu al-Qi\'dah', 'hidayah'),
                    'Dhu al-Hijjah' => __('Dhu al-Hijjah', 'hidayah'),
                ),
            ),
            'days'                  => array(
                'Saturday' => __('Saturday', 'hidayah'),
                'Sunday' => __('Sunday', 'hidayah'),
                'Monday' => __('Monday', 'hidayah'),
                'Tuesday' => __('Tuesday', 'hidayah'),
                'Wednesday' => __('Wednesday', 'hidayah'),
                'Thursday' => __('Thursday', 'hidayah'),
                'Friday' => __('Friday', 'hidayah'),
            ),
        ));
    }
endif;

add_action('wp_enqueue_scripts', 'hidayah_scripts');
