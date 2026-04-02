<?php
/**
 * Archive Template for Probondho (Articles)
 *
 * @package Hidayah
 */
get_header();

$args = array(
    'post_type'      => 'probondho',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Search
if ( ! empty( $_GET['s'] ) ) {
    $args['s'] = sanitize_text_field( $_GET['s'] );
}

// Category
if ( ! empty( $_GET['probondho_cat'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'probondho_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['probondho_cat'] ),
        ),
    );
}

// Sorting
if ( ! empty( $_GET['orderby'] ) ) {
    if ( $_GET['orderby'] === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
        $args['orderby']  = 'meta_value_num';
    } else {
        $args['orderby'] = sanitize_text_field( $_GET['orderby'] );
    }
}

$art_query = new WP_Query( $args );
$cats = get_terms( array( 'taxonomy' => 'probondho_cat' ) );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Articles & Writings', 'hidayah' ); ?></h2>
        <p><?php _e( 'Articles and analytical compositions written by Haqqani Ulama on various Islamic topics.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'All Articles', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" id="probondhoSearchForm" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="probondhoSearchInput" placeholder="<?php _e( 'Search articles...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="probondho" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="probondhoSortSelect">
                                <option value="newest" <?php selected($_GET['orderby'] ?? '', 'date'); ?>><?php _e( 'Newest First', 'hidayah' ); ?></option>
                                <option value="popular" <?php selected($_GET['orderby'] ?? '', 'popular'); ?>><?php _e( 'Popular', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#probondhoArchiveList">
                                <button class="view-toggle-btn active" data-view="grid">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Count & Filter Chips -->
                    <div class="book-archive-topbar">
                        <div class="archive-filters-toolbar">
                        <div class="archive-count-badge" id="probondhoCountBadge">
                            <span class="material-symbols-outlined">article</span>
                            <?php printf( __( 'Total %s Articles', 'hidayah' ), $art_query->found_posts ); ?>
                        </div>
                        <div class="archive-taxonomy-filters">
                            <select id="probondhoCatFilter">
                                <option value=""><?php _e( 'By Topic', 'hidayah' ); ?></option>
                                <?php foreach ( $cats as $ct ) : ?>
                                    <option value="<?php echo esc_attr( $ct->term_id ); ?>"><?php echo esc_html( $ct->name ); ?> (<?php echo $ct->count; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
                    </div>

                    <!-- Probondho Cards -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="probondhoLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="probondho-list" id="probondhoArchiveList">
                        <?php if ( $art_query->have_posts() ) : while ( $art_query->have_posts() ) : $art_query->the_post(); 
                            $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '5';
                            $cats = get_the_terms( get_the_ID(), 'probondho_cat' );
                            $cat_name = !empty($cats) ? $cats[0]->name : __( 'General', 'hidayah' );
                        ?>
                            <article class="probondho-card">
                                <div class="probondho-card-img">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/article-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                                    <?php endif; ?>
                                </div>
                                <div class="probondho-card-content">
                                    <span class="probondho-cat-badge"><?php echo esc_html($cat_name); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="probondho-meta-strip">
                                        <span>
                                            <span class="material-symbols-outlined">person</span>
                                            <?php the_author(); ?>
                                        </span>
                                        <span>
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <span>
                                            <span class="material-symbols-outlined">schedule</span>
                                            <?php printf( __( '%s Min Read', 'hidayah' ), $read_time ); ?>
                                        </span>
                                    </div>
                                    <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                                    <a class="probondho-read-more" href="<?php the_permalink(); ?>">
                                        <?php _e( 'Read Details', 'hidayah' ); ?>
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-full">
                                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="probondhoPagination">
                        <?php hidayah_pagination($art_query); ?>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Most Read -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            <?php _e( 'Most Read', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $most_read = new WP_Query( array(
                                'post_type'      => 'probondho',
                                'posts_per_page' => 5,
                                'meta_key'       => '_post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC'
                            ) );
                            while ( $most_read->have_posts() ) : $most_read->the_post();
                                $v_count = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">article</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php printf( __( '%s Readers', 'hidayah' ), number_format_i18n($v_count) ); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- Tag Cloud -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">tag</span>
                            <?php _e( 'Tag Cloud', 'hidayah' ); ?>
                        </h4>
                        <div class="probondho-tag-cloud">
                            <?php
                            $tags = get_tags( array('taxonomy' => 'post_tag', 'number' => 15) );
                            foreach ( $tags as $tag ) : ?>
                                <a class="probondho-tag" href="<?php echo get_term_link($tag); ?>"><?php echo esc_html($tag->name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Submit Article CTA -->
                    <div class="sidebar-widget probondho-submit-cta">
                        <span class="material-symbols-outlined probondho-submit-icon">edit_note</span>
                        <h4 class="probondho-submit-title"><?php _e( 'Submit Article', 'hidayah' ); ?></h4>
                        <p class="probondho-submit-text"><?php _e( 'You can send your writings to us.', 'hidayah' ); ?></p>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/submit-article' ) ); ?>"><?php _e( 'Submit Writing', 'hidayah' ); ?></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
?>
