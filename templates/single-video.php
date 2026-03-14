<?php
/**
 * Single Video Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    // Update view count
    $views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    update_post_meta( get_the_ID(), '_post_views_count', $views + 1 );

    $video_id  = get_post_meta( get_the_ID(), '_youtube_video_id', true );
    $duration  = get_post_meta( get_the_ID(), '_video_duration', true );
    $location  = get_post_meta( get_the_ID(), '_video_location', true );
    $speakers  = get_the_terms( get_the_ID(), 'speaker' );
    $topics    = get_the_terms( get_the_ID(), 'topic' );
?>

    <section class="archive-hero single-video-hero">
      <div class="archive-hero-content">
        <h2><?php _e( 'ভিডিও ওয়াজ ও বয়ান', 'hidayah' ); ?></h2>
        <p><?php _e( 'হক্বানী আলেমদের ওয়াজ, বয়ান ও মাহফিলের ভিডিও দেখুন', 'hidayah' ); ?></p>
      </div>
    </section>

    <section class="archive-section single-video-section">
      <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <a href="<?php echo get_post_type_archive_link( 'video' ); ?>"><?php _e( 'ভিডিও', 'hidayah' ); ?></a>
          <?php if ( ! empty( $topics ) ) : ?>
              <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
              <a href="<?php echo get_term_link( $topics[0] ); ?>"><?php echo esc_html( $topics[0]->name ); ?></a>
          <?php endif; ?>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="single-video-layout">
          <!-- LEFT COLUMN -->
          <div class="single-video-main">
            <div class="col-inner">
              <!-- Video Player -->
              <div class="single-video-player-wrap">
                <div class="live-video-wrapper video-inplace" data-video-id="<?php echo esc_attr( $video_id ); ?>">
                  <div class="live-video-cover">
                    <img alt="<?php the_title_attribute(); ?>" class="live-video-thumb" src="<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg"; ?>" />
                    <div class="live-video-overlay"></div>
                  </div>
                  <div class="live-play-btn-overlay">
                    <button class="live-play-btn">
                      <span class="material-symbols-outlined live-icon-filled">play_arrow</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Video Info Bar -->
              <div class="single-video-info-bar">
                <div class="single-video-title-row">
                  <div class="title-badge-wrapper" style="margin-bottom: 0">
                    <h1 class="single-video-title" style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0;"><?php the_title(); ?></h1>
                    <?php if ( ! empty( $topics ) ) : ?>
                        <div class="video-badge">
                          <span class="material-symbols-outlined">category</span>
                          <?php echo esc_html( $topics[0]->name ); ?>
                        </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="video-meta-details single-video-meta-details">
                  <?php if ( ! empty( $speakers ) ) : ?>
                      <span class="video-meta-item">
                        <span class="material-symbols-outlined icon-xs">person</span>
                        <a href="<?php echo get_term_link( $speakers[0] ); ?>"><?php echo esc_html( $speakers[0]->name ); ?></a>
                      </span>
                  <?php endif; ?>
                  <?php if ( $location ) : ?>
                      <span class="video-meta-item">
                        <span class="material-symbols-outlined icon-xs">location_on</span>
                        <?php echo esc_html( $location ); ?>
                      </span>
                  <?php endif; ?>
                  <span class="video-meta-item">
                    <span class="material-symbols-outlined icon-xs">calendar_today</span>
                    <?php echo get_the_date(); ?>
                  </span>
                </div>
              </div>

              <!-- Meta Strip -->
              <div class="single-video-meta-strip">
                <span class="sv-meta-chip">
                  <span class="material-symbols-outlined">visibility</span>
                  <?php printf( __( '%s ভিউ', 'hidayah' ), hidayah_en_to_bn_number( number_format_i18n( $views + 1 ) ) ); ?>
                </span>
                <?php if ( $duration ) : ?>
                <span class="sv-meta-chip">
                  <span class="material-symbols-outlined">schedule</span>
                  <?php echo hidayah_en_to_bn_number( $duration ); ?>
                </span>
                <?php endif; ?>
                <span class="sv-meta-chip">
                  <span class="material-symbols-outlined">upload</span>
                  <?php echo get_the_date(); ?>
                </span>
              </div>

              <!-- Share buttons -->
              <div class="single-audio-share-wrap">
                <h3 class="section-heading-sm">
                  <span class="material-symbols-outlined">share</span>
                  <?php _e( 'শেয়ার করুন', 'hidayah' ); ?>
                </h3>
                <div class="main-share-buttons">
                  <a class="share-btn share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                    <span class="material-symbols-outlined">thumb_up</span>
                    Facebook
                  </a>
                  <a class="share-btn share-whatsapp" href="https://api.whatsapp.com/send?text=<?php the_permalink(); ?>" target="_blank">
                    <span class="material-symbols-outlined">chat</span>
                    WhatsApp
                  </a>
                  <button class="share-btn share-copy" id="copyLinkBtn" onclick="navigator.clipboard.writeText(window.location.href); alert('<?php _e( 'লিঙ্ক কপি হয়েছে!', 'hidayah' ); ?>');">
                    <span class="material-symbols-outlined">link</span>
                    <?php _e( 'লিঙ্ক কপি করুন', 'hidayah' ); ?>
                  </button>
                </div>
              </div>

              <!-- Video Content -->
              <div class="single-video-description entry-content">
                <h3 class="section-heading-sm"><?php _e( 'বয়ানের সারসংক্ষেপ', 'hidayah' ); ?></h3>
                <?php the_content(); ?>
              </div>

              <!-- Tags -->
              <?php if ( has_tag() ) : ?>
                  <div class="single-audio-tags">
                    <h3 class="section-heading-sm"><?php _e( 'সম্পর্কিত বিষয়', 'hidayah' ); ?></h3>
                    <div class="sidebar-tags">
                      <?php the_tags('', '', ''); ?>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- Prev / Next Navigation -->
              <div class="single-audio-nav">
                <?php
                $prev_post = get_previous_post();
                if ( ! empty( $prev_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                      <span class="material-symbols-outlined">arrow_back</span>
                      <div class="single-audio-nav-text">
                        <span class="nav-label"><?php _e( 'পূর্ববর্তী ভিডিও', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $prev_post->ID ); ?></span>
                      </div>
                    </a>
                <?php endif; ?>

                <?php
                $next_post = get_next_post();
                if ( ! empty( $next_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                      <div class="single-audio-nav-text text-right">
                        <span class="nav-label"><?php _e( 'পরবর্তী ভিডিও', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $next_post->ID ); ?></span>
                      </div>
                      <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                <?php endif; ?>
              </div>

              <!-- Related Videos Grid -->
              <?php if ( ! empty( $topics ) ) : ?>
                  <div class="single-video-related">
                    <h3 class="section-heading-sm"><?php _e( 'সম্পর্কিত ভিডিও', 'hidayah' ); ?></h3>
                    <div class="related-video-grid-cards">
                      <?php
                      $related = new WP_Query( array(
                          'post_type'      => 'video',
                          'posts_per_page' => 4,
                          'post__not_in'   => array( get_the_ID() ),
                          'tax_query'      => array(
                              array(
                                  'taxonomy' => 'topic',
                                  'field'    => 'term_id',
                                  'terms'    => $topics[0]->term_id,
                              ),
                          ),
                      ) );
                      while ( $related->have_posts() ) : $related->the_post(); 
                        $rel_vid_id = get_post_meta( get_the_ID(), '_youtube_video_id', true );
                        $rel_views  = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                        $rel_dur    = get_post_meta( get_the_ID(), '_video_duration', true );
                        $rel_speakers = get_the_terms( get_the_ID(), 'speaker' );
                      ?>
                          <div class="related-vid-card">
                            <div class="related-vid-thumb">
                              <div class="sidebar-thumb-wrapper video-thumb" data-video-id="<?php echo esc_attr($rel_vid_id); ?>">
                                <img alt="<?php the_title_attribute(); ?>" src="<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : "https://img.youtube.com/vi/{$rel_vid_id}/mqdefault.jpg"; ?>" />
                                <div class="play-overlay">
                                  <span class="material-symbols-outlined play-icon-lg">play_circle</span>
                                </div>
                                <?php if ($rel_dur) : ?>
                                    <span class="video-duration-badge"><?php echo hidayah_en_to_bn_number($rel_dur); ?></span>
                                <?php endif; ?>
                              </div>
                            </div>
                            <div class="related-vid-info">
                              <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                              <?php if ( ! empty( $rel_speakers ) ) : ?>
                                  <p class="archive-video-speaker">
                                    <span class="material-symbols-outlined">person</span>
                                    <a href="<?php echo get_term_link($rel_speakers[0]); ?>"><?php echo esc_html($rel_speakers[0]->name); ?></a>
                                  </p>
                              <?php endif; ?>
                              <div class="archive-video-meta">
                                <span>
                                  <span class="material-symbols-outlined">visibility</span>
                                  <?php echo hidayah_en_to_bn_number(number_format_i18n($rel_views)); ?>
                                </span>
                              </div>
                            </div>
                          </div>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- Comments Section -->
              <?php
              if ( comments_open() || get_comments_number() ) :
                  comments_template();
              endif;
              ?>
            </div>
          </div>

          <!-- RIGHT SIDEBAR -->
          <aside class="single-video-sidebar">
            <div class="col-inner">
              <!-- Speaker Info Card -->
              <?php if ( ! empty( $speakers ) ) : 
                  $speaker = $speakers[0];
              ?>
                  <div class="sidebar-widget single-speaker-card">
                    <div class="speaker-avatar">
                      <span class="material-symbols-outlined">person</span>
                    </div>
                    <div class="speaker-details">
                      <h4 class="speaker-name"><?php echo esc_html($speaker->name); ?></h4>
                      <p class="speaker-role"><?php echo esc_html($speaker->description); ?></p>
                      <div class="speaker-stats">
                        <span>
                          <span class="material-symbols-outlined">videocam</span>
                          <?php printf( __( 'মোট %sটি ভিডিও', 'hidayah' ), hidayah_en_to_bn_number($speaker->count) ); ?>
                        </span>
                      </div>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- Playlist / Series -->
              <?php if ( ! empty( $topics ) ) : ?>
                  <div class="sidebar-widget sidebar-playlist">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">playlist_play</span>
                      <?php printf( __( 'প্লেলিস্ট — %s', 'hidayah' ), esc_html($topics[0]->name) ); ?>
                    </h4>
                    <ul class="sidebar-playlist-list">
                      <?php
                      $series = new WP_Query( array(
                          'post_type'      => 'video',
                          'posts_per_page' => 8,
                          'tax_query'      => array(
                              array(
                                  'taxonomy' => 'topic',
                                  'field'    => 'term_id',
                                  'terms'    => $topics[0]->term_id,
                              ),
                          ),
                      ) );
                      $count = 1;
                      while ( $series->have_posts() ) : $series->the_post(); 
                        $s_dur = get_post_meta( get_the_ID(), '_video_duration', true );
                        $is_current = ( get_the_ID() === get_queried_object_id() );
                      ?>
                        <li class="playlist-item <?php echo $is_current ? 'active-playlist-item' : ''; ?>" onclick="window.location.href='<?php the_permalink(); ?>'">
                          <span class="playlist-num"><?php echo hidayah_en_to_bn_number($count++); ?></span>
                          <div class="playlist-info">
                            <span class="playlist-title"><?php the_title(); ?></span>
                            <?php if ($s_dur) : ?>
                                <span class="playlist-dur"><?php echo hidayah_en_to_bn_number($s_dur); ?></span>
                            <?php endif; ?>
                          </div>
                          <?php if ($is_current) : ?>
                              <span class="material-symbols-outlined playlist-playing-icon">play_arrow</span>
                          <?php else : ?>
                              <span class="material-symbols-outlined playlist-play-icon">play_circle</span>
                          <?php endif; ?>
                        </li>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                  </div>
              <?php endif; ?>

              <!-- Related Videos List -->
              <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                  <span class="material-symbols-outlined">videocam</span>
                  <?php _e( 'সম্পর্কিত ভিডিও', 'hidayah' ); ?>
                </h4>
                <?php
                $related_list = new WP_Query( array(
                    'post_type'      => 'video',
                    'posts_per_page' => 5,
                    'post__not_in'   => array( get_the_ID() ),
                ) );
                while ( $related_list->have_posts() ) : $related_list->the_post(); 
                  $r_vid_id = get_post_meta( get_the_ID(), '_youtube_video_id', true );
                  $r_views  = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                ?>
                  <div class="sidebar-video-card">
                    <div class="sidebar-thumb-wrapper video-thumb" data-video-id="<?php echo esc_attr($r_vid_id); ?>">
                      <img alt="<?php the_title_attribute(); ?>" src="<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : "https://img.youtube.com/vi/{$r_vid_id}/mqdefault.jpg"; ?>" />
                      <div class="play-overlay">
                        <span class="material-symbols-outlined play-icon-lg">play_circle</span>
                      </div>
                    </div>
                    <div class="sidebar-card-content">
                      <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                      <div class="sidebar-meta">
                        <span><?php echo get_the_date(); ?></span>
                        •
                        <span><?php printf( __( '%s ভিউ', 'hidayah' ), hidayah_en_to_bn_number( number_format_i18n( $r_views ) ) ); ?></span>
                      </div>
                    </div>
                  </div>
                <?php endwhile; wp_reset_postdata(); ?>
              </div>

              <!-- YouTube Card -->
              <div class="sidebar-widget sidebar-youtube-card">
                <div class="youtube-card-icon">
                  <span class="material-symbols-outlined">play_circle</span>
                </div>
                <h4><?php _e( 'ইউটিউব চ্যানেল', 'hidayah' ); ?></h4>
                <p><?php echo h_opt('youtube_desc', 'আমাদের অফিসিয়াল ইউটিউব চ্যানেলে সাবস্ক্রাইব করুন এবং নতুন ভিডিও সবার আগে পান।'); ?></p>
                <div class="youtube-subscriber-count">
                  <span class="material-symbols-outlined">group</span>
                  <strong><?php echo h_opt('youtube_subs', '৫০,০০০+'); ?></strong>
                  <?php _e( 'সাবস্ক্রাইবার', 'hidayah' ); ?>
                </div>
                <a class="youtube-subscribe-btn" href="<?php echo esc_url(h_opt('social_youtube', '#')); ?>" rel="noopener" target="_blank">
                  <span class="material-symbols-outlined">play_circle</span>
                  <?php _e( 'সাবস্ক্রাইব করুন', 'hidayah' ); ?>
                </a>
              </div>

              <!-- All Videos CTA -->
              <div class="sidebar-widget sidebar-cta-widget">
                <a class="sidebar-all-audios-btn" href="<?php echo get_post_type_archive_link( 'video' ); ?>">
                  <span class="material-symbols-outlined">videocam</span>
                  <?php _e( 'সব ভিডিও দেখুন', 'hidayah' ); ?>
                  <span class="material-symbols-outlined">arrow_forward</span>
                </a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>

<?php endwhile; ?>

<?php get_footer(); ?>
