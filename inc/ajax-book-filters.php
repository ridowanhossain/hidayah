<?php
/**
 * AJAX Book Filters Handler
 * Handles filtering and sorting for book archive without page reload.
 *
 * @package Hidayah
 */

function hidayah_filter_book_callback() {
    check_ajax_referer( 'hidayah_nonce', 'nonce' );

    $search   = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    $sort     = isset( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'newest';
    $genre    = isset( $_POST['genre'] ) ? absint( $_POST['genre'] ) : 0;
    $author   = isset( $_POST['author'] ) ? absint( $_POST['author'] ) : 0;
    $paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

    $args = array(
        'post_type'      => 'product',
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
    } elseif ( $sort === 'price-asc' ) {
        $args['meta_key'] = '_price';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
    } elseif ( $sort === 'price-desc' ) {
        $args['meta_key'] = '_price';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } elseif ( $sort === 'popular' ) {
        $args['meta_key'] = '_post_views_count';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    }

    $tax_query = array();
    if ( $genre ) {
        $tax_query[] = array(
            'taxonomy' => 'genre',
            'field'    => 'term_id',
            'terms'    => $genre,
        );
    }
    if ( $author ) {
        $tax_query[] = array(
            'taxonomy' => 'book_author',
            'field'    => 'term_id',
            'terms'    => $author,
        );
    }
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $book_query = new WP_Query( $args );

    ob_start();
    if ( $book_query->have_posts() ) :
        while ( $book_query->have_posts() ) : $book_query->the_post();
            hidayah_render_book_card();
        endwhile;
    else :
        echo '<div class="col-full no-results-found"><p class="no-results">' . __( 'Sorry, no books were found.', 'hidayah' ) . '</p></div>';
    endif;

    echo '<div class="ajax-pagination-data" style="display:none;">';
    hidayah_pagination( $book_query );
    echo '</div>';

    echo '<div class="ajax-count-data" style="display:none;">' . $book_query->found_posts . '</div>';

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( array(
        'html'  => $html,
        'count' => $book_query->found_posts,
    ) );
}
add_action( 'wp_ajax_filter_book', 'hidayah_filter_book_callback' );
add_action( 'wp_ajax_nopriv_filter_book', 'hidayah_filter_book_callback' );

if ( ! function_exists( 'hidayah_render_book_card' ) ) {
    function hidayah_render_book_card() {
        $product = wc_get_product( get_the_ID() );
        if ( ! $product ) return;

        $price        = $product->get_price();
        $regular_price = $product->get_regular_price();
        $badge        = get_post_meta( get_the_ID(), '_book_badge', true );
        $rating       = $product->get_average_rating() ?: 0;
        $rating_cnt   = $product->get_review_count() ?: 0;
        $stock_status = $product->get_stock_status();
        ?>
        <article class="book-archive-card">
            <a class="book-sales-link" href="<?php the_permalink(); ?>">
                <div class="book-sales-cover">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/book-placeholder.png" alt="<?php the_title_attribute(); ?>" />
                    <?php endif; ?>
                    <?php if ( $badge ) : ?>
                        <span class="book-sales-badge"><?php echo esc_html( $badge ); ?></span>
                    <?php endif; ?>
                    <?php if ( $stock_status === 'outofstock' ) : ?>
                        <span class="book-sales-badge book-badge-out"><?php _e( 'Out of stock', 'hidayah' ); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <div class="book-sales-content">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>

                <div class="book-star-rating">
                    <?php
                    for ( $i = 1; $i <= 5; $i++ ) {
                        if ( $rating >= $i ) {
                            echo '<span class="material-symbols-outlined star filled">star</span>';
                        } elseif ( $rating > ( $i - 1 ) ) {
                            echo '<span class="material-symbols-outlined star half">star_half</span>';
                        } else {
                            echo '<span class="material-symbols-outlined star empty">star_border</span>';
                        }
                    }
                    ?>
                    <span class="star-count"><?php echo sprintf( '(%s)', $rating_cnt ); ?></span>
                </div>

                <div class="book-sales-meta">
                    <div class="book-sales-pricing">
                        <?php if ( $product->is_on_sale() && $regular_price ) : ?>
                            <span class="book-sales-old-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo $regular_price; ?></span>
                        <?php endif; ?>
                        <span class="book-sales-price"><?php echo __('Tk.', 'hidayah'); ?> <?php echo $price; ?></span>
                    </div>
                    <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="book-sales-order-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php the_ID(); ?>" aria-label="<?php esc_attr_e( 'Add to cart', 'hidayah' ); ?>">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            <?php _e( 'Add to Cart', 'hidayah' ); ?>
                        </a>
                    <?php else : ?>
                        <button class="book-sales-order-btn" disabled>
                            <span class="material-symbols-outlined">inventory_2</span>
                            <?php _e( 'Out of stock', 'hidayah' ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }
}
