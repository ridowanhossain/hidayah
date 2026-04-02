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
        ),

        array(
            'id'      => 'header_site_subtitle',
            'type'    => 'text',
            'title'   => __( 'Site Subtitle (Header)', 'hidayah' ),
            'desc'    => __( 'Tagline shown below the site title in the header.', 'hidayah' ),
        ),

        array(
            'id'      => 'header_donation_url',
            'type'    => 'text',
            'title'   => __( 'Donation / Hadiya Button URL', 'hidayah' ),
            'desc'    => __( 'Link for the "Donation" button in the header.', 'hidayah' ),
        ),

        array(
            'id'      => 'header_donation_label',
            'type'    => 'text',
            'title'   => __( 'Donation Button Label', 'hidayah' ),
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
            'desc'    => __( 'If enabled, the donation button (Donation) will be hidden on screens smaller than 992px.', 'hidayah' ),
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
            'type'    => 'subheading',
            'content' => __( 'Contact Bridge CTA', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_kicker',
            'type'    => 'text',
            'title'   => __( 'CTA Kicker (Badge)', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_title',
            'type'    => 'text',
            'title'   => __( 'CTA Title', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_desc',
            'type'    => 'textarea',
            'title'   => __( 'CTA Description', 'hidayah' ),
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
        ),
        array(
            'id'      => 'footer_cta_stat1_label',
            'type'    => 'text',
            'title'   => __( 'Stat 1 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_stat2_num',
            'type'    => 'text',
            'title'   => __( 'Stat 2 Number', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_cta_stat2_label',
            'type'    => 'text',
            'title'   => __( 'Stat 2 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_stat3_num',
            'type'    => 'text',
            'title'   => __( 'Stat 3 Number', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_cta_stat3_label',
            'type'    => 'text',
            'title'   => __( 'Stat 3 Label', 'hidayah' ),
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
        ),
        array(
            'id'      => 'footer_cta_card1_desc',
            'type'    => 'text',
            'title'   => __( 'Card 1 Desc', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_cta_card1_url',
            'type'    => 'text',
            'title'   => __( 'Card 1 URL', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_card2_title',
            'type'    => 'text',
            'title'   => __( 'Card 2 Title', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_cta_card2_desc',
            'type'    => 'text',
            'title'   => __( 'Card 2 Desc', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_cta_card3_title',
            'type'    => 'text',
            'title'   => __( 'Card 3 Title', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_cta_card3_desc',
            'type'    => 'text',
            'title'   => __( 'Card 3 Desc', 'hidayah' ),
        ),


        array(
            'type'    => 'subheading',
            'content' => __( 'Footer Brand', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_use_logo',
            'type'    => 'switcher',
            'title'   => __( 'Use Logo Instead of Text', 'hidayah' ),
            'desc'    => __( 'ON = Show logo image. OFF = Show mosque icon + brand name text.', 'hidayah' ),
            'default' => false,
        ),

        array(
            'id'         => 'footer_logo',
            'type'       => 'media',
            'title'      => __( 'Footer Logo', 'hidayah' ),
            'desc'       => __( 'Upload logo image for the footer brand area.', 'hidayah' ),
            'dependency' => array( 'footer_use_logo', '==', 'true' ),
        ),

        array(
            'id'         => 'footer_brand_name',
            'type'       => 'text',
            'title'      => __( 'Footer Brand Name', 'hidayah' ),
            'desc'       => __( 'Text shown next to the mosque icon (e.g., Hoquer Dawat).', 'hidayah' ),
            'default'    => '',
            'dependency' => array( 'footer_use_logo', '==', 'false' ),
        ),

        array(
            'id'    => 'footer_about_text',
            'type'  => 'textarea',
            'title' => __( 'Footer Description', 'hidayah' ),
            'desc'  => __( 'Short text shown in the footer brand column.', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Social Media Links', 'hidayah' ),
        ),

        array(
            'id'      => 'social_facebook',
            'type'    => 'text',
            'title'   => __( 'Facebook Page URL', 'hidayah' ),
            'desc'    => __( 'Shown as the Facebook icon in the footer.', 'hidayah' ),
        ),

        array(
            'id'      => 'social_youtube',
            'type'    => 'text',
            'title'   => __( 'YouTube Channel URL', 'hidayah' ),
            'desc'    => __( 'Shown as the YouTube icon in the footer.', 'hidayah' ),
        ),

        array(
            'id'      => 'social_twitter',
            'type'    => 'text',
            'title'   => __( 'X (Twitter) Profile URL', 'hidayah' ),
            'desc'    => __( 'Shown as the X icon in the footer.', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Contact Information', 'hidayah' ),
        ),

        array(
            'id'      => 'contact_email',
            'type'    => 'text',
            'title'   => __( 'Email Address', 'hidayah' ),
            'desc'    => __( 'Shown in the footer contact column.', 'hidayah' ),
        ),

        array(
            'id'      => 'contact_phone',
            'type'    => 'text',
            'title'   => __( 'Phone Number (Display)', 'hidayah' ),
            'desc'    => __( 'Displayed in the footer.', 'hidayah' ),
        ),

        array(
            'id'      => 'contact_phone_raw',
            'type'    => 'text',
            'title'   => __( 'Phone Number (Link)', 'hidayah' ),
            'desc'    => __( 'Used in tel: links — must use English digits only.', 'hidayah' ),
        ),

        array(
            'id'      => 'contact_address',
            'type'    => 'text',
            'title'   => __( 'Address', 'hidayah' ),
        ),

        array(
            'id'      => 'contact_whatsapp',
            'type'    => 'text',
            'title'   => __( 'WhatsApp Number', 'hidayah' ),
            'desc'    => __( 'Used in WhatsApp links. English digits only.', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_copyright',
            'type'    => 'text',
            'title'   => __( 'Copyright Text', 'hidayah' ),
            'desc'    => __( 'Shown in the footer bottom bar after the site name and year.', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_col2_title',
            'type'    => 'text',
            'title'   => __( 'Footer Column 2 Title', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_col3_title',
            'type'    => 'text',
            'title'   => __( 'Footer Column 3 Title', 'hidayah' ),
        ),
        array(
            'id'      => 'footer_col4_title',
            'type'    => 'text',
            'title'   => __( 'Footer Column 4 Title', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Legal Links (Footer Bottom Bar)', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_privacy_text',
            'type'    => 'text',
            'title'   => __( 'Privacy Policy Text', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_privacy_url',
            'type'    => 'text',
            'title'   => __( 'Privacy Policy URL', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_terms_text',
            'type'    => 'text',
            'title'   => __( 'Terms & Conditions Text', 'hidayah' ),
        ),

        array(
            'id'      => 'footer_terms_url',
            'type'    => 'text',
            'title'   => __( 'Terms & Conditions URL', 'hidayah' ),
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
        ),

        array(
            'id'      => 'hero_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Hero Subtitle', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Book Corner Section', 'hidayah' ),
        ),

        array(
            'id'      => 'book_sales_title',
            'type'    => 'text',
            'title'   => __( 'Book Corner Title', 'hidayah' ),
        ),

        array(
            'id'      => 'book_sales_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Book Corner Subtitle', 'hidayah' ),
        ),

        array(
            'id'      => 'book_sales_btn_label',
            'type'    => 'text',
            'title'   => __( 'View More Button Label', 'hidayah' ),
        ),

        array(
            'id'      => 'book_sales_btn_url',
            'type'    => 'text',
            'title'   => __( 'View More Button URL', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'About Section', 'hidayah' ),
        ),

        array(
            'id'      => 'about_title',
            'type'    => 'text',
            'title'   => __( 'About Title', 'hidayah' ),
        ),

        array(
            'id'      => 'about_desc',
            'type'    => 'textarea',
            'title'   => __( 'About Description', 'hidayah' ),
        ),

        array(
            'id'      => 'about_btn1_label',
            'type'    => 'text',
            'title'   => __( 'Button 1 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'about_btn1_url',
            'type'    => 'text',
            'title'   => __( 'Button 1 URL', 'hidayah' ),
        ),

        array(
            'id'      => 'about_btn2_label',
            'type'    => 'text',
            'title'   => __( 'Button 2 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'about_btn2_url',
            'type'    => 'text',
            'title'   => __( 'Button 2 URL', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Monthly Magazine Section', 'hidayah' ),
        ),

        array(
            'id'      => 'monthly_magazine_title',
            'type'    => 'text',
            'title'   => __( 'Section Title', 'hidayah' ),
        ),

        array(
            'id'      => 'monthly_magazine_subtitle',
            'type'    => 'textarea',
            'title'   => __( 'Section Subtitle', 'hidayah' ),
        ),

        array(
            'id'      => 'monthly_magazine_btn_label',
            'type'    => 'text',
            'title'   => __( 'View More Button Label', 'hidayah' ),
        ),

        array(
            'id'      => 'monthly_magazine_btn_url',
            'type'    => 'text',
            'title'   => __( 'View More Button URL', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Articles Section (Probondho)', 'hidayah' ),
        ),

        array(
            'id'      => 'articles_title',
            'type'    => 'text',
            'title'   => __( 'Section Title', 'hidayah' ),
        ),

        array(
            'id'      => 'articles_btn_label',
            'type'    => 'text',
            'title'   => __( 'View All Button Label', 'hidayah' ),
        ),

        array(
            'id'      => 'articles_btn_url',
            'type'    => 'text',
            'title'   => __( 'View All Button URL', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Promo Wide Section', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_title',
            'type'    => 'text',
            'title'   => __( 'Promo Title', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_desc',
            'type'    => 'textarea',
            'title'   => __( 'Promo Description', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_btn1_label',
            'type'    => 'text',
            'title'   => __( 'Button 1 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_btn1_url',
            'type'    => 'text',
            'title'   => __( 'Button 1 URL', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_btn2_label',
            'type'    => 'text',
            'title'   => __( 'Button 2 Label', 'hidayah' ),
        ),

        array(
            'id'      => 'promo_btn2_url',
            'type'    => 'text',
            'title'   => __( 'Button 2 URL', 'hidayah' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __( 'Q&A Section (Islamic Q&A)', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_title',
            'type'    => 'text',
            'title'   => __( 'Section Title', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_subtitle',
            'type'    => 'text',
            'title'   => __( 'Section Subtitle', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_view_all_label',
            'type'    => 'text',
            'title'   => __( 'View All Button Label', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_view_all_url',
            'type'    => 'text',
            'title'   => __( 'View All Button URL', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_ask_label',
            'type'    => 'text',
            'title'   => __( 'Ask Question Label', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_ask_url',
            'type'    => 'text',
            'title'   => __( 'Ask Question URL', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_msg_label',
            'type'    => 'text',
            'title'   => __( 'Message Label', 'hidayah' ),
        ),

        array(
            'id'      => 'qa_msg_url',
            'type'    => 'text',
            'title'   => __( 'Message URL', 'hidayah' ),
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
            'id'      => 'show_about_section',
            'type'    => 'switcher',
            'title'   => __( 'Show About Section', 'hidayah' ),
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
            'title'   => __( 'Show Monthly Magazine Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_probondho_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Articles Section', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'show_promo_section',
            'type'    => 'switcher',
            'title'   => __( 'Show Promo Section', 'hidayah' ),
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
// 8. SEO Settings
// ══════════════════════════════════════════════════════
CSF::createSection( 'hidayah_options', array(
    'id'     => 'seo',
    'title'  => __( 'SEO Settings', 'hidayah' ),
    'icon'   => 'fa fa-search',
    'fields' => array(

        // ── Site-wide Meta ──────────────────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Site-wide Meta', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_site_description',
            'type'    => 'textarea',
            'title'   => __( 'Default Meta Description', 'hidayah' ),
            'desc'    => __( 'Used on the homepage and when a post has no excerpt. Keep under 160 characters.', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_title_separator',
            'type'    => 'text',
            'title'   => __( 'Title Separator', 'hidayah' ),
            'desc'    => __( 'Character between post title and site name, e.g.  |  or  –  or  »', 'hidayah' ),
            'default' => '|',
        ),

        array(
            'id'      => 'seo_default_og_image',
            'type'    => 'media',
            'title'   => __( 'Default OG / Share Image', 'hidayah' ),
            'desc'    => __( 'Shown when sharing a page that has no featured image. Recommended 1200×630 px.', 'hidayah' ),
        ),

        // ── Verification Codes ──────────────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Search Engine Verification', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_google_verification',
            'type'    => 'text',
            'title'   => __( 'Google Search Console Verification Code', 'hidayah' ),
            'desc'    => __( 'Paste only the content value from the meta tag, e.g. abc123XYZ', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_bing_verification',
            'type'    => 'text',
            'title'   => __( 'Bing Webmaster Verification Code', 'hidayah' ),
            'desc'    => __( 'Paste only the content value from the meta tag.', 'hidayah' ),
        ),

        // ── Organization Info (Schema) ───────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Organization / Schema Info', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_org_name',
            'type'    => 'text',
            'title'   => __( 'Organization Name', 'hidayah' ),
            'desc'    => __( 'Used in JSON-LD schema markup, e.g. Hoquer Dawat', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_org_type',
            'type'    => 'select',
            'title'   => __( 'Organization Type', 'hidayah' ),
            'desc'    => __( 'Helps Google understand the site category.', 'hidayah' ),
            'default' => 'ReligiousOrganization',
            'options' => array(
                'ReligiousOrganization' => __( 'Religious Organization', 'hidayah' ),
                'Organization'          => __( 'General Organization', 'hidayah' ),
                'EducationalOrganization' => __( 'Educational Organization', 'hidayah' ),
                'NGO'                   => __( 'NGO / Non-Profit', 'hidayah' ),
            ),
        ),

        array(
            'id'      => 'seo_org_description',
            'type'    => 'textarea',
            'title'   => __( 'Organization Description', 'hidayah' ),
            'desc'    => __( 'Brief description used in schema markup.', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_founder_name',
            'type'    => 'text',
            'title'   => __( 'Founder / Sheikh Name', 'hidayah' ),
            'desc'    => __( 'Used as author name in article schema, e.g. Sheikh Ibrahim', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_founding_year',
            'type'    => 'text',
            'title'   => __( 'Founded Year', 'hidayah' ),
            'desc'    => __( 'e.g. 2015', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_org_logo',
            'type'    => 'media',
            'title'   => __( 'Organization Logo (Schema)', 'hidayah' ),
            'desc'    => __( 'Used in Google Knowledge Panel schema. If empty, Site Logo is used.', 'hidayah' ),
        ),

        // ── Social Profiles (for Schema) ─────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Social Profiles (Schema / Sameās)', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_twitter_handle',
            'type'    => 'text',
            'title'   => __( 'Twitter / X Handle', 'hidayah' ),
            'desc'    => __( 'Without @, e.g. hoquerDawat', 'hidayah' ),
        ),

        // ── Schema Toggle ────────────────────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Schema & Structured Data', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_enable_schema',
            'type'    => 'switcher',
            'title'   => __( 'Enable JSON-LD Schema', 'hidayah' ),
            'desc'    => __( 'Outputs structured data for posts (Article, AudioObject, VideoObject, FAQPage, etc.) to help Google understand content.', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'seo_enable_breadcrumb_schema',
            'type'    => 'switcher',
            'title'   => __( 'Enable BreadcrumbList Schema', 'hidayah' ),
            'desc'    => __( 'Adds breadcrumb structured data so Google displays breadcrumbs in search results.', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'seo_enable_org_schema',
            'type'    => 'switcher',
            'title'   => __( 'Enable Organization Schema (Homepage)', 'hidayah' ),
            'desc'    => __( 'Outputs Organization/WebSite schema on the homepage for Google Knowledge Panel.', 'hidayah' ),
            'default' => true,
        ),

        // ── Canonical & Robots ───────────────────────────
        array(
            'type'    => 'subheading',
            'content' => __( 'Canonical & Robots', 'hidayah' ),
        ),

        array(
            'id'      => 'seo_enable_canonical',
            'type'    => 'switcher',
            'title'   => __( 'Output Canonical URL Tags', 'hidayah' ),
            'desc'    => __( 'Prevents duplicate content issues by specifying the preferred URL.', 'hidayah' ),
            'default' => true,
        ),

        array(
            'id'      => 'seo_noindex_archives',
            'type'    => 'switcher',
            'title'   => __( 'No-Index Archive / Category Pages', 'hidayah' ),
            'desc'    => __( 'Adds noindex to taxonomy archive and date archive pages to avoid thin content penalties.', 'hidayah' ),
            'default' => false,
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
