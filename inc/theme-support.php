<?php
/**
 * Theme Support
 * Enables various WordPress features for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_setup' ) ) :
    function hidayah_setup() {

        // Translation / i18n support
        load_theme_textdomain( 'hidayah', HIDAYAH_DIR . '/languages' );

        // Automatic <title> tag
        add_theme_support( 'title-tag' );

        // Featured image (post thumbnail) support
        add_theme_support( 'post-thumbnails' );

        // HTML5 markup support
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );

        // Custom logo support
        add_theme_support( 'custom-logo', array(
            'height'      => 100,
            'width'       => 100,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        // WooCommerce support
        add_theme_support( 'woocommerce' );

        // Post formats
        add_theme_support( 'post-formats', array( 'audio', 'video', 'gallery' ) );

        // Wide alignment support (Gutenberg block editor)
        add_theme_support( 'align-wide' );

        // Block editor styles
        add_theme_support( 'wp-block-styles' );

        // Responsive embeds
        add_theme_support( 'responsive-embeds' );

    }
endif;

add_action( 'after_setup_theme', 'hidayah_setup' );
