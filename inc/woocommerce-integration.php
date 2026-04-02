<?php
/**
 * WooCommerce Integration
 *
 * @package Hidayah
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enable WooCommerce Support
 */
function hidayah_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'hidayah_add_woocommerce_support' );

/**
 * AJAX: Update cart count badge in header
 */
function hidayah_woocommerce_header_add_to_cart_fragment( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}
	
	// Update Count Badge
	ob_start();
	?>
	<span class="cart-count" id="cartCountBadge">
		<?php echo WC()->cart->get_cart_contents_count(); ?>
	</span>
	<?php
	$fragments['#cartCountBadge'] = ob_get_clean();

	// Update Side Cart Container
	ob_start();
	?>
	<div class="cart-items-container" id="cartItemsContainer">
		<?php if ( WC()->cart->is_empty() ) : ?>
			<div class="empty-cart-msg"><?php _e( 'Your cart is currently empty.', 'hidayah' ); ?></div>
		<?php else : ?>
			<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : 
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div class="cart-item">
					<div class="cart-item-thumb">
						<?php echo $_product->get_image(array(60, 80)); ?>
					</div>
					<div class="cart-item-details">
						<h4 class="cart-item-title"><a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $_product->get_name(); ?></a></h4>
						<div class="cart-item-meta">
							<span class="cart-item-qty"><?php echo $cart_item['quantity']; ?> x</span>
							<span class="cart-item-price"><?php echo WC()->cart->get_product_price( $_product ); ?></span>
						</div>
					</div>
					<div class="cart-item-actions">
						<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="side-cart-remove cart-item-remove" aria-label="<?php esc_attr_e( 'Remove', 'hidayah' ); ?>" data-cart_item_key="<?php echo $cart_item_key; ?>" data-product_id="<?php echo $product_id; ?>">
							<span class="material-symbols-outlined">close</span>
						</a>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php
	$fragments['#cartItemsContainer'] = ob_get_clean();

	// Update Total Price Fragment
	ob_start();
	?>
	<span id="cartTotalPrice">
		<?php echo WC()->cart->get_cart_total(); ?>
	</span>
	<?php
	$fragments['#cartTotalPrice'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'hidayah_woocommerce_header_add_to_cart_fragment', 10, 1 );

/**
 * Enable AJAX Add to Cart on Single Product Page
 */
function hidayah_ajax_add_to_cart_script() {
    if ( is_product() ) {
        wp_enqueue_script( 'hidayah-ajax-add-to-cart', get_template_directory_uri() . '/assets/js/single-product-ajax-cart.js', array( 'jquery' ), '1.0', true );
        wp_localize_script( 'hidayah-ajax-add-to-cart', 'wc_single_ajax_params', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'hidayah-single-ajax-cart' )
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'hidayah_ajax_add_to_cart_script' );

/**
 * AJAX Handler for Single Product Add to Cart
 */
function hidayah_handle_ajax_add_to_cart() {
    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
    $quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id );
    $product_status = get_post_status( $product_id );

    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );
        if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
            wc_add_to_cart_message( array( $product_id => $quantity ), true );
        }
        WC_AJAX::get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
        );
        echo wp_send_json( $data );
    }
    wp_die();
}
add_action( 'wp_ajax_hidayah_ajax_add_to_cart', 'hidayah_handle_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_hidayah_ajax_add_to_cart', 'hidayah_handle_ajax_add_to_cart' );

/**
 * Recently Viewed Section for Cart Page
 */
function hidayah_cart_recently_viewed() {
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
    $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

    if ( empty( $viewed_products ) ) return;

    // Show only 3
    $viewed_products = array_slice( $viewed_products, 0, 3 );

    $args = array(
        'post_type'      => 'product',
        'post__in'       => $viewed_products,
        'posts_per_page' => 3,
        'orderby'        => 'post__in',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) : ?>
        <div class="cart-recently-viewed">
            <h3 class="cart-section-heading"><?php _e( 'Recently Viewed Books', 'hidayah' ); ?></h3>
            <div class="cart-recent-grid">
                <?php while ( $query->have_posts() ) : $query->the_post(); 
                    global $product;
                ?>
                    <article class="book-archive-card">
                        <a class="book-sales-link" href="<?php the_permalink(); ?>">
                            <div class="book-sales-cover">
                                <?php the_post_thumbnail( 'medium' ); ?>
                                <?php if ( $product->is_on_sale() ) : ?>
                                    <span class="book-sales-badge"><?php _e( 'Bestseller', 'hidayah' ); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="book-sales-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="book-star-rating">
                                <span class="material-symbols-outlined star filled">star</span>
                                <span class="material-symbols-outlined star filled">star</span>
                                <span class="material-symbols-outlined star filled">star</span>
                                <span class="material-symbols-outlined star filled">star</span>
                                <span class="material-symbols-outlined star filled">star</span>
                                <span class="star-count">(<?php echo 67; ?>)</span>
                            </div>
                            <div class="book-sales-meta">
                                <div class="book-sales-pricing">
                                    <?php if ( $product->get_regular_price() ) : ?>
                                        <span class="book-sales-old-price"><?php echo wc_price( $product->get_regular_price() ); ?></span>
                                    <?php endif; ?>
                                    <span class="book-sales-price"><?php echo $product->get_price_html(); ?></span>
                                </div>
                                <a href="?add-to-cart=<?php echo esc_attr( $product->get_id() ); ?>" class="book-sales-order-btn add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
                                    <span class="material-symbols-outlined">shopping_cart</span>
                                    <?php _e( 'Add to Cart', 'hidayah' ); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif;
}
add_action( 'woocommerce_after_cart', 'hidayah_cart_recently_viewed' );

/**
 * Rearrange Checkout Hooks
 */
function hidayah_rearrange_checkout_hooks() {
    // Remove payment from the right sidebar order review area
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
}
add_action( 'init', 'hidayah_rearrange_checkout_hooks' );
