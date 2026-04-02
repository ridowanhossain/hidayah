<?php

/**
 * Main Template (index.php)
 * Handles the display for the front page and fallback for other pages.
 *
 * @package Hidayah
 */
get_header();

// ── Check if we are on the Front Page ─────────────────
if (is_front_page() || is_home()) :
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2><?php echo h_opt('hero_title'); ?></h2>
            <p><?php echo h_opt('hero_subtitle'); ?></p>
        </div>
    </section>

    <!-- Notice Section -->
    <section class="notice-section">
        <div class="notice-marquee-container">
            <div class="notice-marquee">
                <div class="notice-track">
                    <?php
                    $notices = new WP_Query(array(
                        'post_type'      => 'notice',
                        'posts_per_page' => 10,
                    ));
                    if ($notices->have_posts()) :
                        while ($notices->have_posts()) : $notices->the_post(); ?>
                            <span class="notice-item"><a href="<?php the_permalink(); ?>">📢 <?php the_title(); ?></a></span>
                            <span class="notice-separator">•</span>
                        <?php endwhile;
                    else : ?>
                        <span class="notice-item"><?php _e('No announcements available.', 'hidayah'); ?></span>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>
                <!-- Duplicate for infinite marquee effect -->
                <div class="notice-track" aria-hidden="true">
                    <?php
                    if ($notices->have_posts()) :
                        while ($notices->have_posts()) : $notices->the_post(); ?>
                            <span class="notice-item"><a href="<?php the_permalink(); ?>">📢 <?php the_title(); ?></a></span>
                            <span class="notice-separator">•</span>
                    <?php endwhile;
                    endif;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    $show_audio = h_opt('show_audio_section', true);
    $show_video = h_opt('show_video_section', true);
    if ($show_audio || $show_video) :
    ?>
        <!-- MEDIA TABS SECTION -->
        <div class="unified-media-section">
            <div class="media-tabs-wrapper">
                <!-- Tab Buttons -->
                <?php if ($show_audio && $show_video) : ?>
                    <div class="media-tab-buttons">
                        <button class="media-tab-btn active" data-tab="audio">
                            <span class="material-symbols-outlined">headphones</span> <?php _e('Audio', 'hidayah'); ?>
                        </button>
                        <button class="media-tab-btn" data-tab="video">
                            <span class="material-symbols-outlined">videocam</span> <?php _e('Video', 'hidayah'); ?>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- AUDIO TAB CONTENT -->
                <?php if ($show_audio) : ?>
                    <div class="media-tab-content active" id="tab-audio">
                        <div class="audio-section-inner">
                            <div class="mahfil-audio-layout">
                                <!-- Main Audio Content -->
                                <div class="audio-main-content">
                                    <?php
                                    $feat_audio = new WP_Query(array(
                                        'post_type'      => 'audio',
                                        'posts_per_page' => 1,
                                        'meta_key'       => '_is_featured',
                                        'meta_value'     => 'yes'
                                    ));
                                    if (! $feat_audio->have_posts()) {
                                        $feat_audio = new WP_Query(array('post_type' => 'audio', 'posts_per_page' => 1));
                                    }

                                    if ($feat_audio->have_posts()) : $feat_audio->the_post();
                                        $audio_url   = get_post_meta(get_the_ID(), '_audio_url',      true);
                                        $youtube_url = get_post_meta(get_the_ID(), '_youtube_url',    true);
                                        $v_count     = get_post_meta(get_the_ID(), '_post_views_count', true) ?: 0;
                                        $duration    = get_post_meta(get_the_ID(), '_audio_duration',  true) ?: '0:00';
                                        $location    = get_post_meta(get_the_ID(), '_mahfil_location', true);
                                        // Extract YouTube video ID
                                        $feat_yt_id  = '';
                                        if ($youtube_url && ! $audio_url) {
                                            preg_match('/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $yt_m);
                                            $feat_yt_id = $yt_m[1] ?? '';
                                        }
                                    ?>
                                        <div class="featured-audio-card">
                                            <div class="featured-audio-cover">
                                                <span class="material-symbols-outlined">graphic_eq</span>
                                            </div>
                                            <div class="featured-audio-info">
                                                <div class="title-badge-wrapper">
                                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <div class="audio-badge">
                                                        <span class="material-symbols-outlined">star</span>
                                                        <?php _e('Latest Speech', 'hidayah'); ?>
                                                    </div>
                                                </div>
                                                <?php if ($feat_yt_id) : ?>
                                                    <!-- YouTube Audio Mode (Featured) -->
                                                    <div class="audio-player yt-audio-mode" id="featured-audio-player" data-ytid="<?php echo esc_attr($feat_yt_id); ?>">
                                                        <div class="player-controls">
                                                            <button class="player-btn player-btn-backward" title="<?php _e('10 seconds backward', 'hidayah'); ?>"><span class="material-symbols-outlined">replay_10</span></button>
                                                            <button class="player-btn player-btn-main yt-play-btn" title="<?php _e('Play / Pause', 'hidayah'); ?>"><span class="material-symbols-outlined">play_arrow</span></button>
                                                            <button class="player-btn player-btn-forward" title="<?php _e('10 seconds forward', 'hidayah'); ?>"><span class="material-symbols-outlined">forward_10</span></button>
                                                        </div>
                                                        <div class="player-progress">
                                                            <span class="player-time player-current">0:00</span>
                                                            <input type="range" class="player-seek" value="0" min="0" max="0" step="1" />
                                                            <span class="player-time player-duration"><?php echo esc_html($duration); ?></span>
                                                        </div>
                                                        <!-- Hidden YouTube iframe -->
                                                        <div style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;">
                                                            <div id="yt-feat-iframe"></div>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <!-- Standard MP3 Player -->
                                                    <div class="audio-player" id="featured-audio-player" data-src="<?php echo esc_url($audio_url); ?>">
                                                        <div class="player-controls">
                                                            <button class="player-btn player-btn-backward" title="<?php _e('10 seconds backward', 'hidayah'); ?>"><span class="material-symbols-outlined">replay_10</span></button>
                                                            <button class="player-btn player-btn-main" title="<?php _e('Play / Pause', 'hidayah'); ?>"><span class="material-symbols-outlined">play_arrow</span></button>
                                                            <button class="player-btn player-btn-forward" title="<?php _e('10 seconds forward', 'hidayah'); ?>"><span class="material-symbols-outlined">forward_10</span></button>
                                                        </div>
                                                        <div class="player-progress">
                                                            <span class="player-time player-current">0:00</span>
                                                            <input type="range" class="player-seek" value="0" min="0" max="0" step="1" />
                                                            <span class="player-time player-duration"><?php echo esc_html($duration); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="video-info-bar">
                                            <div class="video-meta-left">
                                                <div class="video-status">
                                                    <span class="live-badge-sm"><span class="live-dot live-pulse"></span> LIVE</span>
                                                    <h3 class="video-status-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <div class="audio-status-details">
                                                        <span><span class="material-symbols-outlined">schedule</span> <?php echo esc_html($duration); ?></span>
                                                        <span><span class="material-symbols-outlined">headphones</span> <?php echo esc_html($v_count); ?> <?php _e('Listeners', 'hidayah'); ?></span>
                                                        <span><span class="material-symbols-outlined">calendar_today</span> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'hidayah'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="video-meta-details">
                                                    <?php if ($location) : ?>
                                                        <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">location_on</span> <?php echo esc_html($location); ?></span>
                                                    <?php endif; ?>
                                                    <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">calendar_today</span> <?php echo h_bn_num(get_the_date()); ?></span>
                                                    <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">person</span> <?php the_author(); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif;
                                    wp_reset_postdata(); ?>
                                </div>

                                <!-- Sidebar Audio Players -->
                                <div class="audio-sidebar-list">
                                    <?php
                                    $side_audio = new WP_Query(array('post_type' => 'audio', 'posts_per_page' => 2, 'offset' => 1));
                                    $side_yt_ids = [];
                                    while ($side_audio->have_posts()) : $side_audio->the_post();
                                        $s_url     = get_post_meta(get_the_ID(), '_audio_url',     true);
                                        $s_yt_url  = get_post_meta(get_the_ID(), '_youtube_url',   true);
                                        $s_yt_id   = '';
                                        if ($s_yt_url && ! $s_url) {
                                            preg_match('/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $s_yt_url, $sy_m);
                                            $s_yt_id = $sy_m[1] ?? '';
                                        }
                                        $side_yt_ids[] = $s_yt_id;
                                    ?>
                                        <?php if ($s_yt_id) : ?>
                                            <!-- YouTube sidebar card -->
                                            <div class="audio-card yt-audio-mode" data-ytid="<?php echo esc_attr($s_yt_id); ?>" id="side-yt-<?php echo esc_attr($s_yt_id); ?>">
                                            <?php else : ?>
                                                <div class="audio-card audio-player" data-src="<?php echo esc_url($s_url); ?>">
                                                <?php endif; ?>
                                                <h4><a href="<?php the_permalink(); ?>"><span class="material-symbols-outlined audio-title-icon">mic</span><?php the_title(); ?></a></h4>
                                                <div class="player-progress">
                                                    <button class="player-btn player-btn-main <?php echo $s_yt_id ? 'yt-play-btn' : 'sidebar-player-btn-reset'; ?>"><span class="material-symbols-outlined">play_arrow</span></button>
                                                    <span class="player-time player-current">0:00</span>
                                                    <input type="range" class="player-seek" value="0" min="0" max="0" step="1" />
                                                    <span class="player-time player-duration">0:00</span>
                                                </div>
                                                <?php if ($s_yt_id) : ?>
                                                    <div style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;">
                                                        <div id="yt-side-<?php echo esc_attr($s_yt_id); ?>"></div>
                                                    </div>
                                                <?php endif; ?>
                                                </div>
                                            <?php endwhile;
                                        wp_reset_postdata(); ?>
                                            <a href="<?php echo get_post_type_archive_link('audio'); ?>" class="sidebar-view-more-btn"><?php _e('Listen to more audio', 'hidayah'); ?> <span class="material-symbols-outlined">arrow_forward</span></a>
                                            </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- VIDEO TAB CONTENT -->
                    <?php if ($show_video) : ?>
                        <div class="media-tab-content <?php echo (! $show_audio) ? 'active' : ''; ?>" id="tab-video">
                            <div class="mahfil-section-full">
                                <main class="mahfil-main-container">
                                    <div class="mahfil-video-layout">
                                        <!-- Main Video -->
                                        <div class="mahfil-main-video">
                                            <?php
                                            $feat_video = new WP_Query(array(
                                                'post_type'      => 'video',
                                                'posts_per_page' => 1,
                                                'meta_key'       => '_is_featured',
                                                'meta_value'     => 'yes'
                                            ));
                                            if (! $feat_video->have_posts()) {
                                                $feat_video = new WP_Query(array('post_type' => 'video', 'posts_per_page' => 1));
                                            }

                                            if ($feat_video->have_posts()) : $feat_video->the_post();
                                                $video_id = get_post_meta(get_the_ID(), '_youtube_video_id', true);
                                                $v_loc    = get_post_meta(get_the_ID(), '_mahfil_location', true);
                                            ?>
                                                <div class="live-video-wrapper video-inplace" data-video-id="<?php echo esc_attr($video_id); ?>">
                                                    <div class="live-video-cover">
                                                        <?php the_post_thumbnail('large', array('class' => 'live-video-thumb')); ?>
                                                        <div class="live-video-overlay"></div>
                                                    </div>
                                                    <div class="live-play-btn-overlay"><button class="live-play-btn"><span class="material-symbols-outlined live-icon-filled">play_arrow</span></button></div>
                                                </div>

                                                <div class="video-info-bar">
                                                    <div class="video-meta-left">
                                                        <div class="video-status">
                                                            <span class="live-badge-sm"><span class="live-dot live-pulse"></span> LIVE</span>
                                                            <div class="title-badge-wrapper mb-0">
                                                                <h3 class="video-status-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                                <div class="video-badge"><span class="material-symbols-outlined">star</span> <?php _e('Special Speech', 'hidayah'); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="video-meta-details">
                                                            <?php if ($v_loc) : ?>
                                                                <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">location_on</span> <?php echo esc_html($v_loc); ?></span>
                                                            <?php endif; ?>
                                                            <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">calendar_today</span> <?php echo h_bn_num(get_the_date()); ?></span>
                                                            <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">person</span> <?php the_author(); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif;
                                            wp_reset_postdata(); ?>
                                        </div>

                                        <!-- Sidebar Videos -->
                                        <div class="mahfil-sidebar-videos">
                                            <h4 class="sidebar-heading"><?php _e('Other Videos', 'hidayah'); ?></h4>
                                            <?php
                                            $side_video = new WP_Query(array('post_type' => 'video', 'posts_per_page' => 4, 'offset' => 1));
                                            while ($side_video->have_posts()) : $side_video->the_post();
                                                $sv_id = get_post_meta(get_the_ID(), '_youtube_video_id', true);
                                            ?>
                                                <div class="sidebar-video-card">
                                                    <div class="sidebar-thumb-wrapper video-thumb" data-video-id="<?php echo esc_attr($sv_id); ?>">
                                                        <?php the_post_thumbnail('medium'); ?>
                                                        <div class="play-overlay"><span class="material-symbols-outlined play-icon-lg">play_circle</span></div>
                                                    </div>
                                                    <div class="sidebar-card-content">
                                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                        <div class="sidebar-meta"><span><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'hidayah'); ?></span></div>
                                                    </div>
                                                </div>
                                            <?php endwhile;
                                            wp_reset_postdata(); ?>
                                            <a href="<?php echo get_post_type_archive_link('video'); ?>" class="sidebar-view-more-btn"><?php _e('Watch more videos', 'hidayah'); ?> <span class="material-symbols-outlined">arrow_forward</span></a>
                                        </div>
                                    </div>
                                </main>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>

                    <!-- Schedule Section -->
                    <div class="mahfil-schedule-section">
                        <div class="schedule-list">
                            <?php
                            $schedules = new WP_Query(array(
                                'post_type'      => 'notice',
                                'posts_per_page' => 3,
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'notice_cat',
                                        'field'    => 'slug',
                                        'terms'    => 'schedule',
                                    ),
                                ),
                            ));
                            if ($schedules->have_posts()) :
                                while ($schedules->have_posts()) : $schedules->the_post();
                                    $s_date = get_post_meta(get_the_ID(), '_schedule_date', true); // Y-m-d
                                    $s_time = get_post_meta(get_the_ID(), '_schedule_time', true);
                                    $s_loc  = get_post_meta(get_the_ID(), '_schedule_location', true);

                                    $day   = date_i18n('d', strtotime($s_date));
                                    $month = date_i18n('F, Y', strtotime($s_date));
                                    $day_n = date_i18n('l', strtotime($s_date));
                            ?>
                                    <div class="schedule-card">
                                        <div class="schedule-date">
                                            <span class="date-day"><?php echo esc_html($day); ?></span>
                                            <span class="date-month"><?php echo esc_html($month); ?></span>
                                        </div>
                                        <div class="schedule-info">
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_author(); ?></a></h4>
                                            <p class="schedule-location"><span class="material-symbols-outlined schedule-location-icon">location_on</span> <?php echo esc_html($s_loc); ?></p>
                                        </div>
                                        <div class="schedule-time">
                                            <span class="time-text"><?php echo esc_html($s_time); ?></span>
                                            <span class="day-text"><?php echo esc_html($day_n); ?></span>
                                            <button class="reminder-btn"><?php _e('Set Reminder', 'hidayah'); ?></button>
                                        </div>
                                    </div>
                            <?php endwhile;
                                wp_reset_postdata();
                            endif; ?>
                        </div>
                    </div>
            </div>
        <?php endif; ?>

        <?php if (h_opt('show_book_section', true)) : ?>
            <!-- Book Sales Section -->
            <section class="book-sales-section">
                <div class="container">
                    <div class="section-title text-center"><?php echo h_opt('book_sales_title'); ?></div>
                    <?php if ($book_subtitle = h_opt('book_sales_subtitle')) : ?>
                        <p class="section-subtitle text-center" style="max-width: 800px; margin: -15px auto 30px; opacity: 0.8; font-size: 16px;"><?php echo esc_html($book_subtitle); ?></p>
                    <?php endif; ?>
                    <div class="book-slider-wrapper">
                        <button class="book-slider-btn book-slider-prev"><span class="material-symbols-outlined">chevron_left</span></button>
                        <div class="book-sales-grid">
                            <?php
                            $books = new WP_Query(array('post_type' => 'product', 'posts_per_page' => 8));
                            while ($books->have_posts()) : $books->the_post();
                                $product   = wc_get_product(get_the_ID());
                                if (!$product) continue;

                                $price         = $product->get_price();
                                $regular_price = $product->get_regular_price();
                                $badge         = get_post_meta(get_the_ID(), '_book_badge', true);
                            ?>
                                <article class="book-sales-card">
                                    <a href="<?php the_permalink(); ?>" class="book-sales-link">
                                        <div class="book-sales-cover">
                                            <?php the_post_thumbnail('medium'); ?>
                                            <?php if ($badge) : ?><span class="book-sales-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="book-sales-content">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                                        <div class="book-sales-meta">
                                            <div class="book-sales-pricing">
                                                <?php if ($product->is_on_sale() && $regular_price) : ?>
                                                    <span class="book-sales-old-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo esc_html($regular_price); ?></span>
                                                <?php endif; ?>
                                                <span class="book-sales-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo esc_html($price); ?></span>
                                            </div>
                                            <div class="book-sales-buy-woo">
                                                <?php
                                                if (function_exists('woocommerce_template_loop_add_to_cart')) {
                                                    woocommerce_template_loop_add_to_cart();
                                                } else {
                                                ?>
                                                    <a href="<?php the_permalink(); ?>" class="book-sales-order-btn"><?php _e('Order Now', 'hidayah'); ?></a>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                        <button class="book-slider-btn book-slider-next"><span class="material-symbols-outlined">chevron_right</span></button>
                    </div>
                    <div class="book-sales-cta text-center mt-35">
                        <?php if (h_opt('book_sales_btn_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('book_sales_btn_url')); ?>" class="book-sales-more-btn">
                                <?php echo h_opt('book_sales_btn_label'); ?>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (h_opt('show_about_section', true)) : ?>
            <!-- About Section -->
            <section class="about-section">
                <div class="container">
                    <div class="section-title text-center"><?php echo h_opt('about_title'); ?></div>
                    <div class="about-intro">
                        <p><?php echo h_opt('about_desc'); ?></p>
                        <div class="about-buttons">
                            <a href="<?php echo esc_url(h_opt('about_btn1_url')); ?>" class="btn btn-primary"><?php echo h_opt('about_btn1_label'); ?></a>
                            <a href="<?php echo esc_url(h_opt('about_btn2_url')); ?>" class="btn btn-primary-outline"><?php echo h_opt('about_btn2_label'); ?></a>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>


        <?php if (h_opt('show_monthly_magazine_section', true)) : ?>
            <!-- Publications Section (Monthly Magazine) -->
            <section class="publications-section">
                <div class="container monthly-hdr-wrapper">
                    <div class="section-title text-center"><?php echo h_opt('monthly_magazine_title', __('Monthly Haquer Dawat', 'hidayah')); ?></div>
                    <p class="publications-subtitle text-center"><?php echo h_opt('monthly_magazine_subtitle', __('Monthly Publications and Magazine', 'hidayah')); ?></p>

                    <div class="publications-grid">
                        <?php
                        $issues = new WP_Query(array('post_type' => 'monthly_magazine', 'posts_per_page' => 2));
                        $i = 0;
                        while ($issues->have_posts()) : $issues->the_post();
                            $vol  = get_post_meta(get_the_ID(), '_issue_vol', true);
                            $num  = get_post_meta(get_the_ID(), '_issue_num', true);
                            $month = get_post_meta(get_the_ID(), '_issue_month', true);
                            $toc   = get_post_meta(get_the_ID(), '_issue_toc_short', true);
                            $badge      = ($i === 0) ? __('Current Issue', 'hidayah') : __('Special Issue', 'hidayah');
                            $badge_icon = ($i === 0) ? '📖' : '⭐';
                            $class      = ($i === 0) ? 'current-issue' : 'special-issue';
                            $info_label = ($i === 0) ? __('In this issue:', 'hidayah') : __('Special topics:', 'hidayah');
                            $info_icon  = ($i === 0) ? '📝' : '📚';
                            $btn_primary = ($i === 0) ? 'btn btn-sm' : 'btn btn-white btn-sm';
                            $btn_outline = ($i === 0) ? 'btn btn-outline btn-sm' : 'btn btn-white-outline btn-sm';
                        ?>
                            <div class="<?php echo $class; ?>">
                                <h3><a href="<?php the_permalink(); ?>"><?php echo $badge_icon . ' ' . $badge; ?></a></h3>
                                <div class="issue-cover-wrapper" style="text-align: center; margin-bottom: 20px;">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium', array('style' => 'max-width: 180px; height: auto; border-radius: 8px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);')); ?>
                                    <?php endif; ?>
                                </div>
                                <h4><a href="<?php the_permalink(); ?>"><?php echo h_bn_num(get_the_title()); ?></a></h4>
                                <p><?php echo h_bn_num(esc_html($month ? $month : get_the_date())); ?></p>
                                <div class="issue-content">
                                    <h5><?php echo $info_icon . ' ' . $info_label; ?></h5>
                                    <?php
                                    if ($toc) {
                                        $toc_items = explode("\n", str_replace("\r", "", strip_tags($toc)));
                                        if (!empty($toc_items)) {
                                            echo '<ul class="issue-list">';
                                            foreach ($toc_items as $item) {
                                                if (trim($item)) {
                                                    echo '<li>' . h_bn_num(esc_html(trim($item))) . '</li>';
                                                }
                                            }
                                            echo '</ul>';
                                        }
                                    } else {
                                        echo h_bn_num(wpautop(wp_trim_words(get_the_excerpt(), 20)));
                                    }
                                    ?>
                                </div>
                                <div class="issue-buttons">
                                    <a href="<?php the_permalink(); ?>" class="<?php echo $btn_primary; ?>">📥 <?php _e('PDF Download', 'hidayah'); ?> →</a>
                                    <a href="<?php the_permalink(); ?>" class="<?php echo $btn_outline; ?>">📖 <?php _e('Read Online', 'hidayah'); ?> →</a>
                                </div>
                            </div>
                        <?php $i++;
                        endwhile;
                        wp_reset_postdata(); ?>
                    </div>

                    <div class="text-center mt-35">
                        <?php if (h_opt('monthly_magazine_btn_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('monthly_magazine_btn_url')); ?>" class="btn btn-outline"><?php echo h_opt('monthly_magazine_btn_label'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (h_opt('show_probondho_section', true)) : ?>
            <!-- Latest Articles Section (Probondho) -->
            <section class="articles-section" style="padding: 70px 0;">
                <div class="container">
                    <div class="section-title text-center"><?php echo h_opt('articles_title'); ?></div>

                    <div class="articles-grid">
                        <?php
                        $articles = new WP_Query(array('post_type' => 'probondho', 'posts_per_page' => 4));
                        while ($articles->have_posts()) : $articles->the_post();
                        ?>
                            <div class="card">
                                <a href="<?php the_permalink(); ?>" class="article-img-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </a>
                                <div class="content">
                                    <a href="<?php the_permalink(); ?>" class="article-title-link">
                                        <h4><?php the_title(); ?></h4>
                                    </a>
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    <div class="meta">
                                        <span class="meta-link"><span>✍️ <?php the_author(); ?></span></span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>



                    <div class="text-center mt-35">
                        <?php if (h_opt('articles_btn_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('articles_btn_url')); ?>" class="btn btn-outline"><?php echo h_opt('articles_btn_label'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (h_opt('show_promo_section', true)) : ?>
            <!-- Promo Wide Section (Darbar & Education) -->
            <section class="promo-wide-section">
                <div class="container promo-wide-wrapper promo-wide-gradient">
                    <div class="promo-wide-content">
                        <h3><?php echo h_opt('promo_title'); ?></h3>
                        <p>
                            <?php echo h_opt('promo_desc'); ?>
                        </p>
                        <div class="flex-center-wrap" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                            <a href="<?php echo esc_url(h_opt('promo_btn1_url')); ?>" class="btn btn-white"><?php echo h_opt('promo_btn1_label'); ?></a>
                            <a href="<?php echo esc_url(h_opt('promo_btn2_url')); ?>" class="btn btn-white-outline"><?php echo h_opt('promo_btn2_label'); ?></a>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (h_opt('show_dini_jiggasa_section', true)) : ?>
            <!-- Q&A Section (Dini Jiggasa) -->
            <section class="qa-section">
                <div class="container">
                    <div class="section-title text-center"><?php echo h_opt('qa_title'); ?></div>
                    <p class="section-intro text-center">
                        <?php echo h_opt('qa_subtitle'); ?>
                    </p>

                    <div class="book-slider-wrapper">
                        <button class="book-slider-btn book-slider-prev">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>

                        <div class="qa-slider-grid">
                            <?php
                            $qa_args = array(
                                'post_type'      => 'dini_jiggasa',
                                'posts_per_page' => 10,
                            );
                            $qa_query = new WP_Query($qa_args);
                            if ($qa_query->have_posts()) :
                                while ($qa_query->have_posts()) : $qa_query->the_post();
                                    $views = get_post_meta(get_the_ID(), '_post_views_count', true) ?: 0;
                                    $terms = get_the_terms(get_the_ID(), 'topic');
                                    $cat_name = ($terms && ! is_wp_error($terms)) ? $terms[0]->name : __('General', 'hidayah');
                            ?>
                                    <article class="card">
                                        <div class="content">
                                            <a href="<?php the_permalink(); ?>" class="article-title-link">
                                                <h4>❓ <?php the_title(); ?></h4>
                                            </a>
                                            <p><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
                                            <div class="meta">
                                                <span class="meta-link"><span>📚 <?php echo esc_html($cat_name); ?></span></span> •
                                                <span>👁️ <?php echo esc_html($views); ?> <?php _e('times read', 'hidayah'); ?></span>
                                            </div>
                                        </div>
                                    </article>
                                <?php endwhile;
                                wp_reset_postdata();
                            else : ?>
                                <p class="text-center"><?php _e('No questions and answers found.', 'hidayah'); ?></p>
                            <?php endif; ?>
                        </div>

                        <button class="book-slider-btn book-slider-next">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>

                    <div class="text-center mt-35 mb-30 qa-action-buttons">
                        <?php if (h_opt('qa_view_all_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('qa_view_all_url')); ?>" class="btn btn-outline"><?php echo h_opt('qa_view_all_label'); ?></a>
                        <?php endif; ?>
                        <?php if (h_opt('qa_ask_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('qa_ask_url')); ?>" class="btn btn-outline"><?php echo h_opt('qa_ask_label'); ?></a>
                        <?php endif; ?>
                        <?php if (h_opt('qa_msg_url')) : ?>
                            <a href="<?php echo esc_url(h_opt('qa_msg_url')); ?>" class="btn btn-outline"><?php echo h_opt('qa_msg_label'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <script>
            // ── Homepage: Universal YouTube Audio Mode ──────────────────
            (function() {
                var ytCards = document.querySelectorAll('.yt-audio-mode');
                if (!ytCards.length) return;

                var ytPlayers = {};
                var ytTimers = {};

                function loadYTAPI() {
                    if (window.YT && window.YT.Player) {
                        initAllPlayers();
                        return;
                    }
                    var tag = document.createElement('script');
                    tag.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(tag);
                }

                window.onYouTubeIframeAPIReady = function() {
                    initAllPlayers();
                };

                function initAllPlayers() {
                    ytCards.forEach(function(card) {
                        var ytid = card.getAttribute('data-ytid');
                        if (!ytid) return;
                        var iframeId = 'yt-player-' + ytid + '-' + Math.random().toString(36).slice(2, 6);
                        var wrap = card.querySelector('[id^="yt-"]');
                        if (!wrap) {
                            wrap = document.createElement('div');
                            wrap.style.cssText = 'position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;';
                            card.style.position = 'relative';
                            card.appendChild(wrap);
                        }
                        var inner = document.createElement('div');
                        inner.id = iframeId;
                        wrap.appendChild(inner);

                        ytPlayers[iframeId] = new YT.Player(iframeId, {
                            height: '1',
                            width: '1',
                            videoId: ytid,
                            playerVars: {
                                autoplay: 0,
                                controls: 0,
                                disablekb: 1,
                                rel: 0,
                                modestbranding: 1
                            },
                            events: {
                                'onReady': function(e) {
                                    wireControls(card, e.target, iframeId);
                                },
                                'onStateChange': function(e) {
                                    syncIcon(card, e.data);
                                }
                            }
                        });
                    });
                }

                function fmtTime(s) {
                    s = Math.floor(s || 0);
                    var m = Math.floor(s / 60),
                        sec = s % 60;
                    return m + ':' + (sec < 10 ? '0' : '') + sec;
                }

                function wireControls(card, player, iframeId) {
                    var $playBtn = card.querySelector('.player-btn-main'),
                        $backBtn = card.querySelector('.player-btn-backward'),
                        $fwdBtn = card.querySelector('.player-btn-forward'),
                        $seek = card.querySelector('.player-seek'),
                        $curr = card.querySelector('.player-current'),
                        $dur = card.querySelector('.player-duration');

                    if ($playBtn) {
                        $playBtn.addEventListener('click', function() {
                            Object.keys(ytPlayers).forEach(function(id) {
                                if (id !== iframeId && ytPlayers[id] && ytPlayers[id].getPlayerState && ytPlayers[id].getPlayerState() === YT.PlayerState.PLAYING) {
                                    ytPlayers[id].pauseVideo();
                                }
                            });
                            var state = player.getPlayerState();
                            (state === YT.PlayerState.PLAYING) ? player.pauseVideo(): player.playVideo();
                        });
                    }
                    if ($backBtn) $backBtn.addEventListener('click', function() {
                        player.seekTo(Math.max(0, player.getCurrentTime() - 10), true);
                    });
                    if ($fwdBtn) $fwdBtn.addEventListener('click', function() {
                        player.seekTo(player.getCurrentTime() + 10, true);
                    });
                    if ($seek) $seek.addEventListener('input', function() {
                        player.seekTo(parseFloat(this.value), true);
                    });

                    ytTimers[iframeId] = setInterval(function() {
                        var dur = player.getDuration ? player.getDuration() : 0;
                        var curr = player.getCurrentTime ? player.getCurrentTime() : 0;
                        if ($seek && dur) {
                            $seek.max = Math.floor(dur);
                            $seek.value = Math.floor(curr);
                        }
                        if ($curr) $curr.textContent = fmtTime(curr);
                        if ($dur && dur) $dur.textContent = fmtTime(dur);
                    }, 500);
                }

                function syncIcon(card, state) {
                    var icon = card.querySelector('.yt-play-btn span, .player-btn-main span');
                    if (icon) icon.textContent = (state === 1) ? 'pause' : 'play_arrow';
                }

                loadYTAPI();
            })();
        </script>

    <?php
else :
    // ── Standard Loop for Other fallback pages ────────────
    ?>
        <div class="container" style="padding: 100px 0;">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile;
            else : ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'hidayah'); ?></p>
            <?php endif; ?>
        </div>
    <?php
endif;

get_footer();
