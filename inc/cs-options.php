<?php
/**
 * Codestar Framework - Theme Options Panel
 * WordPress Admin > Theme Options
 *
 * All UI labels in English with i18n support for future translation.
 * Bengali strings remain only as default content values (site content, not UI).
 *
 * @package Hidayah
 */

if ( ! class_exists( 'CSF' ) ) { return; }

// ══════════════════════════════════════════════════════
// 1. Create Theme Options Panel
// ══════════════════════════════════════════════════════
CSF::createOptions( 'hidayah_options', array(
    'menu_title'          => __( 'Theme Options', 'hidayah' ),
    'menu_slug'           => 'hidayah-options',
    'menu_type'           => 'menu',
    'menu_icon'           => 'dashicons-admin-generic',
    'menu_position'       => 3,
    'framework_title'     => __( 'Hidayah Theme Settings', 'hidayah' ),
    'framework_version'   => '1.0.0',
    'footer_text'         => __( 'Hidayah Theme &mdash; Hoquer Dawat', 'hidayah' ),
    'theme_url'           => home_url( '/' ),
    'admin_bar_menu'      => true,
    'admin_bar_menu_icon' => 'dashicons-admin-generic',
    'admin_bar_menu_title' => __( 'Theme Options', 'hidayah' ),
    'show_search'         => true,
    'show_reset_all'      => true,
    'show_reset_section'  => true,
    'save_notice'         => __( 'Settings saved successfully.', 'hidayah' ),
    'reset_notice'        => __( 'Settings have been reset.', 'hidayah' ),
) );


