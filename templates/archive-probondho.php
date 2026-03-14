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
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'প্রবন্ধ ও লেখনি', 'hidayah' ); ?></h2>
        <p><?php _e( 'ইসলামের বিভিন্ন বিষয়ে হক্কানী আলেমদের লেখা প্রবন্ধ ও বিশ্লেষণধর্মী রচনা।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'সকল প্রবন্ধ', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" placeholder="<?php _e( 'প্রবন্ধ খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="probondho" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" onchange="window.location.href=this.value">
                                <option value="<?php echo add_query_arg('orderby', 'date'); ?>" <?php selected($_GET['orderby'] ?? '', 'date'); ?>><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="<?php echo add_query_arg('orderby', 'popular'); ?>" <?php selected($_GET['orderby'] ?? '', 'popular'); ?>><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
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
                        <div class="archive-count-badge">
                            <span class="material-symbols-outlined">article</span>
                            <?php printf( __( 'মোট %sটি প্রবন্ধ', 'hidayah' ), hidayah_en_to_bn_number( $art_query->found_posts ) ); ?>
                        </div>
                        <div class="active-filter-chips">
                            <?php if ( ! empty( $_GET['probondho_cat'] ) ) : 
                                $term = get_term_by('slug', $_GET['probondho_cat'], 'probondho_cat');
                            ?>
                                <span class="filter-chip">
                                    <?php echo esc_html($term->name); ?>
                                    <a href="<?php echo remove_query_arg('probondho_cat'); ?>" class="filter-chip-remove">
                                        <span class="material-symbols-outlined">close</span>
                                    </a>
                                </span>
                            <?php endif; ?>

                            <select class="archive-sort-select topbar-filter-select" onchange="window.location.href=this.value">
                                <option value=""><?php _e( 'বিষয় অনুযায়ী', 'hidayah' ); ?></option>
                                <?php
                                $cats = get_terms( array('taxonomy' => 'probondho_cat') );
                                foreach ( $cats as $ct ) : ?>
                                    <option value="<?php echo add_query_arg('probondho_cat', $ct->slug); ?>" <?php selected($_GET['probondho_cat'] ?? '', $ct->slug); ?>><?php echo esc_html($ct->name); ?> (<?php echo hidayah_en_to_bn_number($ct->count); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Probondho Cards -->
                    <div class="probondho-list" id="probondhoArchiveList">
                        <?php if ( $art_query->have_posts() ) : while ( $art_query->have_posts() ) : $art_query->the_post(); 
                            $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '৫';
                            $cats = get_the_terms( get_the_ID(), 'probondho_cat' );
                            $cat_name = !empty($cats) ? $cats[0]->name : __( 'সাধারণ', 'hidayah' );
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
                                            <?php printf( __( '%s মিনিট', 'hidayah' ), hidayah_en_to_bn_number($read_time) ); ?>
                                        </span>
                                    </div>
                                    <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                                    <a class="probondho-read-more" href="<?php the_permalink(); ?>">
                                        <?php _e( 'বিস্তারিত পড়ুন', 'hidayah' ); ?>
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

                    <!-- Pagination -->
                    <?php hidayah_pagination($art_query); ?>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Most Read -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            <?php _e( 'সর্বাধিক পঠিত', 'hidayah' ); ?>
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
                                            <span><?php printf( __( '%s পাঠক', 'hidayah' ), hidayah_en_to_bn_number(number_format_i18n($v_count)) ); ?></span>
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
                            <?php _e( 'ট্যাগ ক্লাউড', 'hidayah' ); ?>
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
                        <h4 class="probondho-submit-title"><?php _e( 'লেখা পাঠান", "hidayah' ); ?></h4>
                        <p class="probondho-submit-text"><?php _e( 'আপনার লেখা আমাদের পাঠাতে পারেন।', 'hidayah' ); ?></p>
                        <a class="btn btn-sm" href="<?php echo esc_url( home_url( '/submit-article' ) ); ?>"><?php _e( 'লেখা পাঠান', 'hidayah' ); ?></a>
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
