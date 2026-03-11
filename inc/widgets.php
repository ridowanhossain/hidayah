<?php
/**
 * Widget Areas (Sidebars)
 * Registers all widget areas for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_widgets_init' ) ) :
    function hidayah_widgets_init() {

        // ── Main Sidebar ──────────────────────────────────────
        register_sidebar( array(
            'name'          => __( 'Main Sidebar', 'hidayah' ),
            'id'            => 'sidebar-main',
            'description'   => __( 'Displayed on archive and single pages.', 'hidayah' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );

        // ── Footer Widget Areas ───────────────────────────────
        register_sidebar( array(
            'name'          => __( 'Footer - Column 1', 'hidayah' ),
            'id'            => 'footer-1',
            'description'   => __( 'First column in the footer.', 'hidayah' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Footer - Column 2', 'hidayah' ),
            'id'            => 'footer-2',
            'description'   => __( 'Second column in the footer.', 'hidayah' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Footer - Column 3', 'hidayah' ),
            'id'            => 'footer-3',
            'description'   => __( 'Third column in the footer.', 'hidayah' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );

    }
endif;

add_action( 'widgets_init', 'hidayah_widgets_init' );
