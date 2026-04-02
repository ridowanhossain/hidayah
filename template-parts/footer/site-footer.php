<?php
/**
 * Template Part: Site Footer
 * Converted from index.html
 *
 * All UI strings use __() / esc_html_e() for i18n.
 * get_theme_mod() replaced with hidayah_opt() for Codestar Framework.
 *
 * @package Hidayah
 */
?>

<!-- ══════════════════════════════════════════
     Contact CTA Section
     ══════════════════════════════════════════ -->
<section class="bridge-cta-section">
    <div class="container">
        <div class="bridge-cta-shell">
            <div class="bridge-cta-copy">
                <?php if ( hidayah_opt( 'footer_cta_kicker' ) ) : ?>
                <p class="bridge-cta-kicker"><?php echo esc_html( hidayah_opt( 'footer_cta_kicker' ) ); ?></p>
                <?php endif; ?>
                <?php if ( hidayah_opt( 'footer_cta_title' ) ) : ?>
                <h3><a href="<?php echo esc_url( hidayah_opt( 'footer_cta_card1_url' ) ); ?>">
                    <?php echo esc_html( hidayah_opt( 'footer_cta_title' ) ); ?>
                </a></h3>
                <?php endif; ?>
                <?php if ( hidayah_opt( 'footer_cta_desc' ) ) : ?>
                <p><?php echo esc_html( hidayah_opt( 'footer_cta_desc' ) ); ?></p>
                <?php endif; ?>
                <div class="grand-cta-stat-row">
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat1_num' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat1_label' ) ); ?></span>
                    </div>
                    <div class="grand-stat-divider"></div>
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat2_num' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat2_label' ) ); ?></span>
                    </div>
                    <div class="grand-stat-divider"></div>
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat3_num' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat3_label' ) ); ?></span>
                    </div>
                </div>
            </div>

            <div class="bridge-cta-links">
                <?php if ( hidayah_opt( 'footer_cta_card1_url' ) ) : ?>
                <a href="<?php echo esc_url( hidayah_opt( 'footer_cta_card1_url' ) ); ?>" class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">forum</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card1_title' ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card1_desc' ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>
                <?php endif; ?>

                <?php if ( hidayah_opt( 'contact_email' ) ) : ?>
                <a href="mailto:<?php echo esc_attr( hidayah_opt( 'contact_email' ) ); ?>"
                   class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">mail</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card2_title' ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card2_desc' ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>
                <?php endif; ?>

                <?php if ( hidayah_opt( 'contact_phone_raw' ) ) : ?>
                <a href="tel:<?php echo esc_attr( hidayah_opt( 'contact_phone_raw' ) ); ?>"
                   class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">call</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card3_title' ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card3_desc' ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer" id="colophon">
    <div class="container">
        <div class="footer-grid">

            <!-- Column 1: Brand -->
            <div class="footer-col col-brand">
                <div class="footer-logo">
                    <?php
                    $use_logo   = hidayah_opt( 'footer_use_logo', false );
                    $logo_data  = hidayah_opt( 'footer_logo', array() );
                    $logo_url   = ! empty( $logo_data['url'] ) ? $logo_data['url'] : '';
                    $brand_name = hidayah_opt( 'footer_brand_name', '' );

                    if ( $use_logo && $logo_url ) : ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>"
                             alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                             class="footer-brand-logo">
                    <?php else : ?>
                        <span class="material-symbols-outlined icon">mosque</span>
                        <h2><?php echo esc_html( $brand_name ); ?></h2>
                    <?php endif; ?>
                </div>
                <p class="footer-desc">
                    <?php echo esc_html( hidayah_opt( 'footer_about_text', get_bloginfo( 'description' ) ) ); ?>
                </p>
                <div class="footer-social">
                    <?php
                    $facebook = hidayah_opt( 'social_facebook', '' );
                    $youtube  = hidayah_opt( 'social_youtube',  '' );
                    $twitter  = hidayah_opt( 'social_twitter',  '' );
                    ?>
                    <?php if ( $facebook && $facebook !== '#' ) : ?>
                    <a href="<?php echo esc_url( $facebook ); ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ( $youtube && $youtube !== '#' ) : ?>
                    <a href="<?php echo esc_url( $youtube ); ?>" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ( $twitter && $twitter !== '#' ) : ?>
                    <a href="<?php echo esc_url( $twitter ); ?>" aria-label="X (Twitter)" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.265 5.638L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <?php 
            $footer_col2_title = hidayah_opt( 'footer_col2_title', '' );
            if ( $footer_col2_title || has_nav_menu( 'footer-quick' ) ) : 
            ?>
            <div class="footer-col">
                <?php if ( $footer_col2_title ) : ?>
                    <h4 class="footer-heading"><?php echo esc_html( $footer_col2_title ); ?></h4>
                <?php endif; ?>
                <?php
                if ( has_nav_menu( 'footer-quick' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer-quick',
                        'container'      => false,
                        'menu_class'     => 'footer-links-list',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                }
                ?>
            </div>
            <?php endif; ?>

            <!-- Column 3: Categories -->
            <?php 
            $footer_col3_title = hidayah_opt( 'footer_col3_title', '' );
            if ( $footer_col3_title || has_nav_menu( 'footer-cats' ) ) : 
            ?>
            <div class="footer-col">
                <?php if ( $footer_col3_title ) : ?>
                    <h4 class="footer-heading"><?php echo esc_html( $footer_col3_title ); ?></h4>
                <?php endif; ?>
                <?php
                if ( has_nav_menu( 'footer-cats' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer-cats',
                        'container'      => false,
                        'menu_class'     => 'footer-links-list',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                } else {
                    // Fallback logic
                    $topics = get_terms( array(
                        'taxonomy'   => 'topic',
                        'number'     => 4,
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'hide_empty' => true,
                    ) );

                    if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) {
                        echo '<ul class="footer-links-list">';
                        foreach ( $topics as $topic ) {
                            echo '<li><a href="' . esc_url( get_term_link( $topic ) ) . '">' . esc_html( $topic->name ) . '</a></li>';
                        }
                        echo '</ul>';
                    }
                }
                ?>
            </div>
            <?php endif; ?>

            <!-- Column 4: Contact & Newsletter -->
            <div class="footer-col">
                <?php 
                $footer_col4_title = hidayah_opt( 'footer_col4_title', '' );
                if ( $footer_col4_title ) : ?>
                    <h4 class="footer-heading"><?php echo esc_html( $footer_col4_title ); ?></h4>
                <?php endif; ?>
                <div class="footer-contact-info">
                    <p>
                        <span class="material-symbols-outlined icon-sm">mail</span>
                        <?php echo esc_html( hidayah_opt( 'contact_email', '' ) ); ?>
                    </p>
                    <p>
                        <span class="material-symbols-outlined icon-sm">call</span>
                        <?php echo esc_html( hidayah_opt( 'contact_phone', '' ) ); ?>
                    </p>
                    <p>
                        <span class="material-symbols-outlined icon-sm">location_on</span>
                        <?php echo esc_html( hidayah_opt( 'contact_address', '' ) ); ?>
                    </p>
                </div>

                <!-- Newsletter -->
                <?php if ( hidayah_opt( 'footer_newsletter_enable', true ) ) : ?>
                <div class="footer-newsletter">
                    <input type="email"
                           placeholder="<?php esc_attr_e( 'Enter your email', 'hidayah' ); ?>"
                           id="footer-email" />
                    <button type="button" aria-label="<?php esc_attr_e( 'Subscribe', 'hidayah' ); ?>">
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- .footer-grid -->
    </div><!-- .container -->

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">
        <div class="container footer-bottom-wrap">
            <p>
                &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                <?php bloginfo( 'name' ); ?> &mdash;
                <?php echo esc_html( hidayah_opt( 'footer_copyright', '' ) ); ?>
            </p>
            <div class="footer-bottom-links">
                <?php
                $privacy_text = hidayah_opt( 'footer_privacy_text', '' );
                $privacy_url  = hidayah_opt( 'footer_privacy_url', '' );
                $terms_text   = hidayah_opt( 'footer_terms_text', '' );
                $terms_url    = hidayah_opt( 'footer_terms_url', '' );
                ?>
                <?php if ( $privacy_text && $privacy_url ) : ?>
                    <a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( $privacy_text ); ?></a>
                <?php endif; ?>
                <?php if ( $terms_text && $terms_url ) : ?>
                    <a href="<?php echo esc_url( $terms_url ); ?>"><?php echo esc_html( $terms_text ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<!-- Video Modal -->
<div id="video-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="video-container" id="video-placeholder">
            <!-- iframe injected by JS -->
        </div>
    </div>
</div>

<!-- Prayer Time Floating Widget -->
<button id="prayer-floating-btn" class="prayer-floating-btn" type="button"
        aria-controls="prayer-bottom-sheet" aria-expanded="false"
        title="<?php esc_attr_e( 'Next Prayer Time', 'hidayah' ); ?>">
    <span class="material-symbols-outlined prayer-floating-icon">schedule</span>
    <span class="prayer-floating-text">
        <span id="prayer-floating-title"><?php esc_html_e( 'Next Prayer', 'hidayah' ); ?></span>
        <span id="prayer-floating-countdown"><?php esc_html_e( 'Waiting for location...', 'hidayah' ); ?></span>
    </span>
</button>

<div id="prayer-sheet-backdrop" class="prayer-sheet-backdrop" hidden></div>

<section id="prayer-bottom-sheet" class="prayer-bottom-sheet" aria-hidden="true">
    <div class="prayer-sheet-handle"></div>
    <div class="prayer-sheet-header">
        <div>
            <h3 id="prayer-sheet-title"><?php esc_html_e( "Today's Prayer Times", 'hidayah' ); ?></h3>
            <p id="prayer-sheet-location"><?php esc_html_e( 'Location unavailable', 'hidayah' ); ?></p>
        </div>
        <button id="prayer-sheet-close" class="prayer-sheet-close" type="button"
                aria-label="<?php esc_attr_e( 'Close', 'hidayah' ); ?>">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="prayer-sheet-meta">
        <span id="prayer-sheet-date"><?php esc_html_e( 'Loading date...', 'hidayah' ); ?></span>
        <span id="prayer-sheet-next"><?php esc_html_e( 'Updating next prayer...', 'hidayah' ); ?></span>
    </div>
    <ul id="prayer-times-list" class="prayer-times-list"></ul>
</section>

<!-- Cart Modal Overlay (Side Drawer) -->
<div class="cart-modal-overlay" id="cartModalOverlay">
    <div class="cart-modal-content" id="cartModalContent">
        <div class="cart-modal-header">
            <h2>🛒 <?php _e( 'Shopping Cart', 'hidayah' ); ?></h2>
            <button class="cart-close-btn" id="cartModalCloseBtn">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="cart-items-container" id="cartItemsContainer">
            <?php if ( function_exists( 'WC' ) && ! WC()->cart->is_empty() ) : ?>
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
                                <span class="cart-item-qty"><?php echo intval($cart_item['quantity']); ?> x</span>
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
            <?php else : ?>
                <div class="empty-cart-msg"><?php _e( 'No books in the cart.', 'hidayah' ); ?></div>
            <?php endif; ?>
        </div>

        <div class="cart-modal-footer">
            <div class="cart-total">
                <span><?php _e( 'Total:', 'hidayah' ); ?></span>
                <span id="cartTotalPrice">
                    <?php echo ( function_exists( 'WC' ) ) ? WC()->cart->get_cart_total() : 'Tk. 0'; ?>
                </span>
            </div>
            <div class="cart-actions">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-checkout-btn secondary"><?php _e( 'View Cart', 'hidayah' ); ?></a>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-checkout-btn"><?php _e( 'Checkout', 'hidayah' ); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container"></div>
