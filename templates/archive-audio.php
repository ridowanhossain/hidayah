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
                            <form role="search" method="get" id="audioSearchForm" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%; align-items: center;">
                                <span class="material-symbols-outlined">search</span>
                                <input class="archive-search-input" id="audioSearchInput" placeholder="<?php _e( 'অডিও খুঁজুন...', 'hidayah' ); ?>" type="text" name="s" value="<?php echo esc_attr($s); ?>" />
                                <input type="hidden" name="post_type" value="audio" />
                            </form>
                        </div>
                        <div class="archive-toolbar-right">
                            <select class="archive-sort-select" id="audioSortSelect">
                                <option value="newest" <?php selected($orderby, 'newest'); ?>><?php _e( 'নতুন প্রথমে', 'hidayah' ); ?></option>
                                <option value="oldest" <?php selected($orderby, 'oldest'); ?>><?php _e( 'পুরাতন প্রথমে', 'hidayah' ); ?></option>
                                <option value="popular" <?php selected($orderby, 'popular'); ?>><?php _e( 'জনপ্রিয়', 'hidayah' ); ?></option>
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

                    <!-- Audio Count & Filters -->
                    <div class="archive-filters-toolbar" style="display: flex; flex-wrap: wrap; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div class="archive-count-badge" style="margin-bottom: 0;">
                            <span class="material-symbols-outlined">headphones</span>
                            <?php printf( __( 'মোট %sটি অডিও', 'hidayah' ), hidayah_en_to_bn_number( $audio_query->found_posts ) ); ?>
                        </div>

                        <div class="archive-taxonomy-filters" style="display: flex; gap: 10px; flex-grow: 1;">
                            <!-- Speaker Filter -->
                            <select class="archive-filter-select" id="audioSpeakerFilter" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; background: #fff; min-width: 160px; outline: none; cursor: pointer;">
                                <option value=""><?php _e( 'বক্তা নির্বাচন করুন', 'hidayah' ); ?></option>
                                <?php
                                $all_speakers = get_terms( array( 'taxonomy' => 'speaker', 'hide_empty' => true ) );
                                if ( ! empty( $all_speakers ) && ! is_wp_error( $all_speakers ) ) :
                                    foreach ( $all_speakers as $spk ) :
                                        echo '<option value="' . esc_attr( $spk->term_id ) . '">' . esc_html( $spk->name ) . ' (' . hidayah_en_to_bn_number($spk->count) . ')</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>

                            <!-- Topic Filter -->
                            <select class="archive-filter-select" id="audioTopicFilter" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; background: #fff; min-width: 160px; outline: none; cursor: pointer;">
                                <option value=""><?php _e( 'বিষয় নির্বাচন করুন', 'hidayah' ); ?></option>
                                <?php
                                $all_topics = get_terms( array( 'taxonomy' => 'topic', 'hide_empty' => true ) );
                                if ( ! empty( $all_topics ) && ! is_wp_error( $all_topics ) ) :
                                    foreach ( $all_topics as $tpc ) :
                                        echo '<option value="' . esc_attr( $tpc->term_id ) . '">' . esc_html( $tpc->name ) . ' (' . hidayah_en_to_bn_number($tpc->count) . ')</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Audio Card Grid -->
                    <div class="archive-audio-grid-wrapper" style="position: relative; min-height: 200px;">
                        <div id="audioLoader" style="display:none; position: absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:100; justify-content:center; align-items:flex-start; padding-top: 100px;">
                            <div class="spinner-hidayah" style="width: 40px; height: 40px; border: 4px solid var(--primary-green-light); border-top: 4px solid var(--primary-green); border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
                        </div>
                        <div class="archive-audio-grid" id="archiveAudioGrid">
                            <?php if ( $audio_query->have_posts() ) : while ( $audio_query->have_posts() ) : $audio_query->the_post(); 
                                hidayah_render_audio_card();
                            endwhile; ?>
                            <?php else : ?>
                                <div class="col-full">
                                    <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="audioPagination">
                        <?php hidayah_pagination( $audio_query ); ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="archive-sidebar">
                <div class="col-inner">




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

<?php wp_reset_postdata(); ?>

