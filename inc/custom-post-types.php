<?php

/**
 * Custom Post Types (CPT)
 * Registers all custom post types for the Hidayah theme.
 *
 * @package Hidayah
 */

if (! function_exists('hidayah_register_post_types')) :
    function hidayah_register_post_types()
    {

        // ── Audio Lectures ────────────────────────────────────
        register_post_type('audio', array(
            'labels'       => array(
                'name'          => __('Audio', 'hidayah'),
                'singular_name' => __('Audio Lecture', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Audio', 'hidayah'),
                'edit_item'     => __('Edit Audio', 'hidayah'),
                'all_items'     => __('All Audio', 'hidayah'),
                'search_items'  => __('Search Audio', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-microphone',
            'supports'     => array('title', 'editor', 'thumbnail', 'comments'),
            'rewrite'      => array('slug' => 'audio'),
            'show_in_rest' => true,
        ));

        // ── Video Lectures ────────────────────────────────────
        register_post_type('video', array(
            'labels'       => array(
                'name'          => __('Video', 'hidayah'),
                'singular_name' => __('Video Lecture', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Video', 'hidayah'),
                'edit_item'     => __('Edit Video', 'hidayah'),
                'all_items'     => __('All Videos', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-video-alt3',
            'supports'     => array('title', 'thumbnail', 'comments'),
            'rewrite'      => array('slug' => 'video'),
            'show_in_rest' => true,
        ));


        // ── Monthly Haquer Dawat (Magazine) ────────────────────
        register_post_type('monthly_magazine', array(
            'labels'       => array(
                'name'          => __('Monthly Haquer Dawat', 'hidayah'),
                'singular_name' => __('Monthly Haquer Dawat', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Issue', 'hidayah'),
                'all_items'     => __('All Issues', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-media-document',
            'supports'     => array('title', 'editor', 'thumbnail', 'comments'),
            'rewrite'      => array('slug' => 'monthly-magazine'),
            'show_in_rest' => true,
        ));

        // ── Islamic Q&A (Dini Jiggasa) ────────────────────────
        register_post_type('dini_jiggasa', array(
            'labels'       => array(
                'name'          => __('Islamic Q&A', 'hidayah'),
                'singular_name' => __('Islamic Q&A', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Question', 'hidayah'),
                'all_items'     => __('All Questions', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-editor-help',
            'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite'      => array('slug' => 'dini-jiggasa'),
            'show_in_rest' => true,
        ));

        // ── Articles / Essays (Probondho) ─────────────────────
        register_post_type('probondho', array(
            'labels'       => array(
                'name'          => __('Articles', 'hidayah'),
                'singular_name' => __('Article', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Article', 'hidayah'),
                'all_items'     => __('All Articles', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-edit-page',
            'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
            'rewrite'      => array('slug' => 'probondho'),
            'show_in_rest' => true,
        ));

        // ── Notices & Announcements ───────────────────────────
        register_post_type('notice', array(
            'labels'       => array(
                'name'          => __('Notices', 'hidayah'),
                'singular_name' => __('Notice', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Notice', 'hidayah'),
                'all_items'     => __('All Notices', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-megaphone',
            'supports'     => array('title', 'editor', 'thumbnail'),
            'rewrite'      => array('slug' => 'notice'),
            'show_in_rest' => true,
        ));

        // ── Photo Gallery ─────────────────────────────────────
        register_post_type('photo_gallery', array(
            'labels'       => array(
                'name'          => __('Photo Gallery', 'hidayah'),
                'singular_name' => __('Gallery', 'hidayah'),
                'add_new'       => __('Add New', 'hidayah'),
                'add_new_item'  => __('Add New Gallery', 'hidayah'),
                'all_items'     => __('All Galleries', 'hidayah'),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-format-gallery',
            'supports'     => array('title', 'editor', 'thumbnail'),
            'rewrite'      => array('slug' => 'photo-gallery'),
            'show_in_rest' => true,
        ));

        // ── Uttordata Taxonomy (for Dini Jiggasa) ────────────────
        register_taxonomy('uttordata', 'dini_jiggasa', array(
            'labels'            => array(
                'name'              => __('Responders', 'hidayah'),
                'singular_name'     => __('Responder', 'hidayah'),
                'search_items'      => __('Search Responders', 'hidayah'),
                'all_items'         => __('All Responders', 'hidayah'),
                'parent_item'       => __('Parent Responder', 'hidayah'),
                'parent_item_colon' => __('Parent Responder:', 'hidayah'),
                'edit_item'         => __('Edit Responder', 'hidayah'),
                'update_item'       => __('Update Responder', 'hidayah'),
                'add_new_item'      => __('Add New Responder', 'hidayah'),
                'new_item_name'     => __('Responder Name', 'hidayah'),
                'menu_name'         => __('Responders', 'hidayah'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'uttordata'),
            'show_in_rest'      => true,
        ));
    }
endif;

add_action('init', 'hidayah_register_post_types');

/**
 * Force comments to be open on specific custom post types
 * so that existing posts (created before 'comments' support was added)
 * will still show the comments section.
 */
add_filter('comments_open', 'hidayah_force_comments_open', 10, 2);
function hidayah_force_comments_open($open, $post_id) {
    $post = get_post($post_id);
    if ($post && in_array($post->post_type, array('audio', 'video', 'monthly_magazine', 'dini_jiggasa', 'probondho'))) {
        return true;
    }
    return $open;
}
