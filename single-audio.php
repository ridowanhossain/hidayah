<?php
/**
 * Single Audio Template
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
    // Update view count
    $views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
    update_post_meta( get_the_ID(), '_post_views_count', $views + 1 );

    $audio_url = get_post_meta( get_the_ID(), '_audio_file_url', true );
    $duration  = get_post_meta( get_the_ID(), '_audio_duration', true );
    $location  = get_post_meta( get_the_ID(), '_audio_location', true );
    $speakers  = get_the_terms( get_the_ID(), 'speaker' );
    $topics    = get_the_terms( get_the_ID(), 'topic' );
?>

    <section class="archive-hero single-audio-hero">
      <div class="archive-hero-content">
        <h2><?php _e( 'অডিও ওয়াজ ও বয়ান', 'hidayah' ); ?></h2>
        <p><?php _e( 'হক্বানী আলেমদের ওয়াজ, বয়ান ও দ্বীনি আলোচনা শুনুন', 'hidayah' ); ?></p>
      </div>
    </section>

    <section class="archive-section single-audio-section">
      <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <a href="<?php echo get_post_type_archive_link( 'audio' ); ?>"><?php _e( 'অডিও', 'hidayah' ); ?></a>
          <?php if ( ! empty( $topics ) ) : ?>
              <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
              <a href="<?php echo get_term_link( $topics[0] ); ?>"><?php echo esc_html( $topics[0]->name ); ?></a>
          <?php endif; ?>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="single-audio-layout">
          <!-- LEFT COLUMN -->
          <div class="single-audio-main">
            <div class="col-inner">
              <!-- Full-size Audio Player -->
              <div class="single-audio-player-card">
                <div class="single-audio-cover">
                  <span class="material-symbols-outlined">graphic_eq</span>
                </div>
                <div class="single-audio-player-info">
                  <div class="title-badge-wrapper">
                    <h1 class="single-audio-title" style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0;"><?php the_title(); ?></h1>
                    <?php if ( ! empty( $topics ) ) : ?>
                        <div class="audio-badge">
                          <span class="material-symbols-outlined">category</span>
                          <?php echo esc_html( $topics[0]->name ); ?>
                        </div>
                    <?php endif; ?>
                  </div>
                  <div class="single-audio-meta-row">
                    <?php if ( ! empty( $speakers ) ) : ?>
                        <span class="single-audio-meta-item">
                          <span class="material-symbols-outlined">person</span>
                          <a href="<?php echo get_term_link( $speakers[0] ); ?>"><?php echo esc_html( $speakers[0]->name ); ?></a>
                        </span>
                    <?php endif; ?>
                    <span class="single-audio-meta-item">
                      <span class="material-symbols-outlined">calendar_today</span>
                      <?php echo get_the_date(); ?>
                    </span>
                    <?php if ( $duration ) : ?>
                    <span class="single-audio-meta-item">
                      <span class="material-symbols-outlined">schedule</span>
                      <?php echo hidayah_en_to_bn_number( $duration ); ?> <?php _e( 'মিনিট', 'hidayah' ); ?>
                    </span>
                    <?php endif; ?>
                    <span class="single-audio-meta-item">
                      <span class="material-symbols-outlined">headphones</span>
                      <?php printf( __( '%s শ্রোতা', 'hidayah' ), hidayah_en_to_bn_number( number_format_i18n( $views + 1 ) ) ); ?>
                    </span>
                  </div>
                  <!-- Audio Player -->
                  <div class="audio-player" data-src="<?php echo esc_url( $audio_url ); ?>" id="single-audio-player">
                    <div class="player-controls">
                      <button class="player-btn player-btn-backward" title="<?php _e( '১০ সেকেন্ড পিছনে', 'hidayah' ); ?>">
                        <span class="material-symbols-outlined">replay_10</span>
                      </button>
                      <button class="player-btn player-btn-main" title="<?php _e( 'চালান / থামান', 'hidayah' ); ?>">
                        <span class="material-symbols-outlined">play_arrow</span>
                      </button>
                      <button class="player-btn player-btn-forward" title="<?php _e( '১০ সেকেন্ড সামনে', 'hidayah' ); ?>">
                        <span class="material-symbols-outlined">forward_10</span>
                      </button>
                    </div>
                    <div class="player-progress">
                      <span class="player-time player-current">0:00</span>
                      <input class="player-seek" max="0" min="0" step="1" type="range" value="0" />
                      <span class="player-time player-duration">0:00</span>
                    </div>
                  </div>
                </div>
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

              <!-- Audio Content -->
              <div class="single-audio-description entry-content">
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
                        <span class="nav-label"><?php _e( 'পূর্ববর্তী', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $prev_post->ID ); ?></span>
                      </div>
                    </a>
                <?php endif; ?>

                <?php
                $next_post = get_next_post();
                if ( ! empty( $next_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                      <div class="single-audio-nav-text text-right">
                        <span class="nav-label"><?php _e( 'পরবর্তী', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $next_post->ID ); ?></span>
                      </div>
                      <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                <?php endif; ?>
              </div>

              <!-- Related Topic Audios -->
              <?php if ( ! empty( $topics ) ) : ?>
                  <div class="single-audio-related">
                    <h3 class="section-heading-sm"><?php _e( 'সম্পর্কিত অডিও', 'hidayah' ); ?></h3>
                    <div class="related-audio-grid">
                      <?php
                      $related = new WP_Query( array(
                          'post_type'      => 'audio',
                          'posts_per_page' => 2,
                          'post__not_in'   => array( get_the_ID() ),
                          'tax_query'      => array(
                              array(
                                  'taxonomy' => 'topic',
                                  'field'    => 'term_id',
                                  'terms'    => $topics[0]->term_id,
                              ),
                          ),
                      ) );
                      if ( $related->have_posts() ) : while ( $related->have_posts() ) : $related->the_post(); 
                        $rel_url = get_post_meta( get_the_ID(), '_audio_file_url', true );
                        $rel_dur = get_post_meta( get_the_ID(), '_audio_duration', true );
                        $rel_views = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
                        $rel_speakers = get_the_terms( get_the_ID(), 'speaker' );
                      ?>
                          <div class="audio-card audio-player archive-audio-card related-audio-card" data-src="<?php echo esc_url($rel_url); ?>" id="rel-<?php the_ID(); ?>">
                            <div class="archive-card-top">
                              <div class="audio-card-icon">
                                <span class="material-symbols-outlined">mic</span>
                              </div>
                              <div class="archive-card-info">
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <?php if ( ! empty( $rel_speakers ) ) : ?>
                                    <p class="audio-card-speaker">
                                      <span class="material-symbols-outlined">person</span>
                                      <a href="<?php echo get_term_link($rel_speakers[0]); ?>"><?php echo esc_html($rel_speakers[0]->name); ?></a>
                                    </p>
                                <?php endif; ?>
                              </div>
                            </div>
                            <div class="archive-card-meta">
                              <?php if ($rel_dur) : ?>
                              <span class="audio-card-duration">
                                <span class="material-symbols-outlined">schedule</span>
                                <?php echo hidayah_en_to_bn_number($rel_dur); ?> <?php _e( 'মিনিট', 'hidayah' ); ?>
                              </span>
                              <?php endif; ?>
                              <span class="audio-card-duration">
                                <span class="material-symbols-outlined">headphones</span>
                                <?php echo hidayah_en_to_bn_number(number_format_i18n($rel_views)); ?>
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
                      <?php endwhile; wp_reset_postdata(); endif; ?>
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
          <aside class="single-audio-sidebar">
            <div class="col-inner">
              <!-- Speaker Info Card -->
              <?php if ( ! empty( $speakers ) ) : 
                  $speaker = $speakers[0];
                  $speaker_avatar = get_term_meta( $speaker->term_id, '_speaker_avatar', true ); // If you have this field
              ?>
                  <div class="sidebar-widget single-speaker-card">
                    <div class="speaker-avatar">
                      <?php if ($speaker_avatar) : ?>
                          <img src="<?php echo esc_url($speaker_avatar); ?>" alt="<?php echo esc_attr($speaker->name); ?>">
                      <?php else : ?>
                          <span class="material-symbols-outlined">person</span>
                      <?php endif; ?>
                    </div>
                    <div class="speaker-details">
                      <h4 class="speaker-name"><?php echo esc_html($speaker->name); ?></h4>
                      <p class="speaker-role"><?php echo esc_html($speaker->description); ?></p>
                      <div class="speaker-stats">
                        <span>
                          <span class="material-symbols-outlined">headphones</span>
                          <?php printf( __( 'মোট %sটি অডিও', 'hidayah' ), hidayah_en_to_bn_number($speaker->count) ); ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- This Speaker's Other Audios -->
                  <div class="sidebar-widget">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">mic</span>
                      <?php printf( __( 'বক্তার অন্যান্য অডিও', 'hidayah' ) ); ?>
                    </h4>
                    <ul class="sidebar-speaker-audios">
                      <?php
                      $other_audios = new WP_Query( array(
                          'post_type'      => 'audio',
                          'posts_per_page' => 5,
                          'post__not_in'   => array( get_the_ID() ),
                          'tax_query'      => array(
                              array(
                                  'taxonomy' => 'speaker',
                                  'field'    => 'term_id',
                                  'terms'    => $speaker->term_id,
                              ),
                          ),
                      ) );
                      while ( $other_audios->have_posts() ) : $other_audios->the_post(); 
                        $other_dur = get_post_meta( get_the_ID(), '_audio_duration', true );
                      ?>
                        <li>
                          <a href="<?php the_permalink(); ?>">
                            <span class="material-symbols-outlined sidebar-audio-icon">play_circle</span>
                            <div class="sidebar-audio-info">
                              <span class="sidebar-audio-title"><?php the_title(); ?></span>
                              <?php if ($other_dur) : ?>
                                <span class="sidebar-audio-meta"><?php echo hidayah_en_to_bn_number($other_dur); ?> <?php _e( 'মিনিট', 'hidayah' ); ?></span>
                              <?php endif; ?>
                            </div>
                          </a>
                        </li>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                  </div>
              <?php endif; ?>

              <!-- Series / Playlist (Audios from same topic) -->
              <?php if ( ! empty( $topics ) ) : ?>
                  <div class="sidebar-widget sidebar-playlist">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">queue_music</span>
                      <?php printf( __( 'সিরিজ — %s', 'hidayah' ), esc_html($topics[0]->name) ); ?>
                    </h4>
                    <ul class="sidebar-playlist-list">
                      <?php
                      $series = new WP_Query( array(
                          'post_type'      => 'audio',
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
                        $s_dur = get_post_meta( get_the_ID(), '_audio_duration', true );
                        $is_current = ( get_the_ID() === get_queried_object_id() );
                      ?>
                        <li class="playlist-item <?php echo $is_current ? 'active-playlist-item' : ''; ?>" onclick="window.location.href='<?php the_permalink(); ?>'">
                          <span class="playlist-num"><?php echo hidayah_en_to_bn_number($count++); ?></span>
                          <div class="playlist-info">
                            <span class="playlist-title"><?php the_title(); ?></span>
                            <?php if ($s_dur) : ?>
                                <span class="playlist-dur"><?php echo hidayah_en_to_bn_number($s_dur); ?> মি</span>
                            <?php endif; ?>
                          </div>
                          <?php if ($is_current) : ?>
                              <span class="material-symbols-outlined playlist-playing-icon">volume_up</span>
                          <?php else : ?>
                              <span class="material-symbols-outlined playlist-play-icon">play_circle</span>
                          <?php endif; ?>
                        </li>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                  </div>
              <?php endif; ?>

              <!-- Download Button -->
              <?php if ( $audio_url ) : ?>
                  <div class="sidebar-widget">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">download</span>
                      <?php _e( 'ডাউনলোড', 'hidayah' ); ?>
                    </h4>
                    <a class="sidebar-download-btn" download href="<?php echo esc_url($audio_url); ?>">
                      <span class="material-symbols-outlined">download</span>
                      <?php _e( 'MP3 ডাউনলোড করুন', 'hidayah' ); ?>
                    </a>
                  </div>
              <?php endif; ?>

              <!-- All Audios CTA -->
              <div class="sidebar-widget sidebar-cta-widget">
                <a class="sidebar-all-audios-btn" href="<?php echo get_post_type_archive_link( 'audio' ); ?>">
                  <span class="material-symbols-outlined">headphones</span>
                  <?php _e( 'সব অডিও দেখুন', 'hidayah' ); ?>
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
