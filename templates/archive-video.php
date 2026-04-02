<?php
/**
 * Archive Template for Video
 *
 * @package Hidayah
 */
get_header();

// Get search and sort parameters
$s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
$order = ($orderby === 'oldest') ? 'ASC' : 'DESC';
$orderby_final = ($orderby === 'popular' || $orderby === 'most_viewed') ? 'meta_value_num' : 'date';

$args = array(
    'post_type'      => 'video',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    's'              => $s,
    'orderby'        => $orderby_final,
    'order'          => $order,
);

if ($orderby === 'popular' || $orderby === 'most_viewed') {
    $args['meta_key'] = '_post_views_count';
}

$video_query = new WP_Query( $args );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Video Waz & Lectures', 'hidayah' ); ?></h2>
        <p><?php _e( 'Complete video archive of Waz, lectures, and Mahfils by Haqqani Ulama. Watch, learn, and implement.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'Waz & Lectures', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- Left Column -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Search & Sort Bar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" id="videoSearchForm" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="videoSearchInput" placeholder="<?php _e( 'Search videos...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="video" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="videoSortSelect">
                                <option value="newest" <?php selected($orderby, 'newest'); ?>><?php _e( 'Newest First', 'hidayah' ); ?></option>
                                <option value="oldest" <?php selected($orderby, 'oldest'); ?>><?php _e( 'Oldest First', 'hidayah' ); ?></option>
                                <option value="popular" <?php selected($orderby, 'popular'); ?>><?php _e( 'Popular', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#archiveVideoGrid">
                                <button class="view-toggle-btn active" data-view="grid" title="Grid View">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="List View">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Video Count Badge -->
                    <div class="archive-filters-toolbar">
                        <div class="archive-count-badge" id="videoCountBadge">
                            <span class="material-symbols-outlined">videocam</span>
                            <?php printf( __( 'Total %s Videos', 'hidayah' ), $video_query->found_posts ); ?>
                        </div>
                        <div class="archive-taxonomy-filters">
                            <select id="videoTopicFilter">
                                <option value=""><?php _e( 'By Topic', 'hidayah' ); ?></option>
                                <?php foreach ( get_terms( array( 'taxonomy' => 'topic' ) ) as $t ) : ?>
                                    <option value="<?php echo esc_attr( $t->term_id ); ?>"><?php echo esc_html( $t->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="videoSpeakerFilter">
                                <option value=""><?php _e( 'By Speaker', 'hidayah' ); ?></option>
                                <?php foreach ( get_terms( array( 'taxonomy' => 'speaker' ) ) as $sp ) : ?>
                                    <option value="<?php echo esc_attr( $sp->term_id ); ?>"><?php echo esc_html( $sp->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Video Card Grid -->
                    <div style="position: relative; min-height: 200px;">
                        <div id="videoLoader" class="archive-ajax-loader" style="display: none;">
                            <span class="material-symbols-outlined rotating">progress_activity</span>
                        </div>
                        <div class="archive-video-grid" id="videoArchiveGrid">
                        <?php if ( $video_query->have_posts() ) : while ( $video_query->have_posts() ) : $video_query->the_post(); 
                            $video_id  = get_post_meta( get_the_ID(), '_youtube_video_id', true );
                            $duration  = get_post_meta( get_the_ID(), '_video_duration', true );
                            $views     = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            $speakers  = get_the_terms( get_the_ID(), 'speaker' );
                            $topics    = get_the_terms( get_the_ID(), 'topic' );
                            $is_featured = get_post_meta( get_the_ID(), '_featured_video', true );
                            $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : "https://img.youtube.com/vi/{$video_id}/mqdefault.jpg";
                        ?>
                            <div class="archive-video-card <?php echo ($is_featured) ? 'special-highlight' : ''; ?>">
                                <div class="archive-video-thumb-wrap">
                                    <div class="sidebar-thumb-wrapper video-thumb" data-video-id="<?php echo esc_attr($video_id); ?>">
                                        <img alt="<?php the_title_attribute(); ?>" src="<?php echo esc_url($thumb_url); ?>" />
                                        <div class="play-overlay">
                                            <span class="material-symbols-outlined play-icon-lg">play_circle</span>
                                        </div>
                                        <?php if ($duration) : ?>
                                            <span class="video-duration-badge"><?php echo $duration; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="archive-video-card-info">
                                    <div class="flex-item-center mb-8">
                                        <?php if ($is_featured) : ?>
                                            <div class="video-badge">
                                                <span class="material-symbols-outlined" style="font-size: 14px;">star</span>
                                                <?php _e( 'Special Lecture', 'hidayah' ); ?>
                                            </div>
                                        <?php elseif ( ! empty($topics) ) : ?>
                                            <div class="video-badge" style="background: rgba(6, 95, 70, 0.1); color: var(--primary-green-dark);">
                                                <span class="material-symbols-outlined" style="font-size: 14px;">category</span>
                                                <?php echo esc_html($topics[0]->name); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <?php if ( ! empty($speakers) ) : ?>
                                        <p class="archive-video-speaker">
                                            <span class="material-symbols-outlined">person</span>
                                            <a href="<?php echo get_term_link($speakers[0]); ?>"><?php echo esc_html($speakers[0]->name); ?></a>
                                        </p>
                                    <?php endif; ?>
                                    <div class="archive-video-meta">
                                        <span>
                                            <span class="material-symbols-outlined">visibility</span>
                                            <?php printf( __( '%s Views', 'hidayah' ), number_format_i18n( $views ) ); ?>
                                        </span>
                                        <span>
                                            <span class="material-symbols-outlined">calendar_today</span>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-full">
                                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="videoPagination">
                        <?php hidayah_pagination( $video_query ); ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">search</span>
                            <?php _e( 'Search', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-search-box">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <input placeholder="<?php _e( 'Search videos...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="video" />
                                <button type="submit">
                                    <span class="material-symbols-outlined">search</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Speaker Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">person</span>
                            <?php _e( 'By Speaker', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $speakers = get_terms( array('taxonomy' => 'speaker', 'number' => 10) );
                            foreach ( $speakers as $speaker ) : ?>
                                <li>
                                    <a href="<?php echo get_term_link($speaker); ?>">
                                        <span><?php echo esc_html($speaker->name); ?></span>
                                        <span class="filter-count"><?php echo $speaker->count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Topic Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'By Topic', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-tags">
                            <?php
                            $topics = get_terms( array('taxonomy' => 'topic', 'number' => 20) );
                            foreach ( $topics as $topic ) : ?>
                                <a class="sidebar-tag" href="<?php echo get_term_link($topic); ?>"><?php echo esc_html($topic->name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Popular Videos (Top 5) -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            <?php _e( 'Most Viewed Videos', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-popular-list">
                            <?php
                            $popular = new WP_Query( array(
                                'post_type'      => 'video',
                                'posts_per_page' => 5,
                                'meta_key'       => '_post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC'
                            ) );
                            $rank = 1;
                            while ( $popular->have_posts() ) : $popular->the_post(); 
                                $p_views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="popular-rank"><?php echo $rank++; ?></span>
                                        <div class="popular-info">
                                            <h5><?php the_title(); ?></h5>
                                            <span><?php printf( __( '%s Views', 'hidayah' ), number_format_i18n( $p_views ) ); ?></span>
                                        </div>
                                        <span class="material-symbols-outlined popular-play">play_circle</span>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>

                    <!-- YouTube Card -->
                    <div class="sidebar-widget sidebar-youtube-card">
                        <div class="youtube-card-icon">
                            <span class="material-symbols-outlined">play_circle</span>
                        </div>
                        <h4><?php _e( 'YouTube Channel', 'hidayah' ); ?></h4>
                        <p><?php echo h_opt('youtube_desc', 'Subscribe to our official YouTube channel and get new videos first.'); ?></p>
                        <div class="youtube-subscriber-count">
                            <span class="material-symbols-outlined">group</span>
                            <strong><?php echo h_opt('youtube_subs', '50,000+'); ?></strong>
                            <?php _e( 'Subscribers', 'hidayah' ); ?>
                        </div>
                        <a class="youtube-subscribe-btn" href="<?php echo esc_url(h_opt('social_youtube', '#')); ?>" rel="noopener" target="_blank">
                            <span class="material-symbols-outlined">play_circle</span>
                            <?php _e( 'Subscribe Now', 'hidayah' ); ?>
                        </a>
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
