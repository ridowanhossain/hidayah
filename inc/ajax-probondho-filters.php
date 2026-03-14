<?php
/**
 * AJAX Probondho Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_probondho_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort   = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $cat    = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
    $paged  = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'probondho',
        'posts_per_page' => 12,
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

    if ( $cat ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'probondho_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ),
        );
    }

    $art_query = new WP_Query( $args );

    ob_start();
    if ( $art_query->have_posts() ) :
        while ( $art_query->have_posts() ) : $art_query->the_post();
            hidayah_render_probondho_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'দুঃখিত, কোনো প্রবন্ধ পাওয়া যায়নি।', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $art_query );
    echo '</div>';
    echo '<div class="ajax-count-data" style="display:none;">' . hidayah_en_to_bn_number( $art_query->found_posts ) . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => hidayah_en_to_bn_number( $art_query->found_posts ),
    ) );
}
add_action( 'wp_ajax_filter_probondho', 'hidayah_filter_probondho_callback' );
add_action( 'wp_ajax_nopriv_filter_probondho', 'hidayah_filter_probondho_callback' );

if ( ! function_exists( 'hidayah_render_probondho_card' ) ) {
    function hidayah_render_probondho_card() {
        $read_time = get_post_meta( get_the_ID(), '_reading_time', true ) ?: '৫';
        $cats      = get_the_terms( get_the_ID(), 'probondho_cat' );
        $cat_name  = ! empty( $cats ) ? $cats[0]->name : __( 'সাধারণ', 'hidayah' );
        ?>
        <article class="probondho-card">
            <div class="probondho-card-img">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/article-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
            </div>
            <div class="probondho-card-content">
                <span class="probondho-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="probondho-meta-strip">
                    <span>
                        <span class="material-symbols-outlined">person</span>
                        <?php the_author(); ?>
                    </span>
                    <span>
                        <span class="material-symbols-outlined">calendar_month</span>
                        <?php echo get_the_date(); ?>
                    </span>
                    <span>
                        <span class="material-symbols-outlined">schedule</span>
                        <?php printf( __( '%s মিনিট', 'hidayah' ), hidayah_en_to_bn_number( $read_time ) ); ?>
                    </span>
                </div>
                <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                <a class="probondho-read-more" href="<?php the_permalink(); ?>">
                    <?php _e( 'বিস্তারিত পড়ুন', 'hidayah' ); ?>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </article>
        <?php
    }
}
