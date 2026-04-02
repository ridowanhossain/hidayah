<?php
/**
 * AJAX Notice Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_notice_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search  = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort    = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $cat     = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
    $urgency = isset( $_POST['urgency'] ) ? sanitize_text_field( $_POST['urgency'] ) : '';
    $paged   = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'           => 'notice',
        'posts_per_page'      => 10,
        'paged'               => $paged,
        'orderby'             => 'date',
        'order'               => $sort === 'oldest' ? 'ASC' : 'DESC',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
    );

    $sticky = get_option( 'sticky_posts' );
    if ( ! empty( $sticky ) ) {
        $args['post__not_in'] = $sticky;
    }

    if ( $search ) {
        $args['s'] = $search;
    }

    if ( $urgency ) {
        $args['meta_query'] = array(
            array(
                'key'   => '_notice_urgency',
                'value' => $urgency,
            ),
        );
    }

    if ( $cat ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'notice_category',
                'field'    => 'term_id',
                'terms'    => $cat,
            ),
        );
    }

    $notice_query = new WP_Query( $args );

    ob_start();
    if ( $notice_query->have_posts() ) :
        while ( $notice_query->have_posts() ) : $notice_query->the_post();
            hidayah_render_notice_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'Sorry, no notices were found.', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $notice_query );
    echo '</div>';
    echo '<div class="ajax-count-data" style="display:none;">' . $notice_query->found_posts . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => $notice_query->found_posts,
    ) );
}
add_action( 'wp_ajax_filter_notice', 'hidayah_filter_notice_callback' );
add_action( 'wp_ajax_nopriv_filter_notice', 'hidayah_filter_notice_callback' );

if ( ! function_exists( 'hidayah_render_notice_card' ) ) {
    function hidayah_render_notice_card() {
        $urgency    = get_post_meta( get_the_ID(), '_notice_urgency', true ) ?: 'general';
        $attachment = get_post_meta( get_the_ID(), '_notice_attachment', true );
        $cats       = get_the_terms( get_the_ID(), 'notice_category' );
        ?>
        <article <?php post_class( "notice-card $urgency" ); ?>>
            <div class="notice-card-header">
                <span class="notice-urgency-badge <?php echo esc_attr( $urgency ); ?>">
                    <span class="material-symbols-outlined">
                        <?php echo ( $urgency === 'urgent' ) ? 'emergency' : ( ( $urgency === 'important' ) ? 'priority_high' : 'info' ); ?>
                    </span>
                    <?php
                    if ( $urgency === 'urgent' ) _e( 'Urgent', 'hidayah' );
                    elseif ( $urgency === 'important' ) _e( 'Important', 'hidayah' );
                    else _e( 'General', 'hidayah' );
                    ?>
                </span>
                <?php if ( ! empty( $cats ) ) : ?>
                    <span class="notice-cat-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                <?php endif; ?>
                <?php if ( $attachment ) : ?>
                    <span class="notice-attach-icon"><span class="material-symbols-outlined">attach_file</span></span>
                <?php endif; ?>
            </div>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

            <div class="notice-card-footer">
                <span class="notice-date">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <?php echo get_the_date(); ?>
                </span>
                <a class="notice-read-link" href="<?php the_permalink(); ?>">
                    <?php _e( 'Details', 'hidayah' ); ?>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </article>
        <?php
    }
}
