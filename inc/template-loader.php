<?php
/**
 * Custom Template Loader
 * Routes WordPress template hierarchy to the /templates/ directory.
 *
 * @package Hidayah
 */

add_filter( 'template_include', function( $template ) {
    
    // If it's already a full path to a file that exists, we're good.
    // But WordPress just found index.php in the root.
    // We want to check if a more specific template exists in /templates/
    
    $file_name = basename( $template );
    
    // Define the subfolder path
    $templates_dir = HIDAYAH_DIR . '/templates/';
    
    // 1. Check for specific post type templates if we are on a single or archive
    if ( is_single() ) {
        $post_type = get_post_type();
        
        // 1.1 WooCommerce Specific Template in /woocommerce/ folder
        if ( $post_type === 'product' ) {
            $woo_template = HIDAYAH_DIR . '/woocommerce/single-product.php';
            if ( file_exists( $woo_template ) ) {
                return $woo_template;
            }
        }

        $specific = $templates_dir . "single-{$post_type}.php";
        if ( file_exists( $specific ) ) {
            return $specific;
        }
        $fallback = $templates_dir . 'single.php';
        if ( file_exists( $fallback ) ) {
            return $fallback;
        }
    }

    if ( is_archive() ) {
        $post_type = get_query_var( 'post_type' );
        if ( is_array( $post_type ) ) {
            $post_type = reset( $post_type );
        }
        
        if ( $post_type ) {
            $specific = $templates_dir . "archive-{$post_type}.php";
            if ( file_exists( $specific ) ) {
                return $specific;
            }
        }
        
        $fallback = $templates_dir . 'archive.php';
        if ( file_exists( $fallback ) ) {
            return $fallback;
        }
    }

    if ( is_page() && ! is_front_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        $specific = $templates_dir . "page-{$slug}.php";
        if ( file_exists( $specific ) ) {
            return $specific;
        }
        $fallback = $templates_dir . 'page.php';
        if ( file_exists( $fallback ) ) {
            return $fallback;
        }
    }

    if ( is_search() ) {
        $search = $templates_dir . 'search.php';
        if ( file_exists( $search ) ) {
            return $search;
        }
    }

    if ( is_404() ) {
        $error_page = $templates_dir . '404.php';
        if ( file_exists( $error_page ) ) {
            return $error_page;
        }
    }

    // Generic check for any other file moved to templates/
    // This handles cases where $template points to index.php but a more specific one exists in templates/
    $custom_template = $templates_dir . $file_name;
    if ( file_exists( $custom_template ) ) {
        return $custom_template;
    }

    return $template;
}, 99 );

/**
 * Filter search form to use the template in subfolder if needed.
 * Note: searchform.php is usually loaded via locate_template which prioritizes root.
 */
add_filter( 'get_search_form', function( $form ) {
    $searchform = HIDAYAH_DIR . '/templates/searchform.php';
    if ( file_exists( $searchform ) ) {
        ob_start();
        include $searchform;
        $form = ob_get_clean();
    }
    return $form;
} );
