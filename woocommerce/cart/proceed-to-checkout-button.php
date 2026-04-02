<?php
/**
 * Proceed to checkout button
 *
 * @package WooCommerce\Templates
 * @version 2.4.0
 */

defined( 'ABSPATH' ) || exit;
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward cart-checkout-btn">
    <span class="material-symbols-outlined">shopping_bag</span>
    <?php esc_html_e( 'Proceed to Checkout', 'hidayah' ); ?>
</a>
