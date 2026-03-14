<?php
/**
 * Front Page Template
 *
 * @package Hidayah
 */
get_header();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h2><?php echo h_opt( 'hero_title', 'কুরআন-সুন্নাহ ভিত্তিক খাঁটি ঈমান, আমল, আক্বিদা ও আখলাক গঠনে' ); ?></h2>
        <p><?php echo h_opt( 'hero_desc', 'হক্বানী আলেম ও অলী হওয়ার নিয়তে বায়াত ও ভর্তি হোন, হক্বানী অলী ও আলেম বানানোর নিয়তে দেশ-বিদেশে ছড়িয়ে পড়ুন।' ); ?></p>
    </div>
</section>

<!-- Notice Section -->
<section class="notice-section">
    <div class="notice-marquee-container">
        <div class="notice-marquee">
            <div class="notice-track">
                <?php
                $notices = new WP_Query( array(
                    'post_type'      => 'notice',
                    'posts_per_page' => 10,
                ) );
                if ( $notices->have_posts() ) :
                    while ( $notices->have_posts() ) : $notices->the_post(); ?>
                        <span class="notice-item"><a href="<?php the_permalink(); ?>">📢 <?php the_title(); ?></a></span>
                        <span class="notice-separator">•</span>
                    <?php endwhile;
                else : ?>
                    <span class="notice-item"><?php _e( 'কোন ঘোষণা নেই।', 'hidayah' ); ?></span>
                <?php endif; wp_reset_postdata(); ?>
            </div>
            <!-- Duplicate for infinite marquee effect -->
            <div class="notice-track" aria-hidden="true">
                <?php
                if ( $notices->have_posts() ) :
                    while ( $notices->have_posts() ) : $notices->the_post(); ?>
                        <span class="notice-item"><a href="<?php the_permalink(); ?>">📢 <?php the_title(); ?></a></span>
                        <span class="notice-separator">•</span>
                    <?php endwhile;
                endif; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</section>

