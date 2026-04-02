<?php
/**
 * Cart Page
 *
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<section class="archive-section">
    <div class="container">

        <div class="cart-page-layout">
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <div class="cart-has-items">
                    <!-- Cart Items Table -->
                    <div class="cart-table-wrap">
                        <div class="cart-table-header">
                            <span class="cart-th cart-th-product"><?php esc_html_e( 'Book', 'hidayah' ); ?></span>
                            <span class="cart-th cart-th-price"><?php esc_html_e( 'Price', 'hidayah' ); ?></span>
                            <span class="cart-th cart-th-qty"><?php esc_html_e( 'Quantity', 'hidayah' ); ?></span>
                            <span class="cart-th cart-th-sub"><?php esc_html_e( 'Subtotal', 'hidayah' ); ?></span>
                            <span class="cart-th cart-th-remove"></span>
                        </div>
                        
                        <div class="cart-table-body">
                            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                            <?php
                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                    ?>
                                    <div class="cart-table-row woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                        
                                        <!-- Product info -->
                                        <div class="cart-td cart-td-product" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                            <div class="cart-product-info">
                                                <div class="cart-product-cover">
                                                    <?php
                                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array(80, 100)), $cart_item, $cart_item_key );

                                                    if ( ! $product_permalink ) {
                                                        echo $thumbnail; // PHPCS: XSS ok.
                                                    } else {
                                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                                    }
                                                    ?>
                                                </div>
                                                <div class="cart-product-details">
                                                    <h4 class="cart-product-name">
                                                        <?php
                                                        if ( ! $product_permalink ) {
                                                            echo wp_kses_post( $product_name . '&nbsp;' );
                                                        } else {
                                                            /**
                                                             * This filter is documented above.
                                                             *
                                                             * @since 2.1.0
                                                             */
                                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                        }

                                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                        // Meta data.
                                                        echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                                        // Backorder notification.
                                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                                        }
                                                        ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="cart-td cart-td-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                            <?php
                                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="cart-td cart-td-qty" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                            <?php
                                            if ( $_product->is_sold_individually() ) {
                                                $min_quantity = 1;
                                                $max_quantity = 1;
                                            } else {
                                                $min_quantity = 0;
                                                $max_quantity = $_product->get_max_purchase_quantity();
                                            }

                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                    'input_value'  => $cart_item['quantity'],
                                                    'max_value'    => $max_quantity,
                                                    'min_value'    => $min_quantity,
                                                    'product_name' => $product_name,
                                                ),
                                                $_product,
                                                false
                                            );

                                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                            ?>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="cart-td cart-td-sub" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                            <?php
                                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?>
                                        </div>

                                        <!-- Remove -->
                                        <div class="cart-td cart-td-remove">
                                            <?php
                                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    'woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="cart-remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span class="material-symbols-outlined">close</span></a>',
                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                        /* translators: %s is the product name */
                                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), $product_name ) ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $_product->get_sku() )
                                                    ),
                                                    $cart_item_key
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <?php do_action( 'woocommerce_cart_contents' ); ?>

                            <div class="cart-actions-bar">
                                <a class="cart-continue-btn" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    <?php esc_html_e( 'Continue Shopping', 'hidayah' ); ?>
                                </a>

                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </div>
                    </div>

                    <div class="cart-bottom-grid">
                        <!-- Coupon Code -->
                        <div class="cart-coupon-box">
                            <?php if ( wc_coupons_enabled() ) { ?>
                                <h4 class="cart-box-title">
                                    <?php esc_html_e( 'Coupon Code', 'hidayah' ); ?>
                                </h4>
                                <div class="cart-box-row">
                                    <div class="cart-coupon-row">
                                        <input type="text" name="coupon_code" class="input-text cart-coupon-input" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> 
                                        <button type="submit" class="button cart-coupon-apply-btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><span class="material-symbols-outlined">confirmation_number</span> <?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                                    </div>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            <?php } ?>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>

                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>

                        <!-- Cart Totals -->
                        <?php
                            /**
                             * Cart collaterals hook.
                             *
                             * @hooked woocommerce_cross_sell_display
                             * @hooked woocommerce_cart_totals - 10
                             */
                            do_action( 'woocommerce_cart_collaterals' );
                        ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </form>

            <!-- Trust Badges -->
            <div class="cart-trust-strip">
                <div class="cart-trust-badge">
                    <span class="material-symbols-outlined">security</span>
                    <strong><?php _e( 'Secure Payment', 'hidayah' ); ?></strong>
                    <p><?php _e( 'SSL Encrypted & Safe Transaction', 'hidayah' ); ?></p>
                </div>
                <div class="cart-trust-badge">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <strong><?php _e( 'Fast Delivery', 'hidayah' ); ?></strong>
                    <p><?php _e( '1-2 days in Dhaka, 3-5 days outside', 'hidayah' ); ?></p>
                </div>
                <div class="cart-trust-badge">
                    <span class="material-symbols-outlined">undo</span>
                    <strong><?php _e( 'Easy Return', 'hidayah' ); ?></strong>
                    <p><?php _e( 'Return defective books within 7 days', 'hidayah' ); ?></p>
                </div>
            </div>

            <!-- Recently Viewed Books (Cross Sells handled by hook above or manually) -->
        </div>
    </div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
