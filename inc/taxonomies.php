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
        register_taxonomy( 'topic', array( 'audio', 'video', 'book', 'probondho', 'dini_jiggasa' ), array(
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
        register_taxonomy( 'book_author', array( 'book', 'probondho' ), array(
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
        register_taxonomy( 'pub_year', array( 'monthly_hd' ), array(
            'labels'       => array(
                'name'          => __( 'Publication Years', 'hidayah' ),
                'singular_name' => __( 'Year', 'hidayah' ),
                'all_items'     => __( 'All Years', 'hidayah' ),
                'add_new_item'  => __( 'Add New Year', 'hidayah' ),
            ),
            'hierarchical'  => true,
            'public'        => true,
            'show_in_rest'  => true,
            'rewrite'       => array( 'slug' => 'pub-year' ),
        ) );

    }
endif;

add_action( 'init', 'hidayah_register_taxonomies' );