<!-- MEDIA TABS SECTION -->
<div class="unified-media-section">
    <div class="media-tabs-wrapper">
        <!-- Tab Buttons -->
        <div class="media-tab-buttons">
            <button class="media-tab-btn active" data-tab="audio">
                <span class="material-symbols-outlined">headphones</span> <?php _e( 'অডিও', 'hidayah' ); ?>
            </button>
            <button class="media-tab-btn" data-tab="video">
                <span class="material-symbols-outlined">videocam</span> <?php _e( 'ভিডিও', 'hidayah' ); ?>
            </button>
        </div>

        <!-- AUDIO TAB CONTENT -->
        <div class="media-tab-content active" id="tab-audio">
            <div class="audio-section-inner">
                <div class="mahfil-audio-layout">
                    <!-- Main Audio Content -->
                    <div class="audio-main-content">
                        <?php
                        $feat_audio = new WP_Query( array(
                            'post_type'      => 'audio',
                            'posts_per_page' => 1,
                            'meta_key'       => '_is_featured',
                            'meta_value'     => 'yes'
                        ) );
                        if ( ! $feat_audio->have_posts() ) {
                            $feat_audio = new WP_Query( array( 'post_type' => 'audio', 'posts_per_page' => 1 ) );
                        }

                        if ( $feat_audio->have_posts() ) : $feat_audio->the_post();
                            $audio_url   = get_post_meta( get_the_ID(), '_audio_url',      true );
                            $youtube_url = get_post_meta( get_the_ID(), '_youtube_url',    true );
                            $v_count     = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                            $duration    = get_post_meta( get_the_ID(), '_audio_duration',  true ) ?: '0:00';
                            $location    = get_post_meta( get_the_ID(), '_mahfil_location', true );
                            // Extract YouTube video ID
                            $feat_yt_id  = '';
                            if ( $youtube_url && ! $audio_url ) {
                                preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $yt_m );
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
                                            <?php _e( 'সর্বশেষ বয়ান', 'hidayah' ); ?>
                                        </div>
                                    </div>
                                    <?php if ( $feat_yt_id ) : ?>
                                    <!-- YouTube Audio Mode (Featured) -->
                                    <div class="audio-player yt-audio-mode" id="featured-audio-player" data-ytid="<?php echo esc_attr( $feat_yt_id ); ?>">
                                        <div class="player-controls">
                                            <button class="player-btn player-btn-backward" title="<?php _e( '১০ সেকেন্ড পিছনে', 'hidayah' ); ?>"><span class="material-symbols-outlined">replay_10</span></button>
                                            <button class="player-btn player-btn-main yt-play-btn" title="<?php _e( 'চালান / থামান', 'hidayah' ); ?>"><span class="material-symbols-outlined">play_arrow</span></button>
                                            <button class="player-btn player-btn-forward" title="<?php _e( '১০ সেকেন্ড সামনে', 'hidayah' ); ?>"><span class="material-symbols-outlined">forward_10</span></button>
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
                                            <button class="player-btn player-btn-backward" title="<?php _e( '১০ সেকেন্ড পিছনে', 'hidayah' ); ?>"><span class="material-symbols-outlined">replay_10</span></button>
                                            <button class="player-btn player-btn-main" title="<?php _e( 'চালান / থামান', 'hidayah' ); ?>"><span class="material-symbols-outlined">play_arrow</span></button>
                                            <button class="player-btn player-btn-forward" title="<?php _e( '১০ সেকেন্ড সামনে', 'hidayah' ); ?>"><span class="material-symbols-outlined">forward_10</span></button>
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
                                            <span><span class="material-symbols-outlined">headphones</span> <?php echo h_bn_num($v_count); ?> <?php _e( 'শ্রোতা', 'hidayah' ); ?></span>
                                            <span><span class="material-symbols-outlined">calendar_today</span> <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('আগে', 'hidayah'); ?></span>
                                        </div>
                                    </div>
                                    <div class="video-meta-details">
                                        <?php if ($location) : ?>
                                            <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">location_on</span> <?php echo esc_html($location); ?></span>
                                        <?php endif; ?>
                                        <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">calendar_today</span> <?php echo get_the_date(); ?></span>
                                        <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">person</span> <?php the_author(); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; wp_reset_postdata(); ?>
                    </div>

                    <!-- Sidebar Audio Players -->
                    <div class="audio-sidebar-list">
                        <?php
                        $side_audio = new WP_Query( array( 'post_type' => 'audio', 'posts_per_page' => 2, 'offset' => 1 ) );
                        $side_yt_ids = [];
                        while ( $side_audio->have_posts() ) : $side_audio->the_post();
                            $s_url     = get_post_meta( get_the_ID(), '_audio_url',     true );
                            $s_yt_url  = get_post_meta( get_the_ID(), '_youtube_url',   true );
                            $s_yt_id   = '';
                            if ( $s_yt_url && ! $s_url ) {
                                preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $s_yt_url, $sy_m );
                                $s_yt_id = $sy_m[1] ?? '';
                            }
                            $side_yt_ids[] = $s_yt_id;
                        ?>
                            <?php if ( $s_yt_id ) : ?>
                            <!-- YouTube sidebar card -->
                            <div class="audio-card yt-audio-mode" data-ytid="<?php echo esc_attr( $s_yt_id ); ?>" id="side-yt-<?php echo esc_attr( $s_yt_id ); ?>">
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
                                <?php if ( $s_yt_id ) : ?>
                                <div style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;">
                                    <div id="yt-side-<?php echo esc_attr( $s_yt_id ); ?>"></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                        <a href="<?php echo get_post_type_archive_link('audio'); ?>" class="sidebar-view-more-btn"><?php _e( 'আরও অডিও শুনুন', 'hidayah' ); ?> <span class="material-symbols-outlined">arrow_forward</span></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- VIDEO TAB CONTENT -->
        <div class="media-tab-content" id="tab-video">
            <div class="mahfil-section-full">
                <main class="mahfil-main-container">
                    <div class="mahfil-video-layout">
                        <!-- Main Video -->
                        <div class="mahfil-main-video">
                            <?php
                            $feat_video = new WP_Query( array(
                                'post_type'      => 'video',
                                'posts_per_page' => 1,
                                'meta_key'       => '_is_featured',
                                'meta_value'     => 'yes'
                            ) );
                            if ( ! $feat_video->have_posts() ) {
                                $feat_video = new WP_Query( array( 'post_type' => 'video', 'posts_per_page' => 1 ) );
                            }

                            if ( $feat_video->have_posts() ) : $feat_video->the_post();
                                $video_id = get_post_meta( get_the_ID(), '_video_id', true );
                                $v_loc    = get_post_meta( get_the_ID(), '_mahfil_location', true );
                            ?>
                                <div class="live-video-wrapper video-inplace" data-video-id="<?php echo esc_attr($video_id); ?>">
                                    <div class="live-video-cover">
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'live-video-thumb' ) ); ?>
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
                                                <div class="video-badge"><span class="material-symbols-outlined">star</span> <?php _e( 'বিশেষ বয়ান', 'hidayah' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="video-meta-details">
                                            <?php if ($v_loc) : ?>
                                                <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">location_on</span> <?php echo esc_html($v_loc); ?></span>
                                            <?php endif; ?>
                                            <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">calendar_today</span> <?php echo get_the_date(); ?></span>
                                            <span class="video-meta-item"><span class="material-symbols-outlined icon-xs">person</span> <?php the_author(); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; wp_reset_postdata(); ?>
                        </div>

                        <!-- Sidebar Videos -->
                        <div class="mahfil-sidebar-videos">
                            <h4 class="sidebar-heading"><?php _e( 'অন্যান্য ভিডিও', 'hidayah' ); ?></h4>
                            <?php
                            $side_video = new WP_Query( array( 'post_type' => 'video', 'posts_per_page' => 4, 'offset' => 1 ) );
                            while ( $side_video->have_posts() ) : $side_video->the_post();
                                $sv_id = get_post_meta( get_the_ID(), '_video_id', true );
                            ?>
                                <div class="sidebar-video-card">
                                    <div class="sidebar-thumb-wrapper video-thumb" data-video-id="<?php echo esc_attr($sv_id); ?>">
                                        <?php the_post_thumbnail( 'medium' ); ?>
                                        <div class="play-overlay"><span class="material-symbols-outlined play-icon-lg">play_circle</span></div>
                                    </div>
                                    <div class="sidebar-card-content">
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <div class="sidebar-meta"><span><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('আগে', 'hidayah'); ?></span></div>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                            <a href="<?php echo get_post_type_archive_link('video'); ?>" class="sidebar-view-more-btn"><?php _e( 'আরও ভিডিও দেখুন', 'hidayah' ); ?> <span class="material-symbols-outlined">arrow_forward</span></a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="mahfil-schedule-section">
        <div class="schedule-list">
            <?php
            $schedules = new WP_Query( array(
                'post_type'      => 'notice',
                'posts_per_page' => 3,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'notice_cat',
                        'field'    => 'slug',
                        'terms'    => 'schedule',
                    ),
                ),
            ) );
            if ( $schedules->have_posts() ) :
                while ( $schedules->have_posts() ) : $schedules->the_post();
                    $s_date = get_post_meta( get_the_ID(), '_schedule_date', true ); // Y-m-d
                    $s_time = get_post_meta( get_the_ID(), '_schedule_time', true );
                    $s_loc  = get_post_meta( get_the_ID(), '_schedule_location', true );
                    
                    $day   = date_i18n( 'd', strtotime($s_date) );
                    $month = date_i18n( 'F, Y', strtotime($s_date) );
                    $day_n = date_i18n( 'l', strtotime($s_date) );
            ?>
                <div class="schedule-card">
                    <div class="schedule-date">
                        <span class="date-day"><?php echo h_bn_num($day); ?></span>
                        <span class="date-month"><?php echo esc_html($month); ?></span>
                    </div>
                    <div class="schedule-info">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_author(); ?></a></h4>
                        <p class="schedule-location"><span class="material-symbols-outlined schedule-location-icon">location_on</span> <?php echo esc_html($s_loc); ?></p>
                    </div>
                    <div class="schedule-time">
                        <span class="time-text"><?php echo h_bn_num($s_time); ?></span>
                        <span class="day-text"><?php echo esc_html($day_n); ?></span>
                        <button class="reminder-btn"><?php _e( 'রিমাইন্ডার দিন', 'hidayah' ); ?></button>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </div>
