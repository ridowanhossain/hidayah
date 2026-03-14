<?php
/**
 * Homepage Template (index.php)
 * index.html থেকে কনভার্ট। সব হার্ডকোড ডেটা WP Query দিয়ে Dynamic করা হয়েছে।
 *
 * @package Hidayah
 */

get_header();
?>

<!-- ══════════════════════════════════════════
     হিরো সেকশন
     ══════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <h2><?php echo esc_html( get_theme_mod( 'hero_title', 'কুরআন-সুন্নাহ ভিত্তিক খাঁটি ঈমান, আমল, আক্বিদা ও আখলাক গঠনে' ) ); ?></h2>
        <p><?php echo esc_html( get_theme_mod( 'hero_subtitle', 'হক্বানী আলেম ও অলী হওয়ার নিয়তে বায়াত ও ভর্তি হোন, হক্বানী অলী ও আলেম বানানোর নিয়তে দেশ-বিদেশে ছড়িয়ে পড়ুন।' ) ); ?></p>
    </div>
</section>

<!-- ══════════════════════════════════════════
     নোটিশ মার্কি
     ══════════════════════════════════════════ -->
<section class="notice-section">
    <div class="notice-marquee-container">
        <div class="notice-marquee">
            <?php
            // সর্বশেষ ৬টি নোটিশ দেখাও
            $notice_query = new WP_Query( array(
                'post_type'      => 'notice',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            // মার্কি ২ বার রেন্ডার করব (সিমলেস লুপের জন্য)
            for ( $i = 0; $i < 2; $i++ ) :
                $aria = $i === 1 ? 'aria-hidden="true"' : '';
                echo '<div class="notice-track" ' . $aria . '>';

                if ( $notice_query->have_posts() ) :
                    while ( $notice_query->have_posts() ) :
                        $notice_query->the_post();
                        echo '<span class="notice-item"><a href="' . esc_url( get_permalink() ) . '">'
                            . esc_html( get_the_title() ) . '</a></span>';
                        echo '<span class="notice-separator">•</span>';
                    endwhile;
                    wp_reset_postdata();
                else :
                    // ফলব্যাক: কোনো নোটিশ না থাকলে
                    echo '<span class="notice-item"><a href="#">📢 হক্বের দা\'ওয়াত সিদ্দীক্বিয়া দরবার শরীফে আপনাকে স্বাগতম।</a></span>';
                    echo '<span class="notice-separator">•</span>';
                endif;

                echo '</div>';
            endfor;
            ?>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     মিডিয়া ট্যাব সেকশন (অডিও / ভিডিও)
     ══════════════════════════════════════════ -->
<div class="unified-media-section">
    <div class="media-tabs-wrapper">

        <!-- ট্যাব বাটন -->
        <div class="media-tab-buttons">
            <button class="media-tab-btn active" data-tab="audio">
                <span class="material-symbols-outlined">headphones</span>
                <?php esc_html_e( 'অডিও', 'hidayah' ); ?>
            </button>
            <button class="media-tab-btn" data-tab="video">
                <span class="material-symbols-outlined">videocam</span>
                <?php esc_html_e( 'ভিডিও', 'hidayah' ); ?>
            </button>
        </div>

        <!-- অডিও ট্যাব -->
        <div class="media-tab-content active" id="tab-audio">
            <div class="audio-section-inner">
                <div class="mahfil-audio-layout">

                    <!-- ফিচার্ড অডিও -->
                    <div class="audio-main-content">
                        <?php
                        $featured_audio = new WP_Query( array(
                            'post_type'      => 'audio',
                            'posts_per_page' => 1,
                            'post_status'    => 'publish',
                            'meta_key'       => '_featured_audio',
                            'meta_value'     => '1',
                        ) );

                        // ফিচার্ড না পেলে সর্বশেষ অডিও
                        if ( ! $featured_audio->have_posts() ) {
                            $featured_audio = new WP_Query( array(
                                'post_type'      => 'audio',
                                'posts_per_page' => 1,
                                'post_status'    => 'publish',
                            ) );
                        }

                        if ( $featured_audio->have_posts() ) :
                            $featured_audio->the_post();
                            $audio_src = get_post_meta( get_the_ID(), '_audio_url', true );
                            $speaker   = get_the_term_list( get_the_ID(), 'speaker', '', ', ', '' );
                            $location  = get_post_meta( get_the_ID(), '_audio_location', true );
                            $audio_type = get_the_term_list( get_the_ID(), 'topic', '', ', ', '' );
                        ?>
                        <div class="featured-audio-card">
                            <div class="featured-audio-cover">
                                <span class="material-symbols-outlined">graphic_eq</span>
                            </div>
                            <div class="featured-audio-info">
                                <div class="title-badge-wrapper">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <?php if ( $audio_type ) : ?>
                                    <div class="audio-badge">
                                        <span class="material-symbols-outlined">star</span>
                                        <?php echo wp_kses_post( $audio_type ); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- অডিও প্লেয়ার -->
                                <?php if ( $audio_src ) : ?>
                                <div class="audio-player" id="featured-audio-player"
                                     data-src="<?php echo esc_url( $audio_src ); ?>">
                                    <div class="player-controls">
                                        <button class="player-btn player-btn-backward" title="১০ সেকেন্ড পিছনে">
                                            <span class="material-symbols-outlined">replay_10</span>
                                        </button>
                                        <button class="player-btn player-btn-main" title="চালান / থামান">
                                            <span class="material-symbols-outlined">play_arrow</span>
                                        </button>
                                        <button class="player-btn player-btn-forward" title="১০ সেকেন্ড সামনে">
                                            <span class="material-symbols-outlined">forward_10</span>
                                        </button>
                                    </div>
                                    <div class="player-progress">
                                        <span class="player-time player-current">0:00</span>
                                        <input type="range" class="player-seek" value="0" min="0" max="0" step="1" />
                                        <span class="player-time player-duration">0:00</span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- অডিও মেটা বার -->
                        <div class="video-info-bar">
                            <div class="video-meta-left">
                                <div class="video-status">
                                    <h3 class="video-status-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="audio-status-details">
                                        <?php
                                        $duration = get_post_meta( get_the_ID(), '_audio_duration', true );
                                        if ( $duration ) : ?>
                                        <span>
                                            <span class="material-symbols-outlined">schedule</span>
                                            <?php echo esc_html( $duration ); ?>
                                        </span>
                                        <?php endif; ?>
                                        <span>
                                            <span class="material-symbols-outlined">calendar_today</span>
                                            <?php echo esc_html( hidayah_bangla_time_ago() ); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="video-meta-details">
                                    <?php if ( $location ) : ?>
                                    <span class="video-meta-item">
                                        <span class="material-symbols-outlined icon-xs">location_on</span>
                                        <?php echo esc_html( $location ); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if ( $speaker ) : ?>
                                    <span class="video-meta-item">
                                        <span class="material-symbols-outlined icon-xs">person</span>
                                        <?php echo wp_kses_post( $speaker ); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php else : ?>
                        <p class="text-center"><?php esc_html_e( 'এখনো কোনো অডিও যোগ করা হয়নি।', 'hidayah' ); ?></p>
                        <?php endif; wp_reset_postdata(); ?>
                    </div>

                    <!-- সাইডবার অডিও লিস্ট -->
                    <div class="audio-sidebar-list">
                        <?php
                        $sidebar_audio = new WP_Query( array(
                            'post_type'      => 'audio',
                            'posts_per_page' => 2,
                            'post_status'    => 'publish',
                            'offset'         => 1, // ফিচার্ড বাদে পরের ২টা
                        ) );

                        $counter = 1;
                        if ( $sidebar_audio->have_posts() ) :
                            while ( $sidebar_audio->have_posts() ) :
                                $sidebar_audio->the_post();
                                $audio_src = get_post_meta( get_the_ID(), '_audio_url', true );
                                $icon = get_post_meta( get_the_ID(), '_audio_icon', true ) ?: 'mic';
                        ?>
                        <div class="audio-card audio-player" id="audio-player-<?php echo esc_attr( $counter ); ?>"
                             data-src="<?php echo esc_url( $audio_src ); ?>">
                            <h4>
                                <a href="<?php the_permalink(); ?>">
                                    <span class="material-symbols-outlined audio-title-icon"><?php echo esc_html( $icon ); ?></span>
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            <div class="player-progress">
                                <button class="player-btn player-btn-main sidebar-player-btn-reset" title="চালান / থামান">
                                    <span class="material-symbols-outlined">play_arrow</span>
                                </button>
                                <span class="player-time player-current">0:00</span>
                                <input type="range" class="player-seek" value="0" min="0" max="0" step="1" />
                                <span class="player-time player-duration">0:00</span>
                            </div>
                        </div>
                        <?php
                                $counter++;
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'audio' ) ); ?>" class="sidebar-view-more-btn">
                            <?php esc_html_e( 'আরও অডিও শুনুন', 'hidayah' ); ?>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                </div><!-- .mahfil-audio-layout -->
            </div>
        </div><!-- #tab-audio -->

        <!-- ভিডিও ট্যাব -->
        <div class="media-tab-content" id="tab-video">
            <div class="mahfil-section-full">
                <main class="mahfil-main-container">
                    <div class="mahfil-video-layout">
                        <!-- ফিচার্ড ভিডিও -->
                        <div class="mahfil-main-video">
                            <?php
                            $featured_video = new WP_Query( array(
                                'post_type'      => 'video',
                                'posts_per_page' => 1,
                                'post_status'    => 'publish',
                            ) );
                            if ( $featured_video->have_posts() ) :
                                $featured_video->the_post();
                                $video_id = get_post_meta( get_the_ID(), '_youtube_video_id', true );
                                $speaker  = get_the_term_list( get_the_ID(), 'speaker', '', ', ', '' );
                                $topic    = get_the_term_list( get_the_ID(), 'topic', '', ', ', '' );
                                $location = get_post_meta( get_the_ID(), '_video_location', true );
                                $thumb_url = $video_id
                                    ? 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg'
                                    : get_the_post_thumbnail_url( null, 'medium_large' );
                            ?>
                            <div class="live-video-wrapper video-inplace"
                                 data-video-id="<?php echo esc_attr( $video_id ); ?>">
                                <div class="live-video-cover">
                                    <img src="<?php echo esc_url( $thumb_url ); ?>"
                                         alt="<?php the_title_attribute(); ?>"
                                         class="live-video-thumb" loading="lazy" />
                                    <div class="live-video-overlay"></div>
                                </div>
                                <div class="live-play-btn-overlay">
                                    <button class="live-play-btn">
                                        <span class="material-symbols-outlined live-icon-filled">play_arrow</span>
                                    </button>
                                </div>
                            </div>

                            <div class="video-info-bar">
                                <div class="video-meta-left">
                                    <div class="video-status">
                                        <div class="title-badge-wrapper mb-0">
                                            <h3 class="video-status-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h3>
                                            <?php if ( $topic ) : ?>
                                            <div class="video-badge">
                                                <span class="material-symbols-outlined">star</span>
                                                <?php echo wp_kses_post( $topic ); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="video-meta-details">
                                        <?php if ( $location ) : ?>
                                        <span class="video-meta-item">
                                            <span class="material-symbols-outlined icon-xs">location_on</span>
                                            <?php echo esc_html( $location ); ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="video-meta-item">
                                            <span class="material-symbols-outlined icon-xs">calendar_today</span>
                                            <?php echo esc_html( hidayah_bangla_time_ago() ); ?>
                                        </span>
                                        <?php if ( $speaker ) : ?>
                                        <span class="video-meta-item">
                                            <span class="material-symbols-outlined icon-xs">person</span>
                                            <?php echo wp_kses_post( $speaker ); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php wp_reset_postdata(); endif; ?>
                        </div>

                        <!-- সাইডবার ভিডিও -->
                        <div class="mahfil-sidebar-videos">
                            <h4 class="sidebar-heading"><?php esc_html_e( 'অন্যান্য ভিডিও', 'hidayah' ); ?></h4>
                            <?php
                            $sidebar_videos = new WP_Query( array(
                                'post_type'      => 'video',
                                'posts_per_page' => 4,
                                'post_status'    => 'publish',
                                'offset'         => 1,
                            ) );
                            if ( $sidebar_videos->have_posts() ) :
                                while ( $sidebar_videos->have_posts() ) :
                                    $sidebar_videos->the_post();
                                    $vid_id = get_post_meta( get_the_ID(), '_youtube_video_id', true );
                                    $sid_thumb = $vid_id
                                        ? 'https://img.youtube.com/vi/' . $vid_id . '/mqdefault.jpg'
                                        : get_the_post_thumbnail_url( null, 'thumbnail' );
                            ?>
                            <div class="sidebar-video-card">
                                <div class="sidebar-thumb-wrapper video-thumb"
                                     data-video-id="<?php echo esc_attr( $vid_id ); ?>">
                                    <img src="<?php echo esc_url( $sid_thumb ); ?>"
                                         alt="<?php the_title_attribute(); ?>" loading="lazy" />
                                    <div class="play-overlay">
                                        <span class="material-symbols-outlined play-icon-lg">play_circle</span>
                                    </div>
                                </div>
                                <div class="sidebar-card-content">
                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <div class="sidebar-meta">
                                        <span><?php echo esc_html( hidayah_bangla_time_ago() ); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>" class="sidebar-view-more-btn">
                                <?php esc_html_e( 'আরও ভিডিও দেখুন', 'hidayah' ); ?>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </main>
            </div>
        </div><!-- #tab-video -->

    </div><!-- .media-tabs-wrapper -->
