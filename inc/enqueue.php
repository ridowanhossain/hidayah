<?php
/**
 * Scripts & Styles Enqueue
 * Loads all CSS and JS files for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_scripts' ) ) :
    function hidayah_scripts() {

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
            array( 'hidayah-google-fonts' ),
            filemtime( get_stylesheet_directory() . '/style.css' )
        );

        // ── Main JavaScript (Core UI) ──────────────────────
        wp_enqueue_script(
            'hidayah-scripts',
            HIDAYAH_URI . '/assets/js/script.js',
            array(),
            filemtime( HIDAYAH_DIR . '/assets/js/script.js' ),
            true
        );

        // ── Comment reply script ──────────────────────────────
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // ── Pass PHP data to JavaScript ───────────────────────
        wp_localize_script( 'hidayah-scripts', 'hidayahData', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'hidayah_nonce' ),
            'homeUrl' => home_url(),
        ) );

    }
endif;

add_action( 'wp_enqueue_scripts', 'hidayah_scripts' );
