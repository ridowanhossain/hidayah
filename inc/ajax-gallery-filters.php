<?php
/**
 * AJAX Gallery Filters Handler
 *
 * @package Hidayah
 */

function hidayah_filter_gallery_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort   = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $cat    = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
    $year   = isset( $_POST['year'] ) ? absint( $_POST['year'] ) : 0;
    $paged  = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'photo_gallery',
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

    $tax_query = array();
    if ( $cat ) {
        $tax_query[] = array(
            'taxonomy' => 'gallery_cat',
            'field'    => 'term_id',
            'terms'    => $cat,
        );
    }
    if ( $year ) {
        $tax_query[] = array(
            'taxonomy' => 'gallery_year',
            'field'    => 'term_id',
            'terms'    => $year,
        );
    }
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $gal_query = new WP_Query( $args );

    ob_start();
    if ( $gal_query->have_posts() ) :
        while ( $gal_query->have_posts() ) : $gal_query->the_post();
            hidayah_render_gallery_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'Sorry, no albums were found.', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $gal_query );
    echo '</div>';
    echo '<div class="ajax-count-data" style="display:none;">' . $gal_query->found_posts . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => $gal_query->found_posts,
    ) );
}
add_action( 'wp_ajax_filter_gallery', 'hidayah_filter_gallery_callback' );
add_action( 'wp_ajax_nopriv_filter_gallery', 'hidayah_filter_gallery_callback' );

if ( ! function_exists( 'hidayah_render_gallery_card' ) ) {
    function hidayah_render_gallery_card() {
        $photos = get_post_meta( get_the_ID(), '_gallery_images', true );
        $count  = is_array( $photos ) ? count( $photos ) : 0;
        $loc    = get_post_meta( get_the_ID(), '_gallery_location', true );
        ?>
        <article class="gallery-album-card">
            <a class="gallery-album-cover-link" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium' ); ?>
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-placeholder.png" alt="" />
                <?php endif; ?>
                <span class="gallery-photo-count">
                    <span class="material-symbols-outlined">photo_camera</span>
                    <?php echo $count; ?>
                </span>
                <div class="gallery-album-hover">
                    <span class="material-symbols-outlined">open_in_full</span>
                </div>
            </a>
            <div class="gallery-album-info">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="gallery-album-meta">
                    <span>
                        <span class="material-symbols-outlined">event</span>
                        <?php echo get_the_date(); ?>
                    </span>
                    <?php if ( $loc ) : ?>
                        <span>
                            <span class="material-symbols-outlined">location_on</span>
                            <?php echo esc_html( $loc ); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php if ( is_array( $photos ) && count( $photos ) > 0 ) : ?>
                    <div class="gallery-preview-thumbs small">
                        <?php foreach ( array_slice( $photos, 0, 3 ) as $p_id ) : ?>
                            <img src="<?php echo wp_get_attachment_image_url( $p_id, 'thumbnail' ); ?>" alt="" />
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
        <?php
    }
}
