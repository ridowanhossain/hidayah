<?php
/**
 * Custom Post Types (CPT)
 * Registers all custom post types for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_register_post_types' ) ) :
    function hidayah_register_post_types() {

        // ── Audio Lectures ────────────────────────────────────
        register_post_type( 'audio', array(
            'labels'       => array(
                'name'          => __( 'Audio Lectures', 'hidayah' ),
                'singular_name' => __( 'Audio Lecture', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Audio', 'hidayah' ),
                'edit_item'     => __( 'Edit Audio', 'hidayah' ),
                'all_items'     => __( 'All Audio', 'hidayah' ),
                'search_items'  => __( 'Search Audio', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-microphone',
            'supports'     => array( 'title', 'editor', 'thumbnail' ),
            'rewrite'      => array( 'slug' => 'audio' ),
            'show_in_rest' => true,
        ) );

        // ── Video Lectures ────────────────────────────────────
        register_post_type( 'video', array(
            'labels'       => array(
                'name'          => __( 'Video Lectures', 'hidayah' ),
                'singular_name' => __( 'Video Lecture', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Video', 'hidayah' ),
                'edit_item'     => __( 'Edit Video', 'hidayah' ),
                'all_items'     => __( 'All Videos', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-video-alt3',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'rewrite'      => array( 'slug' => 'video' ),
            'show_in_rest' => true,
        ) );

        // ── Books / Kitab ─────────────────────────────────────
        register_post_type( 'book', array(
            'labels'       => array(
                'name'          => __( 'Books', 'hidayah' ),
                'singular_name' => __( 'Book', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Book', 'hidayah' ),
                'all_items'     => __( 'All Books', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-book-alt',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'rewrite'      => array( 'slug' => 'book' ),
            'show_in_rest' => true,
        ) );

        // ── Monthly Hidayah (Magazine) ─────────────────────────
        register_post_type( 'monthly_hd', array(
            'labels'       => array(
                'name'          => __( 'Monthly Hidayah', 'hidayah' ),
                'singular_name' => __( 'Monthly Hidayah', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Issue', 'hidayah' ),
                'all_items'     => __( 'All Issues', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-media-document',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'rewrite'      => array( 'slug' => 'monthly-hd' ),
            'show_in_rest' => true,
        ) );

        // ── Islamic Q&A (Dini Jiggasa) ────────────────────────
        register_post_type( 'dini_jiggasa', array(
            'labels'       => array(
                'name'          => __( 'Islamic Q&A', 'hidayah' ),
                'singular_name' => __( 'Islamic Q&A', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Question', 'hidayah' ),
                'all_items'     => __( 'All Questions', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-editor-help',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'rewrite'      => array( 'slug' => 'dini-jiggasa' ),
            'show_in_rest' => true,
        ) );

        // ── Articles / Essays (Probondho) ─────────────────────
        register_post_type( 'probondho', array(
            'labels'       => array(
                'name'          => __( 'Articles', 'hidayah' ),
                'singular_name' => __( 'Article', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Article', 'hidayah' ),
                'all_items'     => __( 'All Articles', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-edit-page',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
            'rewrite'      => array( 'slug' => 'probondho' ),
            'show_in_rest' => true,
        ) );

        // ── Notices & Announcements ───────────────────────────
        register_post_type( 'notice', array(
            'labels'       => array(
                'name'          => __( 'Notices', 'hidayah' ),
                'singular_name' => __( 'Notice', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Notice', 'hidayah' ),
                'all_items'     => __( 'All Notices', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-megaphone',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'rewrite'      => array( 'slug' => 'notice' ),
            'show_in_rest' => true,
        ) );

        // ── Photo Gallery ─────────────────────────────────────
        register_post_type( 'photo_gallery', array(
            'labels'       => array(
                'name'          => __( 'Photo Gallery', 'hidayah' ),
                'singular_name' => __( 'Gallery', 'hidayah' ),
                'add_new'       => __( 'Add New', 'hidayah' ),
                'add_new_item'  => __( 'Add New Gallery', 'hidayah' ),
                'all_items'     => __( 'All Galleries', 'hidayah' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-format-gallery',
            'supports'     => array( 'title', 'editor', 'thumbnail' ),
            'rewrite'      => array( 'slug' => 'photo-gallery' ),
            'show_in_rest' => true,
        ) );

    }
endif;

add_action( 'init', 'hidayah_register_post_types' );
