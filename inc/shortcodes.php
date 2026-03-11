<?php
/**
 * Custom Shortcodes
 * Registers custom shortcodes for the Hidayah theme.
 *
 * @package Hidayah
 */

// ── Hijri Date Display Shortcode ──────────────────────────
// Usage: [hijri_date]
// Note: Renders client-side via JavaScript.
if ( ! function_exists( 'hidayah_hijri_date_shortcode' ) ) :
    function hidayah_hijri_date_shortcode() {
        return '<span class="hijri-date-display" id="hijriDateDisplay"></span>';
    }
endif;
add_shortcode( 'hijri_date', 'hidayah_hijri_date_shortcode' );


// ── Donation Button Shortcode ─────────────────────────────
// Usage: [donation_btn text="Donate" url="https://..."]
if ( ! function_exists( 'hidayah_donation_btn_shortcode' ) ) :
    function hidayah_donation_btn_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'text' => __( 'Donate', 'hidayah' ),
            'url'  => '#',
            'icon' => 'volunteer_activism',
        ), $atts, 'donation_btn' );

        return sprintf(
            '<a href="%s" class="donation-btn"><span class="material-symbols-outlined">%s</span><span class="donation-text">%s</span></a>',
            esc_url( $atts['url'] ),
            esc_html( $atts['icon'] ),
            esc_html( $atts['text'] )
        );
    }
endif;
add_shortcode( 'donation_btn', 'hidayah_donation_btn_shortcode' );
