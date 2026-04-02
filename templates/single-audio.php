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

    $audio_url    = get_post_meta( get_the_ID(), '_audio_url',      true );
    $youtube_url  = get_post_meta( get_the_ID(), '_youtube_url',   true );
    $audio_embed  = get_post_meta( get_the_ID(), '_audio_embed',   true );
    $duration     = h_get_audio_duration( get_the_ID() );
    $location     = get_post_meta( get_the_ID(), '_mahfil_location', true );
    $speaker_role = get_post_meta( get_the_ID(), '_speaker_role',    true );
    $speakers     = get_the_terms( get_the_ID(), 'speaker' );
    $topics       = get_the_terms( get_the_ID(), 'topic' );

    // Extract YouTube video ID
    $yt_video_id = '';
    if ( $youtube_url ) {
        preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $yt_m );
        $yt_video_id = $yt_m[1] ?? '';
    }
?>

    <section class="archive-hero single-audio-hero">
      <div class="archive-hero-content">
        <h2><?php _e( 'Audio Waz & Lectures', 'hidayah' ); ?></h2>
        <p><?php _e( 'Listen to waz, lectures and religious discussions from Haqqani Ulama', 'hidayah' ); ?></p>
      </div>
    </section>

    <section class="archive-section single-audio-section">
      <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <a href="<?php echo get_post_type_archive_link( 'audio' ); ?>"><?php _e( 'Audio', 'hidayah' ); ?></a>
          <?php if ( ! empty( $topics ) ) : ?>
              <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
              <a href="<?php echo get_term_link( $topics[0] ); ?>"><?php echo esc_html( $topics[0]->name ); ?></a>
          <?php endif; ?>
          <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
          <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <!-- Single 2.75 : 1.25 Layout -->
        <div class="single-audio-layout">
          <!-- ==================== LEFT COLUMN (2.75fr) ==================== -->
          <div class="single-audio-main">
            <div class="col-inner">
              <!-- 1. Full-size Audio Player -->
              <div class="single-audio-player-card">
                <div class="single-audio-cover">
                  <span class="material-symbols-outlined">graphic_eq</span>
                </div>
                <div class="single-audio-player-info">
                  <!-- Title + badge -->
                  <div class="title-badge-wrapper">
                    <h1 class="single-audio-title"><?php the_title(); ?></h1>
                    <?php if ( ! empty( $topics ) ) : ?>
                        <div class="audio-badge">
                          <span class="material-symbols-outlined">mosque</span>
                          <?php echo esc_html( $topics[0]->name ); ?>
                        </div>
                    <?php endif; ?>
                  </div>
                  <!-- Meta row -->
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
                          <?php echo $duration; ?> <?php _e( 'minutes', 'hidayah' ); ?>
                        </span>
                    <?php endif; ?>
                    <span class="single-audio-meta-item">
                      <span class="material-symbols-outlined">headphones</span>
                      <?php printf( __( '%s Listeners', 'hidayah' ), number_format_i18n( $views + 1 ) ); ?>
                    </span>
                  </div>

                  <!-- Audio Player -->
                  <?php if ( $yt_video_id && ! $audio_url ) : ?>
                    <!-- YouTube Audio Mode Player -->
                    <div class="audio-player yt-audio-mode" id="single-audio-player" data-ytid="<?php echo esc_attr( $yt_video_id ); ?>">
                      <div class="player-controls">
                        <button class="player-btn player-btn-backward" title="<?php esc_attr_e( '10 seconds back', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">replay_10</span>
                        </button>
                        <button class="player-btn player-btn-main yt-play-btn" title="<?php esc_attr_e( 'Play / Pause', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">play_arrow</span>
                        </button>
                        <button class="player-btn player-btn-forward" title="<?php esc_attr_e( '10 seconds forward', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">forward_10</span>
                        </button>
                      </div>
                      <div class="player-progress">
                        <span class="player-time player-current">0:00</span>
                        <input class="player-seek" max="0" min="0" step="1" type="range" value="0" />
                        <span class="player-time player-duration">0:00</span>
                      </div>
                      <div id="yt-audio-iframe-wrap" style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;">
                        <div id="yt-audio-iframe"></div>
                      </div>
                    </div>
                  <?php elseif ( $audio_url ) : ?>
                    <div class="audio-player" data-src="<?php echo esc_url( $audio_url ); ?>" id="single-audio-player">
                      <div class="player-controls">
                        <button class="player-btn player-btn-backward" title="<?php esc_attr_e( '10 seconds back', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">replay_10</span>
                        </button>
                        <button class="player-btn player-btn-main" title="<?php esc_attr_e( 'Play / Pause', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">play_arrow</span>
                        </button>
                        <button class="player-btn player-btn-forward" title="<?php esc_attr_e( '10 seconds forward', 'hidayah' ); ?>">
                          <span class="material-symbols-outlined">forward_10</span>
                        </button>
                      </div>
                      <div class="player-progress">
                        <span class="player-time player-current">0:00</span>
                        <input class="player-seek" max="0" min="0" step="1" type="range" value="0" />
                        <span class="player-time player-duration">0:00</span>
                      </div>
                    </div>
                  <?php else : ?>
                    <p style="color:#888; font-style:italic; padding:12px 0;"><?php _e( 'Audio for this lecture will be added soon.', 'hidayah' ); ?></p>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Share buttons -->
              <div class="single-audio-share-wrap">
                <h3 class="section-heading-sm">
                  <span class="material-symbols-outlined">share</span>
                  <?php _e( 'Share', 'hidayah' ); ?>
                </h3>
                <div class="main-share-buttons">
                    <a class="hq-share-btn hq-share-facebook"
                       href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span>Facebook</span>
                    </a>
                    <a class="hq-share-btn hq-share-whatsapp"
                       href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span>WhatsApp</span>
                    </a>
                    <button class="hq-share-btn hq-share-copy"
                            onclick="var btn=this; var orig=btn.innerHTML; navigator.clipboard.writeText(window.location.href).then(function(){ btn.innerHTML='<span class=\'material-symbols-outlined\'>check_circle</span><span><?php echo esc_js(__('Copied!', 'hidayah')); ?></span>'; btn.classList.add('copied'); setTimeout(function(){ btn.innerHTML=orig; btn.classList.remove('copied'); }, 2500); });">
                        <span class="material-symbols-outlined">link</span>
                        <span><?php _e( 'Copy Link', 'hidayah' ); ?></span>
                    </button>
                </div>
              </div>

              <!-- 2. Audio Description -->
              <?php if ( get_the_content() ) : ?>
                  <div class="single-audio-description">
                    <h3 class="section-heading-sm"><?php _e( 'Lecture Summary', 'hidayah' ); ?></h3>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- 4. Tags -->
              <?php if ( has_tag() ) : ?>
                  <div class="single-audio-tags">
                    <h3 class="section-heading-sm"><?php _e( 'Related Topics', 'hidayah' ); ?></h3>
                    <div class="sidebar-tags">
                        <?php the_tags('', '', ''); ?>
                    </div>
                  </div>
              <?php endif; ?>

              <!-- 5. Prev / Next Navigation -->
              <div class="single-audio-nav">
                <?php $prev_post = get_previous_post(); if ( ! empty( $prev_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
                      <span class="material-symbols-outlined">arrow_back</span>
                      <div class="single-audio-nav-text">
                        <span class="nav-label"><?php _e( 'Previous', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $prev_post->ID ); ?></span>
                      </div>
                    </a>
                <?php endif; ?>
                <?php $next_post = get_next_post(); if ( ! empty( $next_post ) ) : ?>
                    <a class="single-audio-nav-btn single-audio-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
                      <div class="single-audio-nav-text text-right">
                        <span class="nav-label"><?php _e( 'Next', 'hidayah' ); ?></span>
                        <span class="nav-title-sm"><?php echo get_the_title( $next_post->ID ); ?></span>
                      </div>
                      <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                <?php endif; ?>
              </div>

              <!-- 6. Related Topic Audios -->
              <?php if ( ! empty( $topics ) ) : ?>
                  <div class="single-audio-related">
                    <h3 class="section-heading-sm"><?php _e( 'Related Audio', 'hidayah' ); ?></h3>
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
                        $rel_url = get_post_meta( get_the_ID(), '_audio_url', true );
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
                                <?php echo $rel_dur; ?> <?php _e( 'minutes', 'hidayah' ); ?>
                              </span>
                              <?php endif; ?>
                              <span class="audio-card-duration">
                                <span class="material-symbols-outlined">headphones</span>
                                <?php echo number_format_i18n($rel_views); ?>
                              </span>
                            </div>
                            <div class="player-progress">
                              <button class="player-btn player-btn-main sidebar-player-btn-reset" title="<?php esc_attr_e( 'Play / Pause', 'hidayah' ); ?>">
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

              <!-- 7. Comments Section -->
              <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
            </div>
          </div>
          <!-- END LEFT COLUMN -->

          <!-- ==================== RIGHT SIDEBAR (1.25fr) ==================== -->
          <aside class="single-audio-sidebar">
            <div class="col-inner">
              <!-- Speaker Info Card -->
              <?php if ( ! empty( $speakers ) ) : 
                  $speaker = $speakers[0];
                  $speaker_avatar = get_term_meta( $speaker->term_id, '_speaker_avatar', true );
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
                      <?php if ( $speaker_role ) : ?>
                          <p class="speaker-role"><?php echo esc_html($speaker_role); ?></p>
                      <?php elseif ( $speaker->description ) : ?>
                          <p class="speaker-role"><?php echo esc_html($speaker->description); ?></p>
                      <?php endif; ?>
                      <div class="speaker-stats">
                          <span class="material-symbols-outlined">headphones</span>
                          <?php printf( __( 'Total %s Audios', 'hidayah' ), $speaker->count ); ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- This Speaker's Other Audios -->
                  <div class="sidebar-widget">
                    <h4 class="sidebar-widget-title">
                      <span class="material-symbols-outlined">mic</span>
                      <?php _e( 'Other Audios by Speaker', 'hidayah' ); ?>
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
                                <span class="sidebar-audio-meta"><?php echo $other_dur; ?> <?php _e( 'minutes', 'hidayah' ); ?></span>
                              <?php endif; ?>
                            </div>
                          </a>
                        </li>
                      <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                  </div>
              <?php endif; ?>

              <!-- Download Widget -->
              <?php if ( $audio_url ) : 
                    $dl_count = get_post_meta( get_the_ID(), '_audio_download_count', true ) ?: 0;
              ?>
              <div class="sidebar-widget">
                <h4 class="sidebar-widget-title">
                  <span class="material-symbols-outlined">download</span>
                  <?php _e( 'Download', 'hidayah' ); ?>
                </h4>
                <a class="sidebar-download-btn" download href="<?php echo esc_url($audio_url); ?>">
                  <span class="material-symbols-outlined">download</span>
                  <?php _e( 'Download MP3', 'hidayah' ); ?>
                  <?php if ($dl_count > 0) : ?>
                    <span class="download-count"><?php printf( __( '%s Downloads', 'hidayah' ), number_format_i18n($dl_count) ); ?></span>
                  <?php endif; ?>
                </a>
              </div>
              <?php endif; ?>

              <!-- All Audios CTA -->
              <div class="sidebar-widget sidebar-cta-widget">
                <a class="sidebar-all-audios-btn" href="<?php echo get_post_type_archive_link( 'audio' ); ?>">
                  <span class="material-symbols-outlined">headphones</span>
                  <?php _e( 'View All Audios', 'hidayah' ); ?>
                  <span class="material-symbols-outlined">arrow_forward</span>
                </a>
              </div>
            </div>
          </aside>
          <!-- END RIGHT SIDEBAR -->
        </div>
        <!-- END single-audio-layout -->
      </div>
    </section>
<?php endwhile; ?>

<?php if ( $yt_video_id && ! $audio_url ) : ?>
<script>
// YouTube IFrame API – Audio Mode
var ytAudioPlayer, ytSeekTimer;

function onYouTubeIframeAPIReady() {
    ytAudioPlayer = new YT.Player('yt-audio-iframe', {
        height: '1', width: '1',
        videoId: '<?php echo esc_js( $yt_video_id ); ?>',
        playerVars: { autoplay: 0, controls: 0, disablekb: 1, rel: 0, modestbranding: 1 },
        events: {
            'onReady': function(e) { initYTAudioControls(e.target); },
            'onStateChange': function(e) { onYTStateChange(e); }
        }
    });
}

function initYTAudioControls(player) {
    var $wrap  = document.querySelector('.yt-audio-mode');
    var $play  = $wrap ? $wrap.querySelector('.yt-play-btn span') : null;
    var $seek  = $wrap ? $wrap.querySelector('.player-seek') : null;
    var $curr  = $wrap ? $wrap.querySelector('.player-current') : null;
    var $dur   = $wrap ? $wrap.querySelector('.player-duration') : null;
    if (!$wrap) return;

    function fmtTime(s) {
        s = Math.floor(s || 0);
        var m = Math.floor(s / 60), sec = s % 60;
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }

    // Play/Pause button
    $wrap.querySelector('.player-btn-main').addEventListener('click', function() {
        var state = player.getPlayerState();
        if (state === YT.PlayerState.PLAYING) { player.pauseVideo(); }
        else { player.playVideo(); }
    });

    // Seek backward
    $wrap.querySelector('.player-btn-backward').addEventListener('click', function() {
        player.seekTo(Math.max(0, player.getCurrentTime() - 10), true);
    });

    // Seek forward
    $wrap.querySelector('.player-btn-forward').addEventListener('click', function() {
        player.seekTo(player.getCurrentTime() + 10, true);
    });

    // Range seek
    if ($seek) {
        $seek.addEventListener('input', function() {
            player.seekTo(parseFloat(this.value), true);
        });
    }

    // Tick timer
    ytSeekTimer = setInterval(function() {
        var dur  = player.getDuration ? player.getDuration() : 0;
        var curr = player.getCurrentTime ? player.getCurrentTime() : 0;
        if ($seek && dur) {
            $seek.max   = Math.floor(dur);
            $seek.value = Math.floor(curr);
        }
        if ($curr) $curr.textContent = fmtTime(curr);
        if ($dur)  $dur.textContent  = fmtTime(dur);
    }, 500);
}

function onYTStateChange(e) {
    var $wrap = document.querySelector('.yt-audio-mode');
    var $icon = $wrap ? $wrap.querySelector('.yt-play-btn span') : null;
    if (!$icon) return;
    if (e.data === YT.PlayerState.PLAYING) {
        $icon.textContent = 'pause';
    } else {
        $icon.textContent = 'play_arrow';
    }
}
</script>
<script src="https://www.youtube.com/iframe_api" async></script>
<?php endif; ?>

<?php get_footer(); ?>