</div><!-- .unified-media-section -->


<!-- ══════════════════════════════════════════
     বই বিক্রয় কর্নার
     ══════════════════════════════════════════ -->
<section class="book-sales-section">
    <div class="container">
        <div class="section-title text-center"><?php esc_html_e( 'বই বিক্রয় কর্নার', 'hidayah' ); ?></div>

        <div class="book-slider-wrapper">
            <button class="book-slider-btn book-slider-prev">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>

            <div class="book-sales-grid">
                <?php
                $books_query = new WP_Query( array(
                    'post_type'      => 'book',
                    'posts_per_page' => 8,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $books_query->have_posts() ) :
                    while ( $books_query->have_posts() ) :
                        $books_query->the_post();
                        $price     = get_post_meta( get_the_ID(), '_book_price', true );
                        $old_price = get_post_meta( get_the_ID(), '_book_old_price', true );
                        $badge     = get_post_meta( get_the_ID(), '_book_badge', true );
                        $thumb     = get_the_post_thumbnail_url( null, 'medium' );
                ?>
                <article class="book-sales-card">
                    <a href="<?php the_permalink(); ?>" class="book-sales-link">
                        <div class="book-sales-cover">
                            <?php if ( $thumb ) : ?>
                                <img src="<?php echo esc_url( $thumb ); ?>"
                                     alt="<?php the_title_attribute(); ?>" loading="lazy" />
                            <?php endif; ?>
                            <?php if ( $badge ) : ?>
                                <span class="book-sales-badge"><?php echo esc_html( $badge ); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="book-sales-content">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 12, '...' ); ?></p>
                        <div class="book-sales-meta">
                            <div class="book-sales-pricing">
                                <?php if ( $old_price ) : ?>
                                    <span class="book-sales-old-price">৳ <?php echo esc_html( $old_price ); ?></span>
                                <?php endif; ?>
                                <?php if ( $price ) : ?>
                                    <span class="book-sales-price">৳ <?php echo esc_html( $price ); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="book-sales-order-btn">
                                <?php esc_html_e( 'অর্ডার করুন', 'hidayah' ); ?>
                            </a>
                        </div>
                    </div>
                </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div><!-- .book-sales-grid -->

            <button class="book-slider-btn book-slider-next">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>

        <div class="book-sales-cta text-center mt-35">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>" class="book-sales-more-btn">
                <?php esc_html_e( 'আরও দেখুন', 'hidayah' ); ?>
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     দরবার সম্পর্কে (About)
     ══════════════════════════════════════════ -->
