<?php
/**
 * AJAX Monthly HD Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_monthly_hd_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort   = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $year   = isset( $_POST['year'] ) ? absint( $_POST['year'] ) : 0;
    $cat    = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $paged  = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'monthly_hd',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => $sort === 'oldest' ? 'ASC' : 'DESC',
        'post_status'    => 'publish',
    );

    if ( $search ) {
        $args['s'] = $search;
    }

    $tax_query = array();
    if ( $year ) {
        $tax_query[] = array(
            'taxonomy' => 'issue_year',
            'field'    => 'term_id',
            'terms'    => $year,
        );
    }
    if ( $cat ) {
        $tax_query[] = array(
            'taxonomy' => 'issue_category',
            'field'    => 'term_id',
            'terms'    => $cat,
        );
    }
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $mag_query = new WP_Query( $args );

    ob_start();
    if ( $mag_query->have_posts() ) :
        while ( $mag_query->have_posts() ) : $mag_query->the_post();
            hidayah_render_monthly_hd_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'দুঃখিত, কোনো সংখ্যা পাওয়া যায়নি।', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $mag_query );
    echo '</div>';

    echo '<div class="ajax-count-data" style="display:none;">' . hidayah_en_to_bn_number( $mag_query->found_posts ) . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => hidayah_en_to_bn_number( $mag_query->found_posts ),
    ) );
}
add_action( 'wp_ajax_filter_monthly_hd', 'hidayah_filter_monthly_hd_callback' );
add_action( 'wp_ajax_nopriv_filter_monthly_hd', 'hidayah_filter_monthly_hd_callback' );

if ( ! function_exists( 'hidayah_render_monthly_hd_card' ) ) {
    function hidayah_render_monthly_hd_card() {
        $pages      = get_post_meta( get_the_ID(), '_magazine_pages', true );
        $pdf_url    = get_post_meta( get_the_ID(), '_pdf_file_url', true );
        $is_special = get_post_meta( get_the_ID(), '_is_special_issue', true );
        ?>
        <article class="mhd-card <?php echo $is_special ? 'mhd-special' : ''; ?>">
            <div class="mhd-cover">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mag-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
                <?php if ( $pages ) : ?>
                    <span class="mhd-page-badge"><?php printf( __( '%s পৃষ্ঠা', 'hidayah' ), hidayah_en_to_bn_number( $pages ) ); ?></span>
                <?php endif; ?>
                <?php if ( $is_special ) : ?>
                    <span class="mhd-special-badge"><?php _e( 'বিশেষ সংখ্যা', 'hidayah' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="mhd-info">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="mhd-date">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <?php echo get_the_date(); ?>
                </p>
                <div class="mhd-actions">
                    <a class="mhd-read-btn" href="<?php the_permalink(); ?>">
                        <span class="material-symbols-outlined">menu_book</span>
                        <?php _e( 'পড়ুন', 'hidayah' ); ?>
                    </a>
                    <?php if ( $pdf_url ) : ?>
                        <a class="mhd-dl-btn" href="<?php echo esc_url( $pdf_url ); ?>" download>
                            <span class="material-symbols-outlined">download</span>
                            <?php _e( 'ডাউনলোড', 'hidayah' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }
}
