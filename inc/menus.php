<?php
/**
 * Navigation Menus
 * Registers all menu locations for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_register_menus' ) ) :
    function hidayah_register_menus() {
        register_nav_menus( array(
            'primary'      => __( 'Primary Menu', 'hidayah' ),
            'footer'       => __( 'Footer Bottom Menu', 'hidayah' ),
            'footer-quick' => __( 'Footer Quick Links', 'hidayah' ),
            'footer-cats'  => __( 'Footer Categories', 'hidayah' ),
            'social'       => __( 'Social Links Menu', 'hidayah' ),
        ) );
    }
endif;

add_action( 'init', 'hidayah_register_menus' );