<script>
(function() {
    var ytCards = document.querySelectorAll('.archive-audio-card[data-ytid]');
    if (!ytCards.length) return;

    var ytPlayers = {};
    var ytTimers  = {};

    function fmtTime(s) {
        s = Math.floor(s || 0);
        var m = Math.floor(s / 60), sec = s % 60;
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }

    function pauseAllYT(exceptId) {
        Object.keys(ytPlayers).forEach(function(id) {
            if (id !== exceptId && ytPlayers[id] && ytPlayers[id].pauseVideo) {
                try { ytPlayers[id].pauseVideo(); } catch(e) {}
            }
        });
    }

    function initYTPlayer(card) {
        var ytid   = card.getAttribute('data-ytid');
        var postId = card.id; // e.g. audio-64
        var iframeDiv = document.getElementById('yt-arc-' + postId.replace('audio-', ''));
        var playBtn = card.querySelector('.player-btn-main');
        var $seek   = card.querySelector('.player-seek');
        var $curr   = card.querySelector('.player-current');
        var $dur    = card.querySelector('.player-duration');

        if (!iframeDiv) return;

        if (ytPlayers[ytid]) {
            // Already created — toggle play/pause
            var state = ytPlayers[ytid].getPlayerState();
            if (state === YT.PlayerState.PLAYING) {
                ytPlayers[ytid].pauseVideo();
            } else {
                pauseAllYT(ytid);
                // Also pause any HTML5 audio players
                document.querySelectorAll('.audio-player:not([data-ytid])').forEach(function(c) {
                    var audio = c._hdAudio;
                    if (audio && !audio.paused) audio.pause();
                });
                ytPlayers[ytid].playVideo();
            }
            return;
        }

        ytPlayers[ytid] = new YT.Player(iframeDiv.id, {
            height: '1', width: '1',
            videoId: ytid,
            playerVars: { autoplay: 1, controls: 0, disablekb: 1, rel: 0, modestbranding: 1 },
            events: {
                onReady: function(e) {
                    pauseAllYT(ytid);
                    e.target.playVideo();
                },
                onStateChange: function(e) {
                    var icon = playBtn ? playBtn.querySelector('.material-symbols-outlined') : null;
                    if (e.data === YT.PlayerState.PLAYING) {
                        if (icon) icon.textContent = 'pause';
                        if (ytTimers[ytid]) clearInterval(ytTimers[ytid]);
                        ytTimers[ytid] = setInterval(function() {
                            var p    = ytPlayers[ytid];
                            var curr = p.getCurrentTime ? p.getCurrentTime() : 0;
                            var dur  = p.getDuration   ? p.getDuration()   : 0;
                            if ($seek && dur) { $seek.max = Math.floor(dur); $seek.value = Math.floor(curr); }
                            if ($curr) $curr.textContent = fmtTime(curr);
                            if ($dur)  $dur.textContent  = fmtTime(dur);
                        }, 500);
                    } else {
                        if (icon) icon.textContent = 'play_arrow';
                        if (ytTimers[ytid]) clearInterval(ytTimers[ytid]);
                    }
                }
            }
        });

        if ($seek) {
            $seek.addEventListener('input', function() {
                var p = ytPlayers[ytid];
                if (p && p.seekTo) p.seekTo(parseFloat(this.value), true);
            });
        }
    }

    ytCards.forEach(function(card) {
        var playBtn = card.querySelector('.player-btn-main');
        if (!playBtn) return;
        playBtn.addEventListener('click', function() {
            if (!window.YT || !window.YT.Player) {
                window._ytArcQueue = window._ytArcQueue || [];
                window._ytArcQueue.push(function() { initYTPlayer(card); });
                if (!window._ytApiLoading) {
                    window._ytApiLoading = true;
                    var s = document.createElement('script');
                    s.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(s);
                }
            } else {
                initYTPlayer(card);
            }
        });
    });

    // Merge with any existing onYouTubeIframeAPIReady callback
    var _prev = window.onYouTubeIframeAPIReady;
    window.onYouTubeIframeAPIReady = function() {
        if (_prev) _prev();
        var q = window._ytArcQueue || [];
        q.forEach(function(fn) { fn(); });
        window._ytArcQueue = [];
    };
})();
</script>

<?php get_footer(); ?>