</div>

<!-- Book Sales Section -->
<section class="book-sales-section">
    <div class="container">
        <div class="section-title text-center"><?php echo h_opt( 'book_sales_title', 'বই বিক্রয় কর্নার' ); ?></div>
        <?php if ( $book_subtitle = h_opt( 'book_sales_subtitle' ) ) : ?>
            <p class="section-subtitle text-center" style="max-width: 800px; margin: -15px auto 30px; opacity: 0.8; font-size: 16px;"><?php echo esc_html( $book_subtitle ); ?></p>
        <?php endif; ?>
        <div class="book-slider-wrapper">
            <button class="book-slider-btn book-slider-prev"><span class="material-symbols-outlined">chevron_left</span></button>
            <div class="book-sales-grid">
                <?php
                $books = new WP_Query( array( 'post_type' => 'book', 'posts_per_page' => 8 ) );
                while ( $books->have_posts() ) : $books->the_post();
                    $price     = get_post_meta( get_the_ID(), '_book_price', true );
                    $old_price = get_post_meta( get_the_ID(), '_book_old_price', true );
                    $badge     = get_post_meta( get_the_ID(), '_book_badge', true );
                ?>
                    <article class="book-sales-card">
                        <a href="<?php the_permalink(); ?>" class="book-sales-link">
                            <div class="book-sales-cover">
                                <?php the_post_thumbnail( 'medium' ); ?>
                                <?php if ($badge) : ?><span class="book-sales-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
                            </div>
                        </a>
                        <div class="book-sales-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 10 ); ?></p>
                            <div class="book-sales-meta">
                                <div class="book-sales-pricing">
                                    <?php if ($old_price) : ?><span class="book-sales-old-price">৳ <?php echo h_bn_num($old_price); ?></span><?php endif; ?>
                                    <span class="book-sales-price">৳ <?php echo h_bn_num($price); ?></span>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="book-sales-order-btn"><?php _e( 'অর্ডার করুন', 'hidayah' ); ?></a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <button class="book-slider-btn book-slider-next"><span class="material-symbols-outlined">chevron_right</span></button>
        </div>
        <div class="book-sales-cta text-center mt-35">
            <a href="<?php echo esc_url( h_opt( 'book_sales_btn_url', '#' ) ); ?>" class="book-sales-more-btn">
                <?php echo h_opt( 'book_sales_btn_label', 'আরও দেখুন' ); ?>
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="section-title text-center"><?php echo h_opt( 'about_title', 'দরবার ও দাওয়াত সম্পর্কে' ); ?></div>
        <div class="about-intro">
            <p><?php echo h_opt( 'about_desc', 'দরবার ও দাওয়াত একটি দলীলভিত্তিক, মার্জিত ও দায়িত্বশীল দ্বীনি মারকাজ। আমাদের লক্ষ্য হলো পবিত্র কুরআন ও সহীহ হাদীসের আলোকে বিশুদ্ধ ইসলামী জ্ঞান প্রচার করা এবং তরবিয়ত ও তাযকিয়ার মাধ্যমে প্রকৃত মুসলিম হিসেবে গড়ে তোলা।' ); ?></p>
            <div class="about-buttons">
                <a href="<?php echo esc_url( h_opt( 'about_btn1_url', '#' ) ); ?>" class="btn"><?php echo h_opt( 'about_btn1_label', 'বিস্তারিত জানুন →' ); ?></a>
                <a href="<?php echo esc_url( h_opt( 'about_btn2_url', '#' ) ); ?>" class="btn btn-outline"><?php echo h_opt( 'about_btn2_label', 'যোগাযোগ করুন →' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- Publications Section (Monthly HD) -->
<section class="publications-section">
    <div class="container">
        <div class="section-title text-center"><?php echo h_opt( 'monthly_hd_title', 'মাসিক হক্বের দাওয়াত - প্রকাশনা' ); ?></div>
        <p class="publications-subtitle text-center"><?php echo h_opt( 'monthly_hd_subtitle', 'সহীহ আকিদা, ইবাদত ও তাযকিয়াহভিত্তিক মাসিক দলীলসমৃদ্ধ প্রকাশনা' ); ?></p>

        <div class="publications-grid">
            <?php
            $issues = new WP_Query( array( 'post_type' => 'monthly_hd', 'posts_per_page' => 2 ) );
            $i = 0;
            while ( $issues->have_posts() ) : $issues->the_post();
                $vol  = get_post_meta( get_the_ID(), '_issue_vol', true );
                $num  = get_post_meta( get_the_ID(), '_issue_num', true );
                $month = get_post_meta( get_the_ID(), '_issue_month', true );
                $toc   = get_post_meta( get_the_ID(), '_issue_toc_short', true );
                $pdf   = get_post_meta( get_the_ID(), '_issue_pdf', true );
                $badge = ($i === 0) ? __( 'চলতি সংখ্যা', 'hidayah' ) : __( 'বিশেষ সংখ্যা', 'hidayah' );
                $class = ($i === 0) ? 'current-issue' : 'special-issue';
            ?>
                <div class="<?php echo $class; ?>">
                    <h3><a href="<?php the_permalink(); ?>">📖 <?php echo $badge; ?></a></h3>
                    <h4><a href="<?php the_permalink(); ?>"><?php echo h_bn_num($vol) . ' ' . __('বর্ষ', 'hidayah') . ' ' . h_bn_num($num) . ' ' . __('সংখ্যা', 'hidayah'); ?></a></h4>
                    <p><?php echo esc_html($month); ?></p>
                    <div class="issue-content">
                        <h5>📝 <?php _e( 'এই সংখ্যায়:', 'hidayah' ); ?></h5>
                        <?php if ($toc) : echo wpautop($toc); endif; ?>
                    </div>
                    <div class="issue-buttons">
                        <?php if ($pdf) : ?><a href="<?php echo esc_url($pdf); ?>" class="btn btn-sm">📥 PDF ডাউনলোড →</a><?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">📖 অনলাইনে পড়ুন →</a>
                    </div>
                </div>
            <?php $i++; endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="text-center mt-35">
            <a href="<?php echo esc_url( h_opt( 'monthly_hd_btn_url', '#' ) ); ?>" class="btn btn-outline"><?php echo h_opt( 'monthly_hd_btn_label', 'আরও দেখুন →' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>

<script>
// ── Homepage: Universal YouTube Audio Mode ──────────────────
// Finds every .yt-audio-mode element on the page and wires up
// the YouTube IFrame API for each one.
(function() {

    // Collect all YT player cards
    var ytCards = document.querySelectorAll('.yt-audio-mode');
    if (!ytCards.length) return; // Nothing to do

    var ytPlayers  = {}; // { iframeId: YT.Player }
    var ytTimers   = {};

    function loadYTAPI() {
        if (window.YT && window.YT.Player) { initAllPlayers(); return; }
        var tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        document.head.appendChild(tag);
    }

    // Called by YT API automatically
    window.onYouTubeIframeAPIReady = function() { initAllPlayers(); };

    function initAllPlayers() {
        ytCards.forEach(function(card) {
            var ytid = card.getAttribute('data-ytid');
            if (!ytid) return;

            // Each card needs a unique hidden div for YT to inject the iframe
            var iframeId = 'yt-player-' + ytid + '-' + Math.random().toString(36).slice(2, 6);

            // Create or reuse hidden container inside the card
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
                height: '1', width: '1',
                videoId: ytid,
                playerVars: { autoplay: 0, controls: 0, disablekb: 1, rel: 0, modestbranding: 1 },
                events: {
                    'onReady':       function(e) { wireControls(card, e.target, iframeId); },
                    'onStateChange': function(e) { syncIcon(card, e.data); }
                }
            });
        });
    }

    function fmtTime(s) {
        s = Math.floor(s || 0);
        var m = Math.floor(s / 60), sec = s % 60;
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }

    function wireControls(card, player, iframeId) {
        var $playBtn  = card.querySelector('.player-btn-main');
        var $backBtn  = card.querySelector('.player-btn-backward');
        var $fwdBtn   = card.querySelector('.player-btn-forward');
        var $seek     = card.querySelector('.player-seek');
        var $curr     = card.querySelector('.player-current');
        var $dur      = card.querySelector('.player-duration');

        if ($playBtn) {
            $playBtn.addEventListener('click', function() {
                // Pause any other YT players on the page
                Object.keys(ytPlayers).forEach(function(id) {
                    if (id !== iframeId && ytPlayers[id] && ytPlayers[id].getPlayerState &&
                        ytPlayers[id].getPlayerState() === YT.PlayerState.PLAYING) {
                        ytPlayers[id].pauseVideo();
                    }
                });
                var state = player.getPlayerState();
                if (state === YT.PlayerState.PLAYING) { player.pauseVideo(); }
                else { player.playVideo(); }
            });
        }
        if ($backBtn) { $backBtn.addEventListener('click', function() { player.seekTo(Math.max(0, player.getCurrentTime() - 10), true); }); }
        if ($fwdBtn)  { $fwdBtn.addEventListener('click',  function() { player.seekTo(player.getCurrentTime() + 10, true); }); }
        if ($seek)    { $seek.addEventListener('input',    function() { player.seekTo(parseFloat(this.value), true); }); }

        // Tick timer
        ytTimers[iframeId] = setInterval(function() {
            var dur  = player.getDuration     ? player.getDuration()     : 0;
            var curr = player.getCurrentTime  ? player.getCurrentTime()  : 0;
            if ($seek && dur) { $seek.max = Math.floor(dur); $seek.value = Math.floor(curr); }
            if ($curr) $curr.textContent = fmtTime(curr);
            if ($dur && dur)  $dur.textContent  = fmtTime(dur);
        }, 500);
    }

    function syncIcon(card, state) {
        var icon = card.querySelector('.yt-play-btn span, .player-btn-main span');
        if (!icon) return;
        icon.textContent = (state === 1 /* PLAYING */) ? 'pause' : 'play_arrow';
    }

    loadYTAPI();

})();
</script>
