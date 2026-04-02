<?php
/**
 * Hidayah Theme - Open Graph Meta Tags
 * Adds og:title, og:description, og:image, og:url tags for social sharing.
 *
 * @package Hidayah
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function hidayah_open_graph_tags() {

    // Don't add OG tags if a plugin (like Yoast, RankMath) is already handling it
    if (
        defined( 'WPSEO_VERSION' )          // Yoast SEO
        || defined( 'RANK_MATH_VERSION' )   // Rank Math
        || defined( 'AIOSEO_VERSION' )      // All in One SEO
    ) {
        return;
    }

    global $post;

    $og_title       = '';
    $og_url         = '';
    $og_description = '';
    $og_image       = '';
    $og_type        = 'website';

    // ── HOMEPAGE ────────────────────────────────────────────
    if ( is_front_page() || is_home() ) {
        $og_title       = get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' );
        $og_url         = home_url( '/' );
        $og_description = get_bloginfo( 'description' );
        $og_type        = 'website';

    // ── SINGULAR POSTS / PAGES ──────────────────────────────
    } elseif ( is_singular() && $post ) {
        $og_type  = 'article';
        $og_title = get_the_title( $post->ID );
        $og_url   = get_permalink( $post->ID );

        // Description: excerpt → content → site tagline
        if ( has_excerpt( $post->ID ) ) {
            $og_description = wp_strip_all_tags( get_the_excerpt( $post->ID ) );
        } elseif ( ! empty( $post->post_content ) ) {
            $og_description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
        } else {
            $og_description = get_bloginfo( 'description' );
        }

        // Image: post thumbnail
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            if ( $thumb ) {
                $og_image = $thumb[0];
            }
        }

        // Image: WooCommerce product gallery first image
        if ( ! $og_image && function_exists( 'wc_get_product' ) && get_post_type( $post->ID ) === 'product' ) {
            $product = wc_get_product( $post->ID );
            if ( $product ) {
                $img_id = $product->get_image_id();
                if ( $img_id ) {
                    $src = wp_get_attachment_image_src( $img_id, 'large' );
                    if ( $src ) {
                        $og_image = $src[0];
                    }
                }
            }
        }

        // Image: custom post-type-specific meta fields
        if ( ! $og_image ) {
            $meta_keys_to_try = array(
                '_magazine_cover',
                '_audio_thumbnail',
                '_thumbnail_url',
                '_gallery_images',
            );

            foreach ( $meta_keys_to_try as $key ) {
                $val = get_post_meta( $post->ID, $key, true );
                if ( ! $val ) continue;

                // _gallery_images: array of attachment IDs
                if ( $key === '_gallery_images' && is_array( $val ) && ! empty( $val ) ) {
                    $src = wp_get_attachment_image_src( $val[0], 'large' );
                    if ( $src ) {
                        $og_image = $src[0];
                        break;
                    }
                }

                // Numeric attachment ID
                if ( is_numeric( $val ) ) {
                    $src = wp_get_attachment_image_src( (int) $val, 'large' );
                    if ( $src ) {
                        $og_image = $src[0];
                        break;
                    }
                }

                // Direct URL
                if ( filter_var( $val, FILTER_VALIDATE_URL ) ) {
                    $og_image = esc_url( $val );
                    break;
                }
            }
        }

    // ── TAXONOMY ARCHIVES (topic, speaker, category …) ─────
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $term           = get_queried_object();
        $og_title       = $term->name . ' — ' . get_bloginfo( 'name' );
        $og_url         = get_term_link( $term );
        $og_description = ! empty( $term->description ) ? wp_strip_all_tags( $term->description ) : get_bloginfo( 'description' );
        $og_type        = 'website';

    // ── POST TYPE ARCHIVES ──────────────────────────────────
    } elseif ( is_post_type_archive() ) {
        $post_type_obj  = get_queried_object();
        $og_title       = $post_type_obj->labels->name . ' — ' . get_bloginfo( 'name' );
        $og_url         = get_post_type_archive_link( $post_type_obj->name );
        $og_description = get_bloginfo( 'description' );
        $og_type        = 'website';

    // ── SEARCH ──────────────────────────────────────────────
    } elseif ( is_search() ) {
        $og_title       = sprintf( __( 'Search: %s', 'hidayah' ), get_search_query() ) . ' — ' . get_bloginfo( 'name' );
        $og_url         = get_search_link();
        $og_description = get_bloginfo( 'description' );
        $og_type        = 'website';

    } else {
        // Generic fallback
        $og_title       = get_bloginfo( 'name' );
        $og_url         = home_url( '/' );
        $og_description = get_bloginfo( 'description' );
        $og_type        = 'website';
    }

    // ── GLOBAL IMAGE FALLBACK: site logo ───────────────────
    if ( ! $og_image ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $src ) {
                $og_image = $src[0];
            }
        }
    }

    // ── GLOBAL IMAGE FALLBACK: default OG image from Customizer ─
    if ( ! $og_image ) {
        $default_og = get_theme_mod( 'hidayah_default_og_image', '' );
        if ( $default_og ) {
            $og_image = esc_url( $default_og );
        }
    }

    // ── SANITISE ────────────────────────────────────────────
    $og_title       = esc_attr( wp_strip_all_tags( $og_title ) );
    $og_description = esc_attr( wp_strip_all_tags( $og_description ) );
    $og_url         = esc_url( $og_url );
    ?>
    <!-- Open Graph / Facebook -->
    <meta property="og:type"        content="<?php echo esc_attr( $og_type ); ?>" />
    <meta property="og:url"         content="<?php echo $og_url; ?>" />
    <meta property="og:title"       content="<?php echo $og_title; ?>" />
    <meta property="og:description" content="<?php echo $og_description; ?>" />
    <meta property="og:site_name"   content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
    <meta property="og:locale"      content="en_US" />
    <?php if ( $og_image ) : $og_image = esc_url( $og_image ); ?>
    <meta property="og:image"            content="<?php echo $og_image; ?>" />
    <meta property="og:image:secure_url" content="<?php echo $og_image; ?>" />
    <meta property="og:image:width"      content="1200" />
    <meta property="og:image:height"     content="630" />
    <meta property="og:image:type"       content="image/jpeg" />
    <?php endif; ?>

    <!-- WhatsApp / Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="<?php echo $og_title; ?>" />
    <meta name="twitter:description" content="<?php echo $og_description; ?>" />
    <?php if ( $og_image ) : ?>
    <meta name="twitter:image"       content="<?php echo $og_image; ?>" />
    <?php endif; ?>
    <?php
}
add_action( 'wp_head', 'hidayah_open_graph_tags', 1 ); // Priority 1 — As early as possible
