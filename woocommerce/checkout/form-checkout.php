<?php
/**
 * Checkout Form – Custom Design (Hidayah Theme)
 * Matches hoquerdawat-html/checkout.html layout
 *
 * @package Hidayah
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<!-- ===== HERO ===== -->
<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php _e( 'Checkout', 'hidayah' ); ?></h2>
        <p><?php _e( 'Enter your delivery address and payment information to complete your order.', 'hidayah' ); ?></p>
    </div>
</section>

<!-- ===== CHECKOUT SECTION ===== -->
<section class="archive-section hd-checkout-section">
    <div class="container">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php _e( 'Books', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php _e( 'Cart', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php _e( 'Checkout', 'hidayah' ); ?></span>
        </nav>

        <!-- Checkout Steps -->
        <div class="co-steps">
            <div class="co-step done">
                <div class="co-step-icon">
                    <span class="material-symbols-outlined">shopping_cart</span>
                </div>
                <span class="co-step-label"><?php _e( 'Cart', 'hidayah' ); ?></span>
            </div>
            <div class="co-step-line done"></div>
            <div class="co-step active">
                <div class="co-step-icon">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <span class="co-step-label"><?php _e( 'Address & Payment', 'hidayah' ); ?></span>
            </div>
            <div class="co-step-line"></div>
            <div class="co-step">
                <div class="co-step-icon">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <span class="co-step-label"><?php _e( 'Complete', 'hidayah' ); ?></span>
            </div>
        </div>

        <div class="hd-co-notices-top">
            <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
        </div>

        <form name="checkout" method="post" class="checkout woocommerce-checkout hd-co-form-wrap" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <!-- ===== TWO-COLUMN CHECKOUT LAYOUT ===== -->
            <div class="hd-co-grid">

                <!-- LEFT: BILLING FORM -->
                <div class="hd-co-left">

                    <?php if ( $checkout->get_checkout_fields() ) : ?>

                        <!-- Billing Details -->
                        <div class="co-form-section">
                            <h3 class="co-section-title">
                                <span class="material-symbols-outlined">person</span>
                                <?php _e( 'Customer Information', 'hidayah' ); ?>
                            </h3>
                            <div class="hd-co-billing-fields">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>
                        </div>
                        <!-- Delivery Method (Shipping) -->
                        <div class="co-form-section">
                            <h3 class="co-section-title">
                                <span class="material-symbols-outlined">local_shipping</span>
                                <?php _e( 'Delivery Method', 'hidayah' ); ?>
                            </h3>
                            <div id="hd-co-shipping-method">
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                                <?php // Fallback if no shipping action exists or if it's empty
                                if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                                    <div class="hd-co-shipping-list">
                                        <?php wc_cart_totals_shipping_html(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>


                    <?php endif; ?>

                </div><!-- END LEFT -->

                <!-- RIGHT: ORDER SUMMARY SIDEBAR -->
                <div class="hd-co-right">

                    <!-- Order Summary Card -->
                    <div class="co-summary-card">
                        <h3 class="co-summary-title">
                            <span class="material-symbols-outlined">receipt_long</span>
                            <?php _e( 'Order Summary', 'hidayah' ); ?>
                        </h3>

                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                        
                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <!-- Custom Coupon Box (Inside Card) -->
                        <?php if ( wc_coupons_enabled() ) : ?>
                            <div class="co-sidebar-coupon-box-inner">
                                <div class="co-sidebar-coupon-fields">
                                    <input type="text" name="sidebar_coupon_code" class="input-text" id="sidebar_coupon_code" placeholder="<?php esc_attr_e( 'Coupon Code', 'hidayah' ); ?>" value="" />
                                    <button type="button" class="button alt" id="apply_sidebar_coupon"><?php _e( 'Apply', 'hidayah' ); ?></button>
                                </div>
                                <div id="sidebar_coupon_message"></div>
                            </div>
                        <?php endif; ?>

                        <!-- Payment Method (Moved to Sidebar) -->
                        <div class="co-sidebar-payment-section">
                            <h3 class="co-sidebar-title">
                                <span class="material-symbols-outlined">payments</span>
                                <?php _e( 'Payment Method', 'hidayah' ); ?>
                            </h3>
                            <div id="hd-co-sidebar-payment">
                                <?php woocommerce_checkout_payment(); ?>
                            </div>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                        <div class="co-checkout-submit-wrap">
                            <?php wc_get_template( 'checkout/terms.php' ); ?>
                            
                            <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

                            <button type="submit" class="button alt co-place-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo esc_attr( apply_filters( 'woocommerce_order_button_text', __( 'Complete Order', 'hidayah' ) ) ); ?>" data-value="<?php echo esc_attr( apply_filters( 'woocommerce_order_button_text', __( 'Complete Order', 'hidayah' ) ) ); ?>">
                                <span class="material-symbols-outlined">check_circle</span>
                                <?php echo esc_html( apply_filters( 'woocommerce_order_button_text', __( 'Complete Order', 'hidayah' ) ) ); ?>
                            </button>

                            <?php do_action( 'woocommerce_review_order_after_submit' ); ?>
                        </div>

                        <!-- Back to Cart -->
                        <a class="co-back-to-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <?php _e( 'Back to Cart', 'hidayah' ); ?>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="sidebar-widget co-trust-widget">
                        <div class="co-trust-list">
                            <div class="co-trust-item">
                                <span class="material-symbols-outlined">security</span>
                                <span><?php _e( 'Secure Payment', 'hidayah' ); ?></span>
                            </div>
                            <div class="co-trust-item">
                                <span class="material-symbols-outlined">undo</span>
                                <span><?php _e( 'Easy Return Policy', 'hidayah' ); ?></span>
                            </div>
                            <div class="co-trust-item">
                                <span class="material-symbols-outlined">support_agent</span>
                                <span><?php _e( 'Always Available Support', 'hidayah' ); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery ETA Card -->
                    <div class="sidebar-widget co-eta-card">
                        <h4 class="sidebar-widget-title">
                            <span class="material-symbols-outlined">schedule</span>
                            <?php _e( 'Estimated Delivery Time', 'hidayah' ); ?>
                        </h4>
                        <div class="co-eta-info">
                            <div class="co-eta-row">
                                <span class="material-symbols-outlined">location_city</span>
                                <div>
                                    <strong><?php _e( 'Inside Dhaka:', 'hidayah' ); ?></strong>
                                    <span><?php _e( '1–2 working days', 'hidayah' ); ?></span>
                                </div>
                            </div>
                            <div class="co-eta-row">
                                <span class="material-symbols-outlined">map</span>
                                <div>
                                    <strong><?php _e( 'Outside Dhaka:', 'hidayah' ); ?></strong>
                                    <span><?php _e( '3–5 working days', 'hidayah' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- .hd-co-right -->

            </div><!-- .hd-co-grid -->

        </form>

    </div><!-- .container -->
</section><!-- .archive-section -->

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
