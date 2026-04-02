<?php
/**
 * Empty cart page
 *
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' );
?>

<section class="archive-section">
    <div class="container">

        <div class="cart-empty-state">
            <div class="cart-empty-icon">
                <span class="material-symbols-outlined">shopping_cart</span>
            </div>
            <h3><?php _e( 'Your cart is currently empty', 'hidayah' ); ?></h3>
            <p><?php _e( 'No books have been added to your cart yet.', 'hidayah' ); ?></p>
            <a class="btn" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                <span class="material-symbols-outlined">library_books</span>
                <?php _e( 'Browse Books', 'hidayah' ); ?>
            </a>
        </div>
    </div>
</section>
