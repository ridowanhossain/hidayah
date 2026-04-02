<?php
/**
 * Custom Taxonomies
 * Registers all custom taxonomies for the Hidayah theme.
 *
 * @package Hidayah
 */

if ( ! function_exists( 'hidayah_register_taxonomies' ) ) :
    function hidayah_register_taxonomies() {

        // ── Topic — for Audio, Video, Book, Article, Q&A ─────
        register_taxonomy( 'topic', array( 'audio', 'video', 'probondho', 'dini_jiggasa', 'product' ), array(
            'labels'       => array(
                'name'          => __( 'Topics', 'hidayah' ),
                'singular_name' => __( 'Topic', 'hidayah' ),
                'search_items'  => __( 'Search Topics', 'hidayah' ),
                'all_items'     => __( 'All Topics', 'hidayah' ),
                'edit_item'     => __( 'Edit Topic', 'hidayah' ),
                'add_new_item'  => __( 'Add New Topic', 'hidayah' ),
            ),
            'hierarchical'  => true,  // Category-style
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'topic' ),
        ) );

        // ── Speaker — for Audio, Video ────────────────────────
        register_taxonomy( 'speaker', array( 'audio', 'video' ), array(
            'labels'       => array(
                'name'          => __( 'Speakers', 'hidayah' ),
                'singular_name' => __( 'Speaker', 'hidayah' ),
                'search_items'  => __( 'Search Speakers', 'hidayah' ),
                'all_items'     => __( 'All Speakers', 'hidayah' ),
                'add_new_item'  => __( 'Add New Speaker', 'hidayah' ),
            ),
            'hierarchical'  => false, // Tag-style
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'speaker' ),
        ) );

        // ── Author Taxonomy — for Books, Articles ─────────────
        register_taxonomy( 'book_author', array( 'probondho', 'product' ), array(
            'labels'       => array(
                'name'          => __( 'Authors', 'hidayah' ),
                'singular_name' => __( 'Author', 'hidayah' ),
                'all_items'     => __( 'All Authors', 'hidayah' ),
                'add_new_item'  => __( 'Add New Author', 'hidayah' ),
            ),
            'hierarchical'  => false,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'author' ),
        ) );

        // ── Publication Year — for Monthly Hidayah ────────────
        register_taxonomy( 'pub_year', array( 'monthly_magazine' ), array(
            'labels'       => array(
                'name'          => __( 'Magazine Years', 'hidayah' ),
                'singular_name' => __( 'Year', 'hidayah' ),
                'all_items'     => __( 'All Years', 'hidayah' ),
                'add_new_item'  => __( 'Add New Year', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'pub-year' ),
        ) );

        // ── Issue Year — for Monthly Hidayah (UI filters) ──────
        register_taxonomy( 'issue_year', array( 'monthly_magazine' ), array(
            'labels'       => array(
                'name'          => __( 'Magazine Years', 'hidayah' ),
                'singular_name' => __( 'Magazine Year', 'hidayah' ),
                'all_items'     => __( 'All Years', 'hidayah' ),
                'add_new_item'  => __( 'Add New Year', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'issue-year' ),
        ) );

        // ── Issue Category — for Monthly Hidayah ───────────────
        register_taxonomy( 'issue_category', array( 'monthly_magazine' ), array(
            'labels'       => array(
                'name'          => __( 'Magazine Categories', 'hidayah' ),
                'singular_name' => __( 'Magazine Category', 'hidayah' ),
                'all_items'     => __( 'All Categories', 'hidayah' ),
                'add_new_item'  => __( 'Add New Category', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'issue-category' ),
        ) );

        // ── Genre — for Books ─────────────────────────────────
        register_taxonomy( 'genre', array( 'product' ), array(
            'labels'       => array(
                'name'          => __( 'Genres', 'hidayah' ),
                'singular_name' => __( 'Genre', 'hidayah' ),
                'all_items'     => __( 'All Genres', 'hidayah' ),
                'add_new_item'  => __( 'Add New Genre', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'genre' ),
        ) );

        // ── Notice Category — for Notices ─────────────────────
        register_taxonomy( 'notice_category', array( 'notice' ), array(
            'labels'       => array(
                'name'          => __( 'Notice Categories', 'hidayah' ),
                'singular_name' => __( 'Notice Category', 'hidayah' ),
                'all_items'     => __( 'All Categories', 'hidayah' ),
                'add_new_item'  => __( 'Add New Category', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'notice-category' ),
        ) );

        // ── Probondho Category — for Articles ─────────────────
        register_taxonomy( 'probondho_cat', array( 'probondho' ), array(
            'labels'       => array(
                'name'          => __( 'Article Categories', 'hidayah' ),
                'singular_name' => __( 'Article Category', 'hidayah' ),
                'all_items'     => __( 'All Categories', 'hidayah' ),
                'add_new_item'  => __( 'Add New Category', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'probondho-category' ),
        ) );

        // ── Gallery Category — for Photo Gallery ──────────────
        register_taxonomy( 'gallery_cat', array( 'photo_gallery' ), array(
            'labels'       => array(
                'name'          => __( 'Gallery Categories', 'hidayah' ),
                'singular_name' => __( 'Gallery Category', 'hidayah' ),
                'all_items'     => __( 'All Categories', 'hidayah' ),
                'add_new_item'  => __( 'Add New Category', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'gallery-category' ),
        ) );

        // ── Gallery Year — for Photo Gallery ──────────────────
        register_taxonomy( 'gallery_year', array( 'photo_gallery' ), array(
            'labels'       => array(
                'name'          => __( 'Gallery Years', 'hidayah' ),
                'singular_name' => __( 'Gallery Year', 'hidayah' ),
                'all_items'     => __( 'All Years', 'hidayah' ),
                'add_new_item'  => __( 'Add New Year', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'gallery-year' ),
        ) );

        // ── Dini Jiggasa Category — for Q&A ───────────────────
        register_taxonomy( 'dini_jiggasa_cat', array( 'dini_jiggasa' ), array(
            'labels'       => array(
                'name'          => __( 'Jiggasa Categories', 'hidayah' ),
                'singular_name' => __( 'Jiggasa Category', 'hidayah' ),
                'all_items'     => __( 'All Categories', 'hidayah' ),
                'add_new_item'  => __( 'Add New Category', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'jiggasa-category' ),
        ) );

    }
endif;

add_action( 'init', 'hidayah_register_taxonomies' );
