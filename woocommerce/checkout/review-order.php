<?php
/**
 * Review order table – Custom Design (Hidayah Theme)
 * Overrides: woocommerce/templates/checkout/review-order.php
 *
 * @package Hidayah
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_review_order_before_cart_contents' );
?>

<!-- Item List -->
<div class="co-summary-items">
    <?php
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
            ?>
            <div class="co-summary-item">
                <span class="co-summary-item-title">
                    <?php echo wp_kses_post( $product_name ); ?>&nbsp;<em>&times; <?php echo esc_html( $cart_item['quantity'] ); ?></em>
                </span>
                <span class="co-summary-item-price">
                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
            </div>
            <?php
        }
    }
    ?>
</div>

<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

<!-- Totals -->
<div class="co-summary-totals">
    <div class="co-summary-row">
        <span><?php esc_html_e( 'Subtotal', 'hidayah' ); ?></span>
        <span><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="co-summary-row co-discount-row">
            <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span class="co-discount-val"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <div class="co-summary-row">
            <span><?php echo esc_html( $fee->name ); ?></span>
            <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if ( wc_tax_enabled() && 'excl' === WC()->cart->get_tax_price_display_mode() ) : ?>
        <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                <div class="co-summary-row">
                    <span><?php echo esc_html( $tax->label ); ?></span>
                    <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="co-summary-row">
                <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                <span><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

    <div class="co-summary-row">
        <span><?php esc_html_e( 'Delivery', 'hidayah' ); ?></span>
        <span><?php wc_cart_totals_shipping_html(); ?></span>
    </div>

    <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

    <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

    <div class="co-summary-row co-summary-total">
        <span><?php esc_html_e( 'Total', 'hidayah' ); ?></span>
        <span><?php wc_cart_totals_order_total_html(); ?></span>
    </div>

    <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>
