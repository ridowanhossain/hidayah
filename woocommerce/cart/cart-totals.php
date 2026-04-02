<?php
/**
 * Cart totals
 *
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<style id="hidayah-cart-totals-strict-css">
    /* Strictly enforce the Green Theme & Clean Shipping Layout regardless of AJAX DOM replacements */
    .cart_totals.cart-totals-card { background: #ffffff !important; border: 1px solid rgba(6, 95, 70, 0.08) !important; border-radius: 20px !important; padding: 24px !important; box-shadow: 0 10px 30px rgba(6, 95, 70, 0.04) !important; width: 100% !important; }
    .cart-totals-rows { display: flex !important; flex-direction: column !important; gap: 12px !important; margin-bottom: 24px !important; }
    .cart-totals-row { display: flex !important; justify-content: space-between !important; align-items: flex-start !important; font-size: 14px !important; color: #374151 !important; padding-bottom: 12px !important; border-bottom: 1px solid rgba(6, 95, 70, 0.05) !important; width: 100% !important; }
    .cart-totals-row:last-of-type { border-bottom: none !important; }
    .cart-totals-row > span:first-child { font-weight: 600 !important; color: #4b5563 !important; }
    
    /* Shipping row fixes */
    .cart-totals-row.shipping { flex-direction: column !important; gap: 8px !important; }
    .cart-totals-row.shipping > span:first-child { width: 100% !important; }
    .woocommerce-shipping-methods { list-style: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; }
    .woocommerce-shipping-methods li { padding: 0 !important; margin: 0 0 8px 0 !important; display: flex !important; align-items: center !important; gap: 8px !important; }
    .woocommerce-shipping-methods li:before, .woocommerce-shipping-methods li::before { display: none !important; content: none !important; }
    
    .woocommerce-shipping-destination { font-size: 13px !important; color: #6b7280 !important; margin-bottom: 6px !important; display: block !important; }
    .shipping-calculator-button { color: #065F46 !important; font-weight: 600 !important; text-decoration: none !important; font-size: 13px !important; }
    
    /* Total row fixes */
    .cart-totals-row.order-total, .cart-totals-total { background: rgba(6, 95, 70, 0.03) !important; margin-top: 10px !important; padding: 16px !important; border-radius: 12px !important; border-bottom: none !important; display: flex !important; justify-content: space-between !important; width: auto !important; }
    .cart-totals-row.order-total span:first-child, .cart-totals-total span:first-child { color: #065F46 !important; font-size: 16px !important; font-weight: 700 !important; }
    .cart-totals-row.order-total strong, .cart-totals-total strong { font-size: 20px !important; color: #065F46 !important; font-weight: 800 !important; }
    .cart-totals-row.order-total span.woocommerce-Price-amount { font-size: 20px !important; color: #065F46 !important; font-weight: 800 !important; }
    
    /* Checkout button enforcement */
    .wc-proceed-to-checkout { width: 100% !important; }
    .wc-proceed-to-checkout .checkout-button, 
    .cart_totals .checkout-button,
    a.checkout-button.button.alt.wc-forward { 
        display: flex !important; align-items: center !important; justify-content: center !important; gap: 10px !important; width: 100% !important; 
        padding: 16px !important; border-radius: 15px !important; background: #065F46 !important; color: #ffffff !important; 
        font-size: 16px !important; font-weight: 700 !important; text-decoration: none !important; box-shadow: 0 8px 25px rgba(6, 95, 70, 0.2) !important; 
        transition: all 0.3s ease !important; margin-bottom: 15px !important; border: none !important; 
    }
    .wc-proceed-to-checkout .checkout-button:hover,
    a.checkout-button.button.alt.wc-forward:hover { background: #047857 !important; transform: translateY(-2px) !important; box-shadow: 0 12px 30px rgba(6, 95, 70, 0.3) !important; }
    
    /* Apply Coupon button enforcement */
    .cart-coupon-apply-btn, button[name="apply_coupon"] { 
        background: #065F46 !important; color: #fff !important; transition: background 0.2s !important; border-radius: 8px !important; 
        padding: 0 12px !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 5px !important;
        font-weight: 600 !important; border: none !important;
    }
    .cart-coupon-apply-btn:hover, button[name="apply_coupon"]:hover { background: #047857 !important; }

    /* Shipping Calculator Modernization */
    .woocommerce-shipping-calculator { margin-top: 15px !important; padding-top: 15px !important; border-top: 1px dashed rgba(6, 95, 70, 0.15) !important; }
    .woocommerce-shipping-calculator p.form-row { margin-bottom: 12px !important; color: #4b5563 !important; font-size: 13px !important; font-family: inherit !important; clear: both !important; }
    
    .woocommerce-shipping-calculator select, 
    .woocommerce-shipping-calculator input.input-text { 
        width: 100% !important; border: 1px solid rgba(6, 95, 70, 0.2) !important; border-radius: 8px !important; 
        padding: 10px 14px !important; font-size: 14px !important; color: #374151 !important; 
        background: #fdfdfd !important; height: 42px !important; box-shadow: none !important; transition: border-color 0.2s, background 0.2s !important; outline: none !important; box-sizing: border-box !important; font-family: inherit !important;
    }
    .woocommerce-shipping-calculator select:focus, 
    .woocommerce-shipping-calculator input.input-text:focus { border-color: #065F46 !important; background: #ffffff !important; }
    
    /* Handle WooCommerce Select2 overrides if active */
    .woocommerce-shipping-calculator .select2-container--default .select2-selection--single { width: 100% !important; border: 1px solid rgba(6, 95, 70, 0.2) !important; border-radius: 8px !important; background: #fdfdfd !important; height: 42px !important; padding: 0 !important; }
    .woocommerce-shipping-calculator .select2-container .select2-selection--single .select2-selection__rendered { padding: 10px 14px !important; line-height: 20px !important; color: #374151 !important; }
    .woocommerce-shipping-calculator .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 8px !important; }
    
    .woocommerce-shipping-calculator button.button[name="calc_shipping"] { 
        background: rgba(6, 95, 70, 0.1) !important; color: #065F46 !important; border: none !important; 
        border-radius: 8px !important; padding: 10px 20px !important; font-size: 14px !important; font-weight: 700 !important; 
        cursor: pointer !important; transition: all 0.2s !important; width: 100% !important; margin-top: 5px !important; height: 42px !important;
    }
    .woocommerce-shipping-calculator button.button[name="calc_shipping"]:hover { background: #065F46 !important; color: #ffffff !important; }
</style>

<div class="cart_totals cart-totals-card <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

    <?php do_action( 'woocommerce_before_cart_totals' ); ?>


    <h4 class="cart-box-title">
        <span class="material-symbols-outlined">receipt_long</span>
        <?php esc_html_e( 'Order Summary', 'hidayah' ); ?>
    </h4>

    <div class="cart-totals-rows">
        <!-- Subtotal -->
        <div class="cart-totals-row cart-subtotal">
            <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <!-- Coupons -->
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="cart-totals-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Shipping -->
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
            <?php wc_cart_totals_shipping_html(); ?>
            <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
        <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
            <div class="cart-totals-row shipping">
                <span><?php esc_html_e( 'Shipping', 'hidayah' ); ?></span>
                <span data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></span>
            </div>
        <?php endif; ?>

        <!-- Fees -->
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="cart-totals-row fee">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Taxes -->
        <?php
        if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
            $taxable_address = WC()->customer->get_taxable_address();
            $estimated_text  = '';

            if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                /* translators: %s location. */
                $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
            }

            if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                    ?>
                    <div class="cart-totals-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <span><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <span data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="cart-totals-row tax-total">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <span data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
                <?php
            }
        }
        ?>

        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

        <!-- Total -->
        <div class="cart-totals-row cart-totals-total order-total">
            <span><?php esc_html_e( 'Total', 'hidayah' ); ?></span>
            <span data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
    </div>

    <div class="wc-proceed-to-checkout">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>

    <a class="cart-shop-more-link" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
        <span class="material-symbols-outlined">arrow_back</span>
        <?php esc_html_e( 'Continue Shopping', 'hidayah' ); ?>
    </a>

    <?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
