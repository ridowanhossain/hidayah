<?php
/**
 * AJAX Video Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_video_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search  = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort    = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $topic   = isset( $_POST['topic'] ) ? absint( $_POST['topic'] ) : 0;
    $speaker = isset( $_POST['speaker'] ) ? absint( $_POST['speaker'] ) : 0;
    $paged   = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'video',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    if ( $search ) {
        $args['s'] = $search;
    }

    if ( $sort === 'oldest' ) {
        $args['order'] = 'ASC';
    } elseif ( $sort === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
        $args['orderby']  = 'meta_value_num';
    }

    $tax_query = array();
    if ( $topic ) {
        $tax_query[] = array(
            'taxonomy' => 'topic',
            'field'    => 'term_id',
            'terms'    => $topic,
        );
    }
    if ( $speaker ) {
        $tax_query[] = array(
            'taxonomy' => 'speaker',
            'field'    => 'term_id',
            'terms'    => $speaker,
        );
    }
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            hidayah_render_video_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'কোনো ভিডিও পাওয়া যায়নি।', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $query );
    echo '</div>';
    echo '<div class="ajax-count-data" style="display:none;">' . hidayah_en_to_bn_number( $query->found_posts ) . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => hidayah_en_to_bn_number( $query->found_posts ),
    ) );
}
add_action( 'wp_ajax_filter_video', 'hidayah_filter_video_callback' );
add_action( 'wp_ajax_nopriv_filter_video', 'hidayah_filter_video_callback' );

if ( ! function_exists( 'hidayah_render_video_card' ) ) {
    function hidayah_render_video_card() {
        $yt_id    = get_post_meta( get_the_ID(), '_youtube_video_id', true );
        $duration = h_get_video_duration( get_the_ID() );
        $views    = get_post_meta( get_the_ID(), '_post_views_count', true ) ?: 0;
        $location = get_post_meta( get_the_ID(), '_video_location', true );
        $speakers = get_the_terms( get_the_ID(), 'speaker' );
        $topics   = get_the_terms( get_the_ID(), 'topic' );

        $thumb_url = '';
        if ( $yt_id ) {
            $thumb_url = 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg';
        }
        if ( ! $thumb_url && has_post_thumbnail() ) {
            $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
        }
        ?>
        <article class="video-card" data-video-id="<?php echo esc_attr( $yt_id ); ?>">
            <a class="video-card-thumb-link" href="<?php the_permalink(); ?>">
                <div class="video-thumb-wrapper">
                    <?php if ( $thumb_url ) : ?>
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                    <?php endif; ?>
                    <div class="video-play-overlay">
                        <span class="material-symbols-outlined">play_circle</span>
                    </div>
                    <?php if ( $duration ) : ?>
                        <span class="video-duration-badge"><?php echo esc_html( $duration ); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <div class="video-card-content">
                <?php if ( ! empty( $topics ) ) : ?>
                    <span class="video-topic-badge"><?php echo esc_html( $topics[0]->name ); ?></span>
                <?php endif; ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="video-card-meta">
                    <?php if ( ! empty( $speakers ) ) : ?>
                        <span>
                            <span class="material-symbols-outlined">person</span>
                            <?php echo esc_html( $speakers[0]->name ); ?>
                        </span>
                    <?php endif; ?>
                    <span>
                        <span class="material-symbols-outlined">calendar_month</span>
                        <?php echo get_the_date(); ?>
                    </span>
                    <?php if ( $location ) : ?>
                        <span>
                            <span class="material-symbols-outlined">location_on</span>
                            <?php echo esc_html( $location ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ( $views ) : ?>
                        <span>
                            <span class="material-symbols-outlined">visibility</span>
                            <?php echo hidayah_en_to_bn_number( number_format_i18n( $views ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }
}
