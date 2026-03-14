<?php
/**
 * AJAX Dini Jiggasa Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_jiggasa_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort   = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $cat    = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
    $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
    $paged  = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'dini_jiggasa',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    if ( $search ) {
        $args['s'] = $search;
    }

    if ( $sort === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
        $args['orderby']  = 'meta_value_num';
    }

    if ( $status ) {
        $args['meta_query'] = array(
            array(
                'key'   => '_jiggasa_status',
                'value' => $status,
            ),
        );
    }

    if ( $cat ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'dini_jiggasa_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ),
        );
    }

    $q_query = new WP_Query( $args );

    ob_start();
    if ( $q_query->have_posts() ) :
        while ( $q_query->have_posts() ) : $q_query->the_post();
            hidayah_render_jiggasa_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'দুঃখিত, কোনো প্রশ্ন পাওয়া যায়নি।', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $q_query );
    echo '</div>';

    echo '<div class="ajax-count-data" style="display:none;">' . hidayah_en_to_bn_number( $q_query->found_posts ) . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => hidayah_en_to_bn_number( $q_query->found_posts ),
    ) );
}
add_action( 'wp_ajax_filter_jiggasa', 'hidayah_filter_jiggasa_callback' );
add_action( 'wp_ajax_nopriv_filter_jiggasa', 'hidayah_filter_jiggasa_callback' );

if ( ! function_exists( 'hidayah_render_jiggasa_card' ) ) {
    function hidayah_render_jiggasa_card() {
        $status    = get_post_meta( get_the_ID(), '_jiggasa_status', true ) ?: 'answered';
        $mufti     = get_post_meta( get_the_ID(), '_jiggasa_mufti', true );
        $asker     = get_post_meta( get_the_ID(), '_jiggasa_asker_name', true ) ?: __( 'আনোনিমাস', 'hidayah' );
        $cat_terms = get_the_terms( get_the_ID(), 'dini_jiggasa_cat' );
        $cat_name  = ! empty( $cat_terms ) ? $cat_terms[0]->name : __( 'সাধারণ', 'hidayah' );
        ?>
        <article class="jiggasa-card <?php echo esc_attr( $status ); ?>">
            <div class="jiggasa-card-header">
                <span class="jiggasa-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
                <span class="jiggasa-status <?php echo esc_attr( $status ); ?>-badge">
                    <span class="material-symbols-outlined"><?php echo $status === 'answered' ? 'check_circle' : 'schedule'; ?></span>
                    <?php echo $status === 'answered' ? __( 'উত্তরিত', 'hidayah' ) : __( 'অপেক্ষমাণ', 'hidayah' ); ?>
                </span>
            </div>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="jiggasa-meta">
                <span><span class="material-symbols-outlined">person</span><?php echo esc_html( $asker ); ?></span>
                <span><span class="material-symbols-outlined">calendar_month</span><?php echo get_the_date(); ?></span>
                <?php if ( $status === 'answered' && $mufti ) : ?>
                    <span><span class="material-symbols-outlined">support_agent</span><?php echo esc_html( $mufti ); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $status === 'answered' ) : ?>
                <a class="jiggasa-read-link" href="<?php the_permalink(); ?>">
                    <?php _e( 'উত্তর পড়ুন', 'hidayah' ); ?>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            <?php endif; ?>
        </article>
        <?php
    }
}
