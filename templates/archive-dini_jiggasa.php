<?php
/**
 * Archive Template for Dini Jiggasa (Q&A)
 *
 * @package Hidayah
 */
get_header();

$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'dini_jiggasa',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Filters
if ( ! empty( $_GET['s'] ) ) {
    $args['s'] = sanitize_text_field( $_GET['s'] );
}

if ( ! empty( $_GET['jiggasa_cat'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'dini_jiggasa_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['jiggasa_cat'] ),
        ),
    );
}

if ( ! empty( $_GET['status'] ) ) {
    $args['meta_query'] = array(
        array(
            'key'   => '_jiggasa_status',
            'value' => sanitize_text_field( $_GET['status'] ),
        ),
    );
}

$q_query = new WP_Query( $args );
$cats = get_terms( array( 'taxonomy' => 'dini_jiggasa_cat' ) );

global $wpdb;
$answered_count = (int) $wpdb->get_var( $wpdb->prepare(
    "SELECT COUNT(p.ID) FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id WHERE p.post_type = %s AND p.post_status = 'publish' AND pm.meta_key = '_jiggasa_status' AND pm.meta_value = 'answered'",
    'dini_jiggasa'
) );
$pending_count = (int) $wpdb->get_var( $wpdb->prepare(
    "SELECT COUNT(p.ID) FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id WHERE p.post_type = %s AND p.post_status = 'publish' AND pm.meta_key = '_jiggasa_status' AND pm.meta_value = 'pending'",
    'dini_jiggasa'
) );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'দ্বীনি জিজ্ঞাসা', 'hidayah' ); ?></h2>
        <p><?php _e( 'ইসলামী জীবনের বিভিন্ন বিষয়ে আপনার প্রশ্নের সঠিক ও দলীলভিত্তিক উত্তর।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'দ্বীনি জিজ্ঞাসা', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- LEFT -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Toolbar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form id="jiggasaSearchForm" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="jiggasaSearchInput" placeholder="<?php _e( 'প্রশ্ন খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo get_search_query(); ?>" />
                                <input type="hidden" name="post_type" value="dini_jiggasa" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="jiggasaSortSelect">
                                <option value="newest"><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="popular"><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#jiggasaList">
                                <button class="view-toggle-btn active" data-view="grid" title="গ্রিড ভিউ">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="লিস্ট ভিউ">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs/Status Filter -->
                    <div class="jiggasa-tabs jiggasa-tabs-container">
                        <button class="jiggasa-tab active" data-status=""><?php _e( 'সকল', 'hidayah' ); ?></button>
                        <button class="jiggasa-tab" data-status="answered"><?php _e( 'উত্তরিত', 'hidayah' ); ?></button>
                        <button class="jiggasa-tab" data-status="pending"><?php _e( 'অপেক্ষমাণ', 'hidayah' ); ?></button>
                    </div>

                    <!-- Topbar Info -->
                    <div class="book-archive-topbar">
                        <div class="archive-filters-toolbar">
                            <div class="archive-count-badge" id="jiggasaCountBadge">
                                <span class="material-symbols-outlined">help</span>
                                <?php printf( __( 'মোট %sটি প্রশ্ন', 'hidayah' ), hidayah_en_to_bn_number( $q_query->found_posts ) ); ?>
                            </div>
                            <div class="archive-taxonomy-filters">
                                <select id="jiggasaCatFilter">
                                    <option value=""><?php _e( 'বিষয় অনুযায়ী', 'hidayah' ); ?></option>
                                    <?php foreach ( $cats as $ct ) : ?>
                                        <option value="<?php echo esc_attr( $ct->term_id ); ?>"><?php echo esc_html( $ct->name ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Question Cards -->
                    <div class="jiggasa-grid-wrapper" style="position:relative; min-height:200px;">
                        <div id="jiggasaLoader" style="display:none;" class="archive-ajax-loader">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="jiggasa-list" id="jiggasaList">
                            <?php if ( $q_query->have_posts() ) : while ( $q_query->have_posts() ) : $q_query->the_post(); 
                                $status  = get_post_meta( get_the_ID(), '_jiggasa_status', true ) ?: 'answered';
                                $mufti   = get_post_meta( get_the_ID(), '_jiggasa_mufti', true );
                                $asker   = get_post_meta( get_the_ID(), '_jiggasa_asker_name', true ) ?: __( 'আনোনিমাস', 'hidayah' );
                                $cat_terms = get_the_terms( get_the_ID(), 'dini_jiggasa_cat' );
                                $cat_name = !empty($cat_terms) ? $cat_terms[0]->name : __( 'সাধারণ', 'hidayah' );
                            ?>
                                <article class="jiggasa-card <?php echo esc_attr($status); ?>">
                                <div class="jiggasa-card-header">
                                    <span class="jiggasa-cat-badge"><?php echo esc_html($cat_name); ?></span>
                                    <span class="jiggasa-status <?php echo $status; ?>-badge">
                                        <span class="material-symbols-outlined"><?php echo $status === 'answered' ? 'check_circle' : 'schedule'; ?></span>
                                        <?php echo $status === 'answered' ? __( 'উত্তরিত', 'hidayah' ) : __( 'অপেক্ষমাণ', 'hidayah' ); ?>
                                    </span>
                                </div>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="jiggasa-meta">
                                    <span><span class="material-symbols-outlined">person</span><?php echo esc_html($asker); ?></span>
                                    <span><span class="material-symbols-outlined">calendar_month</span><?php echo get_the_date(); ?></span>
                                    <?php if ( $status === 'answered' && $mufti ) : ?>
                                        <span><span class="material-symbols-outlined">support_agent</span><?php echo esc_html($mufti); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ( $status === 'answered' ) : ?>
                                    <a class="jiggasa-read-link" href="<?php the_permalink(); ?>">
                                        <?php _e( 'উত্তর পড়ুন', 'hidayah' ); ?>
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                    </a>
                                <?php endif; ?>
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
                    <div id="jiggasaPagination">
                        <?php hidayah_pagination($q_query); ?>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Most Asked -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            <?php _e( 'সর্বাধিক জিজ্ঞাসিত', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-recent-list">
                            <?php
                            $most_asked = new WP_Query( array(
                                'post_type'      => 'dini_jiggasa',
                                'posts_per_page' => 5,
                                'meta_key'       => '_post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC'
                            ) );
                            while ( $most_asked->have_posts() ) : $most_asked->the_post();
                                $v_count = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="material-symbols-outlined recent-icon">help</span>
                                        <div class="recent-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php printf( __( '%s বার পঠিত', 'hidayah' ), hidayah_en_to_bn_number($v_count) ); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- Stats Card -->
                    <div class="sidebar-widget jiggasa-stats-card">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">bar_chart</span>
                            <?php _e( 'পরিসংখ্যান', 'hidayah' ); ?>
                        </h4>
                        <div class="jiggasa-stats-grid">
                            <?php
                            $total_q = wp_count_posts( 'dini_jiggasa' )->publish;
                            $answered_q = $answered_count;
                            $pending_q = $pending_count;
                            ?>
                            <div class="jiggasa-stat-item">
                                <span class="jiggasa-stat-num"><?php echo hidayah_en_to_bn_number($total_q); ?></span>
                                <span class="jiggasa-stat-label"><?php _e( 'মোট প্রশ্ন', 'hidayah' ); ?></span>
                            </div>
                            <div class="jiggasa-stat-item">
                                <span class="jiggasa-stat-num answered-num"><?php echo hidayah_en_to_bn_number($answered_q); ?></span>
                                <span class="jiggasa-stat-label"><?php _e( 'উত্তরিত', 'hidayah' ); ?></span>
                            </div>
                            <div class="jiggasa-stat-item">
                                <span class="jiggasa-stat-num pending-num"><?php echo hidayah_en_to_bn_number($pending_q); ?></span>
                                <span class="jiggasa-stat-label"><?php _e( 'অপেক্ষমাণ', 'hidayah' ); ?></span>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        </div>
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
