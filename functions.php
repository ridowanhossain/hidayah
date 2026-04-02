<?php
/**
 * Hidayah Theme - Functions
 * Main entry point: loads the framework and all inc/ files.
 *
 * @package Hidayah
 * @version 1.0.0
 */

// Define theme directory constants
define( 'HIDAYAH_DIR', get_template_directory() );
define( 'HIDAYAH_URI', get_template_directory_uri() );

// ── Load Codestar Framework (Theme Options) ───────────────
if ( file_exists( HIDAYAH_DIR . '/inc/codestar-framework/codestar-framework.php' ) ) {
    require_once HIDAYAH_DIR . '/inc/codestar-framework/codestar-framework.php';
}

// ── Load inc/ files ───────────────────────────────────────
require_once HIDAYAH_DIR . '/inc/theme-support.php';
require_once HIDAYAH_DIR . '/inc/nav-walker.php';
require_once HIDAYAH_DIR . '/inc/enqueue.php';
require_once HIDAYAH_DIR . '/inc/menus.php';
require_once HIDAYAH_DIR . '/inc/custom-post-types.php';
require_once HIDAYAH_DIR . '/inc/taxonomies.php';
require_once HIDAYAH_DIR . '/inc/widgets.php';
require_once HIDAYAH_DIR . '/inc/shortcodes.php';
require_once HIDAYAH_DIR . '/inc/helpers.php';
require_once HIDAYAH_DIR . '/inc/open-graph.php';
require_once HIDAYAH_DIR . '/inc/seo.php';
require_once HIDAYAH_DIR . '/inc/template-loader.php';
require_once HIDAYAH_DIR . '/inc/woocommerce-integration.php';

// ── Theme Options Panel (Codestar) ────────────────────────
require_once HIDAYAH_DIR . '/inc/cs-options.php';
require_once HIDAYAH_DIR . '/inc/meta-boxes.php';
require_once HIDAYAH_DIR . '/inc/ajax-audio-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-book-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-jiggasa-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-monthly-hd-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-notice-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-gallery-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-probondho-filters.php';
require_once HIDAYAH_DIR . '/inc/ajax-video-filters.php';

// ── AJAX Actions: Jiggasa Vote ───────────────────────
function hidayah_jiggasa_vote() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    $type    = sanitize_text_field( $_POST['type'] ?? '' );

    if ( ! $post_id || ! in_array( $type, array( 'up', 'down' ), true ) ) {
        wp_send_json_error();
    }

    $cookie_key = 'jiggasa_vote_' . $post_id;
    if ( isset( $_COOKIE[ $cookie_key ] ) ) {
        wp_send_json_error( array( 'message' => 'already_voted' ) );
    }

    $meta_key = $type === 'up' ? '_jiggasa_votes_up' : '_jiggasa_votes_down';
    $current  = (int) get_post_meta( $post_id, $meta_key, true );
    update_post_meta( $post_id, $meta_key, $current + 1 );

    setcookie( $cookie_key, '1', time() + 86400 * 30, '/' );

    wp_send_json_success( array(
        'up'   => (int) get_post_meta( $post_id, '_jiggasa_votes_up', true ),
        'down' => (int) get_post_meta( $post_id, '_jiggasa_votes_down', true ),
    ) );
}
add_action( 'wp_ajax_jiggasa_vote', 'hidayah_jiggasa_vote' );
add_action( 'wp_ajax_nopriv_jiggasa_vote', 'hidayah_jiggasa_vote' );

// ── AJAX Actions: Monthly Download Count ───────────────
function hidayah_update_download_count() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    if ( ! $post_id ) {
        wp_send_json_error();
    }

    $count = (int) get_post_meta( $post_id, '_pdf_download_count', true );
    update_post_meta( $post_id, '_pdf_download_count', $count + 1 );

    wp_send_json_success( array( 'count' => $count + 1 ) );
}
add_action( 'wp_ajax_update_dl_count', 'hidayah_update_download_count' );
add_action( 'wp_ajax_nopriv_update_dl_count', 'hidayah_update_download_count' );

// ── AJAX Actions: Gallery ZIP Download ─────────────────
function hidayah_download_gallery_zip() {
    $post_id = absint( $_GET['post_id'] ?? 0 );
    $photos  = get_post_meta( $post_id, '_gallery_images', true );
    if ( ! $post_id || ! is_array( $photos ) || empty( $photos ) ) {
        wp_die();
    }

    if ( ! class_exists( 'ZipArchive' ) ) {
        wp_die( 'ZipArchive not available.' );
    }

    $zip = new ZipArchive();
    $tmp = sys_get_temp_dir() . '/gallery-' . $post_id . '.zip';
    if ( $zip->open( $tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
        wp_die();
    }

    foreach ( $photos as $id ) {
        $file = get_attached_file( $id );
        if ( $file && file_exists( $file ) ) {
            $zip->addFile( $file, basename( $file ) );
        }
    }

    $zip->close();

    header( 'Content-Type: application/zip' );
    header( 'Content-Disposition: attachment; filename="album.zip"' );
    header( 'Content-Length: ' . filesize( $tmp ) );
    readfile( $tmp );
    unlink( $tmp );
    exit;
}
add_action( 'wp_ajax_download_gallery_zip', 'hidayah_download_gallery_zip' );
add_action( 'wp_ajax_nopriv_download_gallery_zip', 'hidayah_download_gallery_zip' );

// ── Save Book Review Rating ─────────────────
function hidayah_save_book_rating( $comment_id, $comment_approved ) {
    if ( isset( $_POST['rating'] ) ) {
        $rating = absint( $_POST['rating'] );
        if ( $rating >= 1 && $rating <= 5 ) {
            add_comment_meta( $comment_id, 'rating', $rating );
            
            // Optional: update average rating on post meta
            $post_id = get_comment($comment_id)->comment_post_ID;
            $comments = get_comments(array('post_id' => $post_id, 'status' => 'approve'));
            $total = 0; $count = 0;
            foreach($comments as $c) {
                $r = intval(get_comment_meta($c->comment_ID, 'rating', true));
                if($r >= 1 && $r <= 5) {
                    $total += $r;
                    $count++;
                }
            }
            if($count > 0) {
                update_post_meta($post_id, '_rating', round($total/$count, 1));
                update_post_meta($post_id, '_rating_count', $count);
            }
        }
    }
}
add_action( 'comment_post', 'hidayah_save_book_rating', 10, 2 );

// ── Force Comments Open for Books (Products) ─────────────────
add_filter( 'comments_open', function( $open, $post_id ) {
    if ( get_post_type( $post_id ) === 'product' ) {
        return true;
    }
    return $open;
}, 10, 2 );