// ══════════════════════════════════════════════════════
// 2. General Settings
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'general',
    'title'  => __( 'General Settings', 'hidayah' ),
    'icon'   => 'fa fa-cog',
    'fields' => array(

        array(
            'id'    => 'site_logo',
            'type'  => 'media',
            'title' => __( 'Site Logo', 'hidayah' ),
            'desc'  => __( 'Upload the logo shown in the header.', 'hidayah' ),
        ),

        array(
            'id'    => 'site_favicon',
            'type'  => 'media',
            'title' => __( 'Favicon', 'hidayah' ),
            'desc'  => __( '32×32 px icon shown in browser tab.', 'hidayah' ),
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 3. Header Settings
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'header',
    'title'  => __( 'Header Settings', 'hidayah' ),
    'icon'   => 'fa fa-window-maximize',
    'fields' => array(

        array(
            'id'      => 'header_site_title',
            'type'    => 'text',
            'title'   => __( 'Site Title (Header)', 'hidayah' ),
            'desc'    => __( 'Main title shown in the header beside the logo.', 'hidayah' ),
            'default' => "হক্বের দা'ওয়াত সিদ্দীক্বিয়া দরবার শরীফ",
        ),

        array(
            'id'      => 'header_site_subtitle',
            'type'    => 'text',
            'title'   => __( 'Site Subtitle (Header)', 'hidayah' ),
            'desc'    => __( 'Tagline shown below the site title in the header.', 'hidayah' ),
            'default' => "একটি দ্বীনি ও তরিকত ভিত্তিক প্রকাশনা এবং সেবা কেন্দ্র",
        ),

        array(
            'id'      => 'header_donation_url',
            'type'    => 'text',
            'title'   => __( 'Donation / Hadiya Button URL', 'hidayah' ),
            'desc'    => __( 'Link for the "হাদিয়া" button in the header.', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'id'      => 'header_donation_label',
            'type'    => 'text',
            'title'   => __( 'Donation Button Label', 'hidayah' ),
            'default' => 'হাদিয়া',
        ),

        array(
            'id'      => 'header_show_cart',
            'type'    => 'switcher',
            'title'   => __( 'Show Cart Icon in Header', 'hidayah' ),
            'default' => true,
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 4. Footer Settings
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'footer',
    'title'  => __( 'Footer Settings', 'hidayah' ),
    'icon'   => 'fa fa-window-minimize',
    'fields' => array(

        array(
            'id'      => 'footer_about_text',
            'type'    => 'textarea',
            'title'   => __( 'Footer Description', 'hidayah' ),
            'desc'    => __( 'Short text shown in the footer brand column.', 'hidayah' ),
            'default' => 'আমরা সত্যের পথে আহবান জানাই। হক্কানী আলেমদের সরাসরি বয়ান ও নির্ভরযোগ্য কন্টেন্ট সরবরাহ করাই আমাদের মূল লক্ষ্য।',
        ),

        array(
            'id'      => 'footer_copyright',
            'type'    => 'text',
            'title'   => __( 'Copyright Text', 'hidayah' ),
            'desc'    => __( 'Shown in the footer bottom bar after the site name and year.', 'hidayah' ),
            'default' => 'All rights reserved.',
        ),

        array(
            'id'      => 'footer_newsletter_enable',
            'type'    => 'switcher',
            'title'   => __( 'Show Newsletter Box', 'hidayah' ),
            'desc'    => __( 'Toggle the email newsletter input in the footer.', 'hidayah' ),
            'default' => true,
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 5. Homepage Settings
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'homepage',
    'title'  => __( 'Homepage Settings', 'hidayah' ),
    'icon'   => 'fa fa-home',
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => __( 'Hero Section', 'hidayah' ),
        ),

        array(
            'id'      => 'hero_badge_text',
            'type'    => 'text',
            'title'   => __( 'Hero Badge Text', 'hidayah' ),
            'desc'    => __( 'Small badge shown above the hero title.', 'hidayah' ),
            'default' => "বিসমিল্লাহির রাহমানির রাহীম",
        ),

        array(
            'id'      => 'hero_title',
            'type'    => 'text',
            'title'   => __( 'Hero Title', 'hidayah' ),
            'default' => "কুরআন-সুন্নাহ ভিত্তিক খাঁটি ঈমান, আমল, আক্বিদা ও আখলাক গঠনে",
        ),

        array(
            'id'      => 'hero_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Hero Subtitle', 'hidayah' ),
            'default' => "হক্বানী আলেম ও অলী হওয়ার নিয়তে বায়াত ও ভর্তি হোন।",
        ),

        array(
            'id'      => 'hero_btn1_label',
            'type'    => 'text',
            'title'   => __( 'Hero Primary Button Label', 'hidayah' ),
            'default' => 'প্রকাশনা দেখুন',
        ),

        array(
            'id'      => 'hero_btn1_url',
            'type'    => 'text',
            'title'   => __( 'Hero Primary Button URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'id'      => 'hero_btn2_label',
            'type'    => 'text',
            'title'   => __( 'Hero Secondary Button Label', 'hidayah' ),
            'default' => 'লাইভ দেখুন',
        ),

        array(
            'id'      => 'hero_btn2_url',
            'type'    => 'text',
            'title'   => __( 'Hero Secondary Button URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'About / Darbar Section', 'hidayah' ),
        ),

        array(
            'id'      => 'about_text',
            'type'    => 'textarea',
            'title'   => __( 'About Darbar Text', 'hidayah' ),
            'desc'    => __( 'Short description shown in the About section.', 'hidayah' ),
            'default' => 'দরবার ও দাওয়াত একটি দলীলভিত্তিক, মার্জিত ও দায়িত্বশীল দ্বীনি মারকাজ।',
        ),

        array(
            'id'      => 'promo_text',
            'type'    => 'textarea',
            'title'   => __( 'Darbar Promo Banner Text', 'hidayah' ),
            'default' => 'তরবিয়ত, তাযকিয়া ও আত্মশুদ্ধির মাধ্যমে প্রকৃত মুসলিম হিসেবে গড়ে ওঠার প্রশিক্ষণ কেন্দ্র।',
        ),

        array(
            'id'      => 'promo_btn_label',
            'type'    => 'text',
            'title'   => __( 'Promo Banner Button Label', 'hidayah' ),
            'default' => 'আরও জানুন',
        ),

        array(
            'id'      => 'promo_btn_url',
            'type'    => 'text',
            'title'   => __( 'Promo Banner Button URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Section Visibility', 'hidayah' ),
        ),

        array(
            'id'      => 'show_audio_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Audio Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_video_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Video Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_book_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Books Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_monthly_hd_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Monthly Hidayah Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_dini_jiggasa_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Islamic Q&A Section', 'hidayah' ),
            'default' => true,
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 5. Contact Information
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'contact',
    'title'  => __( 'Contact Information', 'hidayah' ),
    'icon'   => 'fa fa-phone',
    'fields' => array(

        array(
            'id'      => 'contact_email',
            'type'    => 'text',
            'title'   => __( 'Email Address', 'hidayah' ),
            'desc'    => __( 'Shown in the footer and contact section.', 'hidayah' ),
            'default' => 'info@hoquerdawat.com',
        ),

        array(
            'id'      => 'contact_phone',
            'type'    => 'text',
            'title'   => __( 'Phone Number (Display)', 'hidayah' ),
            'desc'    => __( 'Displayed in the footer (can use Bangla numerals).', 'hidayah' ),
            'default' => '+৮৮০ ১২৩৪ ৫৬৭৮৯০',
        ),

        array(
            'id'      => 'contact_phone_raw',
            'type'    => 'text',
            'title'   => __( 'Phone Number (Link)', 'hidayah' ),
            'desc'    => __( 'Used in tel: links — must use English digits only.', 'hidayah' ),
            'default' => '+8801234567890',
        ),

        array(
            'id'      => 'contact_address',
            'type'    => 'text',
            'title'   => __( 'Address', 'hidayah' ),
            'default' => 'Dhaka, Bangladesh',
        ),

        array(
            'id'      => 'contact_whatsapp',
            'type'    => 'text',
            'title'   => __( 'WhatsApp Number', 'hidayah' ),
            'desc'    => __( 'Used in WhatsApp links. English digits only.', 'hidayah' ),
            'default' => '+8801234567890',
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 4. Social Media Links
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'social',
    'title'  => __( 'Social Media', 'hidayah' ),
    'icon'   => 'fa fa-share-alt',
    'fields' => array(

        array(
            'id'      => 'social_facebook',
            'type'    => 'text',
            'title'   => __( 'Facebook Page URL', 'hidayah' ),
            'default' => 'https://facebook.com/',
        ),

        array(
            'id'      => 'social_youtube',
            'type'    => 'text',
            'title'   => __( 'YouTube Channel URL', 'hidayah' ),
            'default' => 'https://youtube.com/',
        ),

        array(
            'id'      => 'social_whatsapp',
            'type'    => 'text',
            'title'   => __( 'WhatsApp Group Link', 'hidayah' ),
            'default' => 'https://whatsapp.com/',
        ),

        array(
            'id'      => 'social_telegram',
            'type'    => 'text',
            'title'   => __( 'Telegram Channel URL', 'hidayah' ),
            'default' => '',
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 6. Colors & Design
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'colors',
    'title'  => __( 'Colors & Design', 'hidayah' ),
    'icon'   => 'fa fa-paint-brush',
    'fields' => array(

        array(
            'id'      => 'primary_color',
            'type'    => 'color',
            'title'   => __( 'Primary Color', 'hidayah' ),
            'desc'    => __( 'Main brand color used throughout the theme.', 'hidayah' ),
            'default' => '#1a6b3c',
        ),

        array(
            'id'      => 'secondary_color',
            'type'    => 'color',
            'title'   => __( 'Secondary Color', 'hidayah' ),
            'default' => '#2d5a27',
        ),

        array(
            'id'      => 'accent_color',
            'type'    => 'color',
            'title'   => __( 'Accent Color (Gold)', 'hidayah' ),
            'desc'    => __( 'Used for highlights, badges, and decorative elements.', 'hidayah' ),
            'default' => '#c9a84c',
        ),

        array(
            'id'    => 'custom_css',
            'type'  => 'code_editor',
            'title' => __( 'Custom CSS', 'hidayah' ),
            'desc'  => __( 'Write additional CSS here — injected into &lt;head&gt;.', 'hidayah' ),
            'settings' => array(
                'mode'  => 'css',
                'theme' => 'monokai',
            ),
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// 7. Header / Footer Scripts
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'scripts',
    'title'  => __( 'Header / Footer Code', 'hidayah' ),
    'icon'   => 'fa fa-code',
    'fields' => array(

        array(
            'id'    => 'header_scripts',
            'type'  => 'code_editor',
            'title' => __( 'Header Code', 'hidayah' ),
            'desc'  => __( 'Inserted before &lt;/head&gt; — use for Google Tag Manager, Meta Pixel, etc.', 'hidayah' ),
            'settings' => array( 'mode' => 'html' ),
        ),

        array(
            'id'    => 'footer_scripts',
            'type'  => 'code_editor',
            'title' => __( 'Footer Code', 'hidayah' ),
            'desc'  => __( 'Inserted before &lt;/body&gt; — use for analytics or chat widgets.', 'hidayah' ),
            'settings' => array( 'mode' => 'html' ),
        ),

    ),
) );


// ══════════════════════════════════════════════════════
// Helper Function — easy option retrieval
// ══════════════════════════════════════════════════════
if ( ! function_exists( 'hidayah_opt' ) ) {
    /**
     * Get a Hidayah theme option value.
     *
     * Usage:
     *   hidayah_opt( 'contact_email' )
     *   hidayah_opt( 'primary_color', '#1a6b3c' )
     *
     * @param string $key     Option key.
     * @param mixed  $default Fallback value if option is empty.
     * @return mixed
     */
    function hidayah_opt( $key, $default = '' ) {
        $options = get_option( 'hidayah_options' );
        if ( isset( $options[ $key ] ) && $options[ $key ] !== '' ) {
            return $options[ $key ];
        }
        return $default;
    }
}


// ══════════════════════════════════════════════════════
// Inject Custom CSS & Color Variables into <head>
// ══════════════════════════════════════════════════════
add_action( 'wp_head', function () {

    // CSS custom property overrides (color theming)
    $primary   = hidayah_opt( 'primary_color',   '#1a6b3c' );
    $secondary = hidayah_opt( 'secondary_color',  '#2d5a27' );
    $accent    = hidayah_opt( 'accent_color',     '#c9a84c' );

    echo '<style id="hidayah-color-vars">:root{'
        . '--clr-primary:'   . esc_attr( $primary )   . ';'
        . '--clr-secondary:' . esc_attr( $secondary ) . ';'
        . '--clr-accent:'    . esc_attr( $accent )    . ';'
        . '}</style>' . PHP_EOL;

    // Admin-supplied custom CSS
    $custom_css = hidayah_opt( 'custom_css' );
    if ( $custom_css ) {
        echo '<style id="hidayah-custom-css">' . wp_strip_all_tags( $custom_css ) . '</style>' . PHP_EOL;
    }

    // Header scripts (GTM, Pixel, etc.) — trusted admin input
    $header_scripts = hidayah_opt( 'header_scripts' );
    if ( $header_scripts ) {
        echo $header_scripts . PHP_EOL;
    }

} );

// ══════════════════════════════════════════════════════
// Inject Footer Scripts before </body>
// ══════════════════════════════════════════════════════
add_action( 'wp_footer', function () {

    $footer_scripts = hidayah_opt( 'footer_scripts' );
    if ( $footer_scripts ) {
        echo $footer_scripts . PHP_EOL;
    }

}, 99 );
