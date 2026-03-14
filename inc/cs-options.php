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

        array(
            'id'      => 'header_hide_donation_mobile',
            'type'    => 'switcher',
            'title'   => __( 'Hide Donation Button on Mobile', 'hidayah' ),
            'desc'    => __( 'If enabled, the donation button (হাদিয়া) will be hidden on screens smaller than 992px.', 'hidayah' ),
            'default' => false,
        ),

        array(
            'id'      => 'header_show_date',
            'type'    => 'switcher',
            'title'   => __( 'Show Date in Header', 'hidayah' ),
            'desc'    => __( 'Show or hide the Hijri and Gregorian date in the header.', 'hidayah' ),
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
            'id'    => 'footer_about_text',
            'type'  => 'textarea',
            'title' => __( 'Footer Description', 'hidayah' ),
            'desc'  => __( 'Short text shown in the footer brand column.', 'hidayah' ),
            'default' => 'আমরা সত্যের পথে আহবান জানাই। হক্কানী আলেমদের সরাসরি বয়ান ও নির্ভরযোগ্য কন্টেন্ট সরবরাহ করাই আমাদের মূল লক্ষ্য।',
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Contact Bridge CTA', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_kicker',
            'type'    => 'text',
            'title'   => __( 'CTA Kicker (Badge)', 'hidayah' ),
            'default' => 'যোগাযোগ শুরু করুন',
        ),

        array(
            'id'      => 'footer_cta_title',
            'type'    => 'text',
            'title'   => __( 'CTA Title', 'hidayah' ),
            'default' => 'দ্বীনি জিজ্ঞাসা বা সহায়তা দরকার?',
        ),

        array(
            'id'      => 'footer_cta_desc',
            'type'    => 'textarea',
            'title'   => __( 'CTA Description', 'hidayah' ),
            'default' => 'আপনি প্রশ্ন করতে চাইলে, বিস্তারিত বার্তা পাঠাতে চাইলে বা সরাসরি কল করতে চাইলে নিচের অপশনগুলো থেকে একটি বেছে নিতে পারেন।',
        ),

        // Statistics Group (Simplified as separate fields)
        array(
            'type'    => 'subheading',
            'content' => __( 'CTA Statistics', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_stat1_num',
            'type'    => 'text',
            'title'   => __( 'Stat 1 Number', 'hidayah' ),
            'default' => '২৪/৭',
        ),
        array(
            'id'      => 'footer_cta_stat1_label',
            'type'    => 'text',
            'title'   => __( 'Stat 1 Label', 'hidayah' ),
            'default' => 'অনলাইন সেবা',
        ),

        array(
            'id'      => 'footer_cta_stat2_num',
            'type'    => 'text',
            'title'   => __( 'Stat 2 Number', 'hidayah' ),
            'default' => '১০০+',
        ),
        array(
            'id'      => 'footer_cta_stat2_label',
            'type'    => 'text',
            'title'   => __( 'Stat 2 Label', 'hidayah' ),
            'default' => 'দ্বীনি প্রশ্নের উত্তর',
        ),

        array(
            'id'      => 'footer_cta_stat3_num',
            'type'    => 'text',
            'title'   => __( 'Stat 3 Number', 'hidayah' ),
            'default' => '৫০০০+',
        ),
        array(
            'id'      => 'footer_cta_stat3_label',
            'type'    => 'text',
            'title'   => __( 'Stat 3 Label', 'hidayah' ),
            'default' => 'সন্তুষ্ট মুসলিম',
        ),

        // Contact Cards Group
        array(
            'type'    => 'subheading',
            'content' => __( 'Contact Cards', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_card1_title',
            'type'    => 'text',
            'title'   => __( 'Card 1 Title', 'hidayah' ),
            'default' => 'যোগাযোগ ফর্ম',
        ),
        array(
            'id'      => 'footer_cta_card1_desc',
            'type'    => 'text',
            'title'   => __( 'Card 1 Desc', 'hidayah' ),
            'default' => 'বিস্তারিত মেসেজ পাঠানোর জন্য',
        ),
        array(
            'id'      => 'footer_cta_card1_url',
            'type'    => 'text',
            'title'   => __( 'Card 1 URL', 'hidayah' ),
            'default' => '/contact',
        ),

        array(
            'id'      => 'footer_cta_card2_title',
            'type'    => 'text',
            'title'   => __( 'Card 2 Title', 'hidayah' ),
            'default' => 'ইমেইল সাপোর্ট',
        ),
        array(
            'id'      => 'footer_cta_card2_desc',
            'type'    => 'text',
            'title'   => __( 'Card 2 Desc', 'hidayah' ),
            'default' => 'ডকুমেন্ট বা লিখিত জিজ্ঞাসার জন্য',
        ),

        array(
            'id'      => 'footer_cta_card3_title',
            'type'    => 'text',
            'title'   => __( 'Card 3 Title', 'hidayah' ),
            'default' => 'ফোন কল',
        ),
        array(
            'id'      => 'footer_cta_card3_desc',
            'type'    => 'text',
            'title'   => __( 'Card 3 Desc', 'hidayah' ),
            'default' => 'তাৎক্ষণিক সহায়তার জন্য',
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
            'type'    => 'subheading',
            'content' => __( 'Book Corner Section', 'hidayah' ),
        ),

        array(
            'id'      => 'book_sales_title',
            'type'    => 'text',
            'title'   => __( 'Book Corner Title', 'hidayah' ),
            'default' => 'বই বিক্রয় কর্নার',
        ),

        array(
            'id'      => 'book_sales_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Book Corner Subtitle', 'hidayah' ),
            'default' => '',
        ),

        array(
            'id'      => 'book_sales_btn_label',
            'type'    => 'text',
            'title'   => __( 'View More Button Label', 'hidayah' ),
            'default' => 'আরও দেখুন',
        ),

        array(
            'id'      => 'book_sales_btn_url',
            'type'    => 'text',
            'title'   => __( 'View More Button URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'About Section', 'hidayah' ),
        ),

        array(
            'id'      => 'about_title',
            'type'    => 'text',
            'title'   => __( 'About Title', 'hidayah' ),
            'default' => "দরবার ও দাওয়াত সম্পর্কে",
        ),

        array(
            'id'      => 'about_desc',
            'type'    => 'textarea',
            'title'   => __( 'About Description', 'hidayah' ),
            'default' => "দরবার ও দাওয়াত একটি দলীলভিত্তিক, মার্জিত ও দায়িত্বশীল দ্বীনি মারকাজ। আমাদের লক্ষ্য হলো পবিত্র কুরআন ও সহীহ হাদীসের আলোকে বিশুদ্ধ ইসলামী জ্ঞান প্রচার করা এবং তরবিয়ত ও তাযকিয়ার মাধ্যমে প্রকৃত মুসলিম হিসেবে গড়ে তোলা।",
        ),

        array(
            'id'      => 'about_btn1_label',
            'type'    => 'text',
            'title'   => __( 'Button 1 Label', 'hidayah' ),
            'default' => 'বিস্তারিত জানুন →',
        ),

        array(
            'id'      => 'about_btn1_url',
            'type'    => 'text',
            'title'   => __( 'Button 1 URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'id'      => 'about_btn2_label',
            'type'    => 'text',
            'title'   => __( 'Button 2 Label', 'hidayah' ),
            'default' => 'যোগাযোগ করুন →',
        ),

        array(
            'id'      => 'about_btn2_url',
            'type'    => 'text',
            'title'   => __( 'Button 2 URL', 'hidayah' ),
            'default' => '#',
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Monthly Publication Section', 'hidayah' ),
        ),

        array(
            'id'      => 'monthly_hd_title',
            'type'    => 'text',
            'title'   => __( 'Section Title', 'hidayah' ),
            'default' => 'মাসিক হক্বের দাওয়াত - প্রকাশনা',
        ),

        array(
            'id'      => 'monthly_hd_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Section Subtitle', 'hidayah' ),
            'default' => 'সহীহ আকিদা, ইবাদত ও তাযকিয়াহভিত্তিক মাসিক দলীলসমৃদ্ধ প্রকাশনা',
        ),

        array(
            'id'      => 'monthly_hd_btn_label',
            'type'    => 'text',
            'title'   => __( 'View More Button Label', 'hidayah' ),
            'default' => 'আরও দেখুন →',
        ),

        array(
            'id'      => 'monthly_hd_btn_url',
            'type'    => 'text',
            'title'   => __( 'View More Button URL', 'hidayah' ),
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
