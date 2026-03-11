<?php
/**
 * Archive Template for Audio
 *
 * @package Hidayah
 */
get_header();

// Get search and sort parameters
$s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
$order = ($orderby === 'oldest') ? 'ASC' : 'DESC';
$orderby_final = ($orderby === 'popular') ? 'meta_value_num' : 'date';

$args = array(
    'post_type'      => 'audio',
    'posts_per_page' => 12,
    'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    's'              => $s,
    'orderby'        => $orderby_final,
    'order'          => $order,
);

if ($orderby === 'popular') {
    $args['meta_key'] = '_post_views_count';
}

$audio_query = new WP_Query( $args );
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'অডিও ওয়াজ ও বয়ান', 'hidayah' ); ?></h2>
        <p><?php _e( 'হক্বানী আলেমদের ওয়াজ, বয়ান, তিলাওয়াত ও দ্বীনি আলোচনার সম্পূর্ণ অডিও আর্কাইভ। শুনুন, শিখুন এবং আমল করুন।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'ওয়াজ ও বয়ান', 'hidayah' ); ?></span>
        </nav>

        <div class="archive-layout">
            <!-- Left Column -->
            <div class="archive-main">
                <div class="col-inner">
                    <!-- Search & Sort Bar -->
                    <div class="archive-toolbar">
                        <div class="archive-search-bar">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" placeholder="<?php _e( 'অডিও খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="audio" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" onchange="window.location.href=this.value">
                                <option value="<?php echo add_query_arg('orderby', 'newest'); ?>" <?php selected($orderby, 'newest'); ?>><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="<?php echo add_query_arg('orderby', 'oldest'); ?>" <?php selected($orderby, 'oldest'); ?>><?php _e( 'পুরাতন প্রথমে', 'hidayah' ); ?></option>
                                <option value="<?php echo add_query_arg('orderby', 'popular'); ?>" <?php selected($orderby, 'popular'); ?>><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
                            </select>
                            <div class="archive-view-toggle" data-view-target="#archiveAudioGrid">
                                <button class="view-toggle-btn active" data-view="grid" title="গ্রিড ভিউ">
                                    <span class="material-symbols-outlined">grid_view</span>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="লিস্ট ভিউ">
                                    <span class="material-symbols-outlined">view_list</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audio Count Badge -->
                    <div class="archive-count-badge">
                        <span class="material-symbols-outlined">headphones</span>
                        <?php printf( __( 'মোট %sটি অডিও', 'hidayah' ), hidayah_en_to_bn_number( $audio_query->found_posts ) ); ?>
                    </div>

                    <!-- Audio Card Grid -->
                    <div class="archive-audio-grid" id="archiveAudioGrid">
                        <?php if ( $audio_query->have_posts() ) : while ( $audio_query->have_posts() ) : $audio_query->the_post(); 
                            $audio_url = get_post_meta( get_the_ID(), '_audio_file_url', true );
                            $duration  = get_post_meta( get_the_ID(), '_audio_duration', true );
                            $views     = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            $speakers  = get_the_terms( get_the_ID(), 'speaker' );
                            $topics    = get_the_terms( get_the_ID(), 'topic' );
                            $is_featured = get_post_meta( get_the_ID(), '_featured_audio', true );
                        ?>
                            <div class="audio-card audio-player archive-audio-card <?php echo ($is_featured) ? 'special-highlight' : ''; ?>" data-src="<?php echo esc_url($audio_url); ?>" id="audio-<?php the_ID(); ?>">
                                <div class="archive-card-top">
                                    <div class="audio-card-icon">
                                        <span class="material-symbols-outlined">
                                            <?php 
                                            // Handle different icons based on category if available
                                            echo 'mic'; 
                                            ?>
                                        </span>
                                    </div>
                                    <div class="archive-card-info">
                                        <div class="flex-item-center mb-8">
                                            <?php if ($is_featured) : ?>
                                                <div class="audio-badge" style="padding: 4px 10px; font-size: 10px; gap: 4px; background: rgba(6, 95, 70, 0.1); color: var(--primary-green-dark); border-radius: 4px;">
                                                    <span class="material-symbols-outlined" style="font-size: 12px;">star</span>
                                                    <?php _e( 'বিশেষ বয়ান', 'hidayah' ); ?>
                                                </div>
                                            <?php elseif ( ! empty( $topics ) ) : ?>
                                                <div class="audio-badge" style="padding: 4px 10px; font-size: 10px; gap: 4px; background: rgba(6, 95, 70, 0.1); color: var(--primary-green-dark); border-radius: 4px;">
                                                    <span class="material-symbols-outlined" style="font-size: 12px;">category</span>
                                                    <?php echo esc_html( $topics[0]->name ); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <?php if ( ! empty($speakers) ) : ?>
                                            <p class="audio-card-speaker">
                                                <span class="material-symbols-outlined">person</span>
                                                <a href="<?php echo get_term_link($speakers[0]); ?>"><?php echo esc_html($speakers[0]->name); ?></a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="archive-card-meta">
                                    <?php if ($duration) : ?>
                                    <span class="audio-card-duration">
                                        <span class="material-symbols-outlined">schedule</span>
                                        <?php echo hidayah_en_to_bn_number($duration); ?> <?php _e( 'মিনিট', 'hidayah' ); ?>
                                    </span>
                                    <?php endif; ?>
                                    <span class="audio-card-duration">
                                        <span class="material-symbols-outlined">headphones</span>
                                        <?php echo hidayah_en_to_bn_number(number_format_i18n($views)); ?>
                                    </span>
                                    <span class="audio-card-duration">
                                        <span class="material-symbols-outlined">calendar_today</span>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                </div>
                                <div class="player-progress">
                                    <button class="player-btn player-btn-main sidebar-player-btn-reset" title="<?php _e( 'চালান / থামান', 'hidayah' ); ?>">
                                        <span class="material-symbols-outlined">play_arrow</span>
                                    </button>
                                    <span class="player-time player-current">0:00</span>
                                    <input class="player-seek" max="0" min="0" step="1" type="range" value="0" />
                                    <span class="player-time player-duration">0:00</span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-full">
                                <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php hidayah_pagination( $audio_query ); ?>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <!-- Sidebar Search -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">search</span>
                            <?php _e( 'অনুসন্ধান', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-search-box">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <input placeholder="<?php _e( 'অডিও খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="audio" />
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
                            <?php _e( 'বক্তা অনুযায়ী', 'hidayah' ); ?>
                        </h4>
                        <ul class="sidebar-filter-list">
                            <?php
                            $speakers = get_terms( array(
                                'taxonomy' => 'speaker',
                                'number'   => 10,
                            ) );
                            if ( ! empty( $speakers ) && ! is_wp_error( $speakers ) ) :
                                foreach ( $speakers as $speaker ) : ?>
                                    <li>
                                        <a href="<?php echo get_term_link( $speaker ); ?>">
                                            <span><?php echo esc_html( $speaker->name ); ?></span>
                                            <span class="filter-count"><?php echo hidayah_en_to_bn_number($speaker->count); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </div>

                    <!-- Category Filter -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">category</span>
                            <?php _e( 'বিষয় অনুযায়ী', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-tags">
                            <?php
                            $topics = get_terms( array(
                                'taxonomy' => 'topic',
                                'number'   => 20,
                            ) );
                            if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) :
                                foreach ( $topics as $topic ) : ?>
                                    <a class="sidebar-tag" href="<?php echo get_term_link( $topic ); ?>"><?php echo esc_html( $topic->name ); ?></a>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>

                    <!-- Tag Cloud -->
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">tag</span>
                            <?php _e( 'ট্যাগসমূহ', 'hidayah' ); ?>
                        </h4>
                        <div class="sidebar-tags">
                            <?php wp_tag_cloud( array( 'smallest' => 12, 'largest' => 12, 'unit' => 'px' ) ); ?>
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