<section class="about-section">
    <div class="container">
        <div class="section-title text-center"><?php esc_html_e( 'দরবার ও দাওয়াত সম্পর্কে', 'hidayah' ); ?></div>
        <div class="about-intro">
            <p><?php echo esc_html( get_theme_mod( 'about_text', 'দরবার ও দাওয়াত একটি দলীলভিত্তিক, মার্জিত ও দায়িত্বশীল দ্বীনি মারকাজ। আমাদের লক্ষ্য হলো পবিত্র কুরআন ও সহীহ হাদীসের আলোকে বিশুদ্ধ ইসলামী জ্ঞান প্রচার করা এবং তরবিয়ত ও তাযকিয়ার মাধ্যমে প্রকৃত মুসলিম হিসেবে গড়ে তোলা।' ) ); ?></p>
            <div class="about-buttons">
                <a href="<?php echo esc_url( home_url( '/darbar' ) ); ?>" class="btn">
                    <?php esc_html_e( 'বিস্তারিত জানুন →', 'hidayah' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline">
                    <?php esc_html_e( 'যোগাযোগ করুন →', 'hidayah' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     মাসিক হক্বের দাওয়াত প্রকাশনা
     ══════════════════════════════════════════ -->
<section class="publications-section">
    <div class="container">
        <div class="section-title text-center"><?php esc_html_e( "মাসিক হক্বের দা'ওয়াত - প্রকাশনা", 'hidayah' ); ?></div>
        <p class="publications-subtitle text-center">
            <?php esc_html_e( 'সহীহ আকিদা, ইবাদত ও তাযকিয়াহভিত্তিক মাসিক দলীলসমৃদ্ধ প্রকাশনা', 'hidayah' ); ?>
        </p>

        <div class="publications-grid">
            <?php
            // চলতি সংখ্যা (সর্বশেষ)
            $current_issue = new WP_Query( array(
                'post_type'      => 'monthly_hd',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $current_issue->have_posts() ) :
                $current_issue->the_post();
                $pdf_url        = get_post_meta( get_the_ID(), '_pdf_download_url', true );
                $online_read_url = get_post_meta( get_the_ID(), '_online_read_url', true );
                $issue_list     = get_post_meta( get_the_ID(), '_issue_contents', true );
                $pub_date       = get_the_date( 'F Y' );
            ?>
            <!-- চলতি সংখ্যা -->
            <div class="current-issue">
                <h3><a href="<?php the_permalink(); ?>">📖 <?php esc_html_e( 'চলতি সংখ্যা', 'hidayah' ); ?></a></h3>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo esc_html( $pub_date ); ?></p>

                <?php if ( $issue_list ) : ?>
                <div class="issue-content">
                    <h5>📝 <?php esc_html_e( 'এই সংখ্যায়:', 'hidayah' ); ?></h5>
                    <ul class="issue-list">
                        <?php
                        $items = explode( "\n", $issue_list );
                        foreach ( $items as $item ) :
                            if ( trim( $item ) ) :
                        ?>
                            <li><?php echo esc_html( trim( $item ) ); ?></li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="issue-buttons">
                    <?php if ( $pdf_url ) : ?>
                    <a href="<?php echo esc_url( $pdf_url ); ?>" class="btn btn-sm" download>
                        📥 <?php esc_html_e( 'PDF ডাউনলোড →', 'hidayah' ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $online_read_url ) : ?>
                    <a href="<?php echo esc_url( $online_read_url ); ?>" class="btn btn-outline btn-sm">
                        📖 <?php esc_html_e( 'অনলাইনে পড়ুন →', 'hidayah' ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php wp_reset_postdata(); endif; ?>

            <?php
            // বিশেষ সংখ্যা (meta দিয়ে চিহ্নিত)
            $special_issue = new WP_Query( array(
                'post_type'      => 'monthly_hd',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'meta_key'       => '_is_special_issue',
                'meta_value'     => '1',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $special_issue->have_posts() ) :
                $special_issue->the_post();
                $pdf_url2       = get_post_meta( get_the_ID(), '_pdf_download_url', true );
                $online_url2    = get_post_meta( get_the_ID(), '_online_read_url', true );
                $issue_list2    = get_post_meta( get_the_ID(), '_issue_contents', true );
            ?>
            <!-- বিশেষ সংখ্যা -->
            <div class="special-issue">
                <h3><a href="<?php the_permalink(); ?>">⭐ <?php esc_html_e( 'বিশেষ সংখ্যা', 'hidayah' ); ?></a></h3>
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo esc_html( get_the_date( 'F Y' ) ); ?></p>

                <?php if ( $issue_list2 ) : ?>
                <div class="issue-content">
                    <h5>📚 <?php esc_html_e( 'বিশেষ বিষয়সমূহ:', 'hidayah' ); ?></h5>
                    <ul class="issue-list">
                        <?php
                        $items2 = explode( "\n", $issue_list2 );
                        foreach ( $items2 as $item2 ) :
                            if ( trim( $item2 ) ) :
                        ?>
                            <li><?php echo esc_html( trim( $item2 ) ); ?></li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="issue-buttons">
                    <?php if ( $pdf_url2 ) : ?>
                    <a href="<?php echo esc_url( $pdf_url2 ); ?>" class="btn btn-white btn-sm" download>
                        📥 <?php esc_html_e( 'PDF ডাউনলোড →', 'hidayah' ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $online_url2 ) : ?>
                    <a href="<?php echo esc_url( $online_url2 ); ?>" class="btn btn-white-outline btn-sm">
                        📖 <?php esc_html_e( 'অনলাইনে পড়ুন →', 'hidayah' ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php wp_reset_postdata(); endif; ?>

        </div><!-- .publications-grid -->

        <div class="text-center mt-35">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'monthly_hd' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'আরও দেখুন →', 'hidayah' ); ?>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     সর্বশেষ প্রবন্ধসমূহ
     ══════════════════════════════════════════ -->
<section class="articles-section">
    <div class="container">
        <div class="section-title text-center"><?php esc_html_e( 'সর্বশেষ প্রবন্ধসমূহ', 'hidayah' ); ?></div>

        <div class="articles-grid">
            <?php
            $articles_query = new WP_Query( array(
                'post_type'      => 'probondho',
                'posts_per_page' => 4,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );

            if ( $articles_query->have_posts() ) :
                while ( $articles_query->have_posts() ) :
                    $articles_query->the_post();
                    $authors = get_the_term_list( get_the_ID(), 'book_author', '', ', ', '' );
                    $thumb   = get_the_post_thumbnail_url( null, 'medium' );
            ?>
            <div class="card">
                <?php if ( $thumb ) : ?>
                <a href="<?php the_permalink(); ?>" class="article-img-link">
                    <img src="<?php echo esc_url( $thumb ); ?>"
                         alt="<?php the_title_attribute(); ?>" loading="lazy" />
                </a>
                <?php endif; ?>
                <div class="content">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <p><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
                    <?php if ( $authors ) : ?>
                    <div class="meta">
                        <span>✍️ <?php echo wp_kses_post( $authors ); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="text-center mt-35">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'probondho' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'সকল প্রবন্ধ দেখুন →', 'hidayah' ); ?>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     দরবার শরীফ ও শিক্ষা (Promo Banner)
     ══════════════════════════════════════════ -->
<section class="promo-wide-section">
    <div class="container promo-wide-wrapper promo-wide-gradient">
        <div class="promo-wide-content">
            <h3><a href="<?php echo esc_url( home_url( '/darbar-sharif' ) ); ?>">
                <?php esc_html_e( 'দরবার শরীফ ও শিক্ষা কার্যক্রম', 'hidayah' ); ?>
            </a></h3>
            <p><?php echo esc_html( get_theme_mod( 'promo_text', 'তরবিয়ত, তাযকিয়া ও আত্মশুদ্ধির মাধ্যমে প্রকৃত মুসলিম হিসেবে গড়ে ওঠার প্রশিক্ষণ কেন্দ্র এবং শিশু ও কিশোরদের জন্য কুরআন, হাদীস ও ইসলামী শিক্ষার বিশেষ ব্যবস্থা।' ) ); ?></p>
            <div class="flex-center-wrap">
                <a href="<?php echo esc_url( home_url( '/darbar-sharif' ) ); ?>" class="btn btn-white">
                    <?php esc_html_e( 'দরবার শরীফ →', 'hidayah' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/education' ) ); ?>" class="btn btn-white-outline">
                    <?php esc_html_e( 'মাদ্রাসা কার্যক্রম →', 'hidayah' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     দ্বীনি জিজ্ঞাসা সেকশন
     ══════════════════════════════════════════ -->
<section class="qa-section">
    <div class="container">
        <div class="section-title text-center"><?php esc_html_e( 'দ্বীনি জিজ্ঞাসা', 'hidayah' ); ?></div>
        <p class="section-intro text-center">
            <?php esc_html_e( 'কুরআন সুন্নাহ সম্মত অকাট্য দলিল ভিত্তিক উত্তর', 'hidayah' ); ?>
        </p>

        <div class="book-slider-wrapper">
            <button class="book-slider-btn book-slider-prev">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>

            <div class="qa-slider-grid">
                <?php
                $qa_query = new WP_Query( array(
                    'post_type'      => 'dini_jiggasa',
                    'posts_per_page' => 10,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $qa_query->have_posts() ) :
                    while ( $qa_query->have_posts() ) :
                        $qa_query->the_post();
                        $topic    = get_the_term_list( get_the_ID(), 'topic', '📚 ', ', ', '' );
                        $views    = get_post_meta( get_the_ID(), '_post_views_count', true );
                ?>
                <div class="card">
                    <div class="content">
                        <h4><a href="<?php the_permalink(); ?>">❓ <?php the_title(); ?></a></h4>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 12, '...' ); ?></p>
                        <div class="meta">
                            <?php if ( $topic ) : ?>
                            <span><?php echo wp_kses_post( $topic ); ?></span> •
                            <?php endif; ?>
                            <?php if ( $views ) : ?>
                            <span>👁️ <?php echo esc_html( hidayah_en_to_bn_number( $views ) ); ?> বার পঠিত</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div><!-- .qa-slider-grid -->

            <button class="book-slider-btn book-slider-next">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>

        <div class="text-center mt-35 mb-30 qa-action-buttons">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'dini_jiggasa' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'সকল প্রশ্নোত্তর দেখুন →', 'hidayah' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/question-form' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'লিখিত প্রশ্ন করুন →', 'hidayah' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'দোয়া চাইতে মেসেজ করুন →', 'hidayah' ); ?>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════
     যোগাযোগ CTA সেকশন
     ══════════════════════════════════════════ -->


<?php get_footer(); ?>
