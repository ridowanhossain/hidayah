<?php
/**
 * Navigation Menu Filters
 *
 * Instead of a Walker class (which can cause signature-mismatch fatals
 * across WordPress versions), we use the built-in wp_nav_menu filters to:
 *   • Add 'nav-item' class to every top-level <li>
 *   • Add 'has-dropdown' class to any <li> that has children
 *   • Rename the inner <ul class="sub-menu"> to <ul class="dropdown-menu">
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_nav_menu_css_class' ) ) :
    /**
     * Filter CSS classes on each nav menu <li>.
     *
     * @param string[] $classes Array of the CSS classes for the <li>.
     * @param WP_Post  $item    The current menu item.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item.
     * @return string[]
     */
    function hidayah_nav_menu_css_class( $classes, $item, $args, $depth ) {
        // Only touch the 'primary' menu location
        if ( empty( $args->theme_location ) || $args->theme_location !== 'primary' ) {
            return $classes;
        }

        // Remove default WP classes we don't need
        $remove = array(
            'menu-item',
            'menu-item-type-post_type',
            'menu-item-type-taxonomy',
            'menu-item-type-custom',
            'menu-item-object-page',
            'menu-item-object-category',
            'menu-item-object-custom',
        );
        $classes = array_diff( $classes, $remove );

        // Top-level items get 'nav-item'
        if ( $depth === 0 ) {
            $classes[] = 'nav-item';
        }

        // Items with children get 'has-dropdown'
        if ( in_array( 'menu-item-has-children', $classes, true ) ) {
            $classes   = array_diff( $classes, array( 'menu-item-has-children' ) );
            $classes[] = 'has-dropdown';
        }

        // Current page / ancestor highlight
        $current_classes = array(
            'current-menu-item',
            'current-menu-ancestor',
            'current-menu-parent',
            'current_page_item',
            'current_page_ancestor',
            'current_page_parent',
        );
        $is_current = ! empty( array_intersect( $classes, $current_classes ) );
        $classes    = array_diff( $classes, $current_classes );
        if ( $is_current ) {
            $classes[] = 'active';
        }

        return array_values( array_unique( array_filter( $classes ) ) );
    }
endif;
add_filter( 'nav_menu_css_class', 'hidayah_nav_menu_css_class', 10, 4 );


if ( ! function_exists( 'hidayah_nav_menu_submenu_css_class' ) ) :
    /**
     * Rename "sub-menu" to "dropdown-menu" on every nested <ul>.
     *
     * @param string[] $classes Array of CSS classes for the <ul>.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of the sub-menu.
     * @return string[]
     */
    function hidayah_nav_menu_submenu_css_class( $classes, $args, $depth ) {
        if ( empty( $args->theme_location ) || $args->theme_location !== 'primary' ) {
            return $classes;
        }

        // Replace 'sub-menu' with 'dropdown-menu'
        $classes = array_map( function( $class ) {
            return $class === 'sub-menu' ? 'dropdown-menu' : $class;
        }, $classes );

        return $classes;
    }
endif;
add_filter( 'nav_menu_submenu_css_class', 'hidayah_nav_menu_submenu_css_class', 10, 3 );


if ( ! function_exists( 'hidayah_nav_menu_link_attributes' ) ) :
    /**
     * Wrap the menu link text in a <span> and add aria-current on current page.
     *
     * @param array    $atts The HTML attributes for the <a> tag.
     * @param WP_Post  $item The current menu item.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     * @param int      $depth Depth of the menu item.
     * @return array
     */
    function hidayah_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
        if ( empty( $args->theme_location ) || $args->theme_location !== 'primary' ) {
            return $atts;
        }

        $classes = (array) $item->classes;
        if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'active', $classes, true ) ) {
            $atts['aria-current'] = 'page';
        }

        return $atts;
    }
endif;
add_filter( 'nav_menu_link_attributes', 'hidayah_nav_menu_link_attributes', 10, 4 );
