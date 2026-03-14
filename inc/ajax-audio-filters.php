<?php
/**
 * AJAX Audio Filters Handler
 * Handles filtering and sorting for audio archive without page reload.
 */

function hidayah_filter_audio_callback() {
    $speaker_id = isset($_POST['speaker']) ? intval($_POST['speaker']) : 0;
    $topic_id   = isset($_POST['topic']) ? intval($_POST['topic']) : 0;
    $orderby    = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'newest';
    $search     = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $paged      = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $order = ($orderby === 'oldest') ? 'ASC' : 'DESC';
    $orderby_final = ($orderby === 'popular') ? 'meta_value_num' : 'date';

    $args = array(
        'post_type'      => 'audio',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => $orderby_final,
        'order'          => $order,
        'post_status'    => 'publish'
    );

    if ( ! empty( $search ) ) {
        $args['s'] = $search;
    }

    if ( $orderby === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
    }

    $tax_query = array();

    if ( $speaker_id > 0 ) {
        $tax_query[] = array(
            'taxonomy' => 'speaker',
            'field'    => 'term_id',
            'terms'    => $speaker_id,
        );
    }

    if ( $topic_id > 0 ) {
        $tax_query[] = array(
            'taxonomy' => 'topic',
            'field'    => 'term_id',
            'terms'    => $topic_id,
        );
    }

    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $audio_query = new WP_Query( $args );

    ob_start();

    if ( $audio_query->have_posts() ) :
        while ( $audio_query->have_posts() ) : $audio_query->the_post();
            hidayah_render_audio_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'দুঃখিত, কোনো অডিও পাওয়া যায়নি।', 'hidayah' ) . '</p></div>';
    endif;

    // Always append sync data even if no posts found
    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $audio_query );
    echo '</div>';
    
    echo '<div class="ajax-count-data" style="display:none;">' . hidayah_en_to_bn_number( $audio_query->found_posts ) . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => hidayah_en_to_bn_number( $audio_query->found_posts )
    ) );
}
add_action( 'wp_ajax_filter_audio', 'hidayah_filter_audio_callback' );
add_action( 'wp_ajax_nopriv_filter_audio', 'hidayah_filter_audio_callback' );

/**
 * Helper to render audio card consistently
 */
if ( ! function_exists( 'hidayah_render_audio_card' ) ) {
    function hidayah_render_audio_card() {
        $audio_url   = get_post_meta( get_the_ID(), '_audio_url', true );
        $youtube_url = get_post_meta( get_the_ID(), '_youtube_url', true );
        $duration    = get_post_meta( get_the_ID(), '_audio_duration', true );
        $views       = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
        $speakers    = get_the_terms( get_the_ID(), 'speaker' );
        $topics      = get_the_terms( get_the_ID(), 'topic' );
        $is_featured = get_post_meta( get_the_ID(), '_featured_audio', true );

        $yt_vid_id = '';
        if ( $youtube_url ) {
            preg_match( '/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $yt_m );
            $yt_vid_id = $yt_m[1] ?? '';
        }
        ?>
        <div class="audio-card audio-player archive-audio-card <?php echo ($is_featured) ? 'special-highlight' : ''; ?>"
             <?php if ( $audio_url ) : ?>data-src="<?php echo esc_url( $audio_url ); ?>"
             <?php elseif ( $yt_vid_id ) : ?>data-ytid="<?php echo esc_attr( $yt_vid_id ); ?>"
             <?php endif; ?>
             id="audio-<?php the_ID(); ?>">
            <div class="archive-card-top">
                <div class="audio-card-icon">
                    <span class="material-symbols-outlined">mic</span>
                </div>
                <div class="archive-card-info">
                    <div class="flex-item-center mb-8">
                        <?php if ($is_featured) : ?>
                            <div class="audio-badge" style="padding: 4px 10px; font-size: 10px; gap: 4px; background: rgba(6, 95, 70, 0.1); color: var(--primary-green-dark); border-radius: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 12px;">star</span>
                                <?php _e( 'বিশেষ বয়ান', 'hidayah' ); ?>
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
            <?php if ( $yt_vid_id && ! $audio_url ) : ?>
            <div style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;">
                <div id="yt-arc-<?php the_ID(); ?>"></div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
