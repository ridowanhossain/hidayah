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
     যোগাযোগ CTA সেকশন
     ══════════════════════════════════════════ -->
<section class="bridge-cta-section">
    <div class="container">
        <div class="bridge-cta-shell">
            <div class="bridge-cta-copy">
                <p class="bridge-cta-kicker"><?php echo esc_html( hidayah_opt( 'footer_cta_kicker', __( 'যোগাযোগ শুরু করুন', 'hidayah' ) ) ); ?></p>
                <h3><a href="<?php echo esc_url( hidayah_opt( 'footer_cta_card1_url', home_url( '/contact' ) ) ); ?>">
                    <?php echo esc_html( hidayah_opt( 'footer_cta_title', __( 'দ্বীনি জিজ্ঞাসা বা সহায়তা দরকার?', 'hidayah' ) ) ); ?>
                </a></h3>
                <p><?php echo esc_html( hidayah_opt( 'footer_cta_desc', __( 'আপনি প্রশ্ন করতে চাইলে, বিস্তারিত বার্তা পাঠাতে চাইলে বা সরাসরি কল করতে চাইলে নিচের অপশনগুলো থেকে একটি বেছে নিতে পারেন।', 'hidayah' ) ) ); ?></p>
                <div class="grand-cta-stat-row">
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat1_num', '২৪/৭' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat1_label', __( 'অনলাইন সেবা', 'hidayah' ) ) ); ?></span>
                    </div>
                    <div class="grand-stat-divider"></div>
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat2_num', '১০০+' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat2_label', __( 'দ্বীনি প্রশ্নের উত্তর', 'hidayah' ) ) ); ?></span>
                    </div>
                    <div class="grand-stat-divider"></div>
                    <div class="grand-stat">
                        <span class="grand-stat-num"><?php echo esc_html( hidayah_opt( 'footer_cta_stat3_num', '৫০০০+' ) ); ?></span>
                        <span class="grand-stat-label"><?php echo esc_html( hidayah_opt( 'footer_cta_stat3_label', __( 'সন্তুষ্ট মুসলিম', 'hidayah' ) ) ); ?></span>
                    </div>
                </div>
            </div>

            <div class="bridge-cta-links">
                <a href="<?php echo esc_url( hidayah_opt( 'footer_cta_card1_url', home_url( '/contact' ) ) ); ?>" class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">forum</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card1_title', __( 'যোগাযোগ ফর্ম', 'hidayah' ) ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card1_desc', __( 'বিস্তারিত মেসেজ পাঠানোর জন্য', 'hidayah' ) ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>

                <a href="mailto:<?php echo esc_attr( hidayah_opt( 'contact_email', 'info@hoquerdawat.com' ) ); ?>"
                   class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">mail</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card2_title', __( 'ইমেইল সাপোর্ট', 'hidayah' ) ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card2_desc', __( 'ডকুমেন্ট বা লিখিত জিজ্ঞাসার জন্য', 'hidayah' ) ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>

                <a href="tel:<?php echo esc_attr( hidayah_opt( 'contact_phone_raw', '+8801234567890' ) ); ?>"
                   class="bridge-link-card">
                    <span class="material-symbols-outlined bridge-link-icon">call</span>
                    <div>
                        <h4><?php echo esc_html( hidayah_opt( 'footer_cta_card3_title', __( 'ফোন কল', 'hidayah' ) ) ); ?></h4>
                        <p><?php echo esc_html( hidayah_opt( 'footer_cta_card3_desc', __( 'তাৎক্ষণিক সহায়তার জন্য', 'hidayah' ) ) ); ?></p>
                    </div>
                    <span class="material-symbols-outlined bridge-link-arrow">arrow_forward</span>
                </a>
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
                    <span class="material-symbols-outlined icon">mosque</span>
                    <h2><?php bloginfo( 'name' ); ?></h2>
                </div>
                <p class="footer-desc">
                    <?php echo esc_html( hidayah_opt( 'footer_about_text', get_bloginfo( 'description' ) ) ); ?>
                </p>
                <div class="footer-social">
                    <?php
                    $facebook = hidayah_opt( 'social_facebook', '#' );
                    $youtube  = hidayah_opt( 'social_youtube',  '#' );
                    $whatsapp = hidayah_opt( 'social_whatsapp', '#' );
                    ?>
                    <a href="<?php echo esc_url( $facebook ); ?>" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">social_leaderboard</span>
                    </a>
                    <a href="<?php echo esc_url( $youtube ); ?>" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">play_circle</span>
                    </a>
                    <a href="<?php echo esc_url( $whatsapp ); ?>" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">
                        <span class="material-symbols-outlined">share</span>
                    </a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-col">
                <h4 class="footer-heading"><?php esc_html_e( 'Quick Links', 'hidayah' ); ?></h4>
                <ul class="footer-links-list">
                    <li><a href="#"><?php esc_html_e( 'Live Mahfil', 'hidayah' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'audio' ) ); ?>"><?php esc_html_e( 'Media Archive', 'hidayah' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'dini_jiggasa' ) ); ?>"><?php esc_html_e( 'Islamic Q&A', 'hidayah' ); ?></a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>"><?php esc_html_e( 'Books & Publications', 'hidayah' ); ?></a></li>
                </ul>
            </div>

            <!-- Column 3: Categories (from 'topic' taxonomy) -->
            <div class="footer-col">
                <h4 class="footer-heading"><?php esc_html_e( 'Categories', 'hidayah' ); ?></h4>
                <ul class="footer-links-list">
                    <?php
                    $topics = get_terms( array(
                        'taxonomy'   => 'topic',
                        'number'     => 4,
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'hide_empty' => true,
                    ) );

                    if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) :
                        foreach ( $topics as $topic ) : ?>
                            <li>
                                <a href="<?php echo esc_url( get_term_link( $topic ) ); ?>">
                                    <?php echo esc_html( $topic->name ); ?>
                                </a>
                            </li>
                        <?php endforeach;
                    else :
                        // Fallback when taxonomy is empty
                        $fallback_cats = array(
                            __( 'Tafseerul Quran', 'hidayah' ),
                            __( 'Seeratun Nabi', 'hidayah' ),
                            __( 'Adab & Akhlaq', 'hidayah' ),
                            __( 'Fiqh & Fatwa', 'hidayah' ),
                        );
                        foreach ( $fallback_cats as $cat ) : ?>
                            <li><a href="#"><?php echo esc_html( $cat ); ?></a></li>
                        <?php endforeach;
                    endif;
                    ?>
                </ul>
            </div>

            <!-- Column 4: Contact & Newsletter -->
            <div class="footer-col">
                <h4 class="footer-heading"><?php esc_html_e( 'Contact', 'hidayah' ); ?></h4>
                <div class="footer-contact-info">
                    <p>
                        <span class="material-symbols-outlined icon-sm">mail</span>
                        <?php echo esc_html( hidayah_opt( 'contact_email', 'info@hoquerdawat.com' ) ); ?>
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
                <?php echo esc_html( hidayah_opt( 'footer_copyright', __( 'All rights reserved.', 'hidayah' ) ) ); ?>
            </p>
            <div class="footer-bottom-links">
                <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'hidayah' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'hidayah' ); ?></a>
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

<!-- Shopping Cart Modal -->
<div class="cart-modal-overlay" id="cartModalOverlay">
    <div class="cart-modal-content" id="cartModalContent">
        <div class="cart-modal-header">
            <h2><?php esc_html_e( '🛒 Shopping Cart', 'hidayah' ); ?></h2>
            <button class="cart-close-btn" id="cartModalCloseBtn">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="cart-items-container" id="cartItemsContainer">
            <div class="empty-cart-msg"><?php esc_html_e( 'Your cart is empty.', 'hidayah' ); ?></div>
        </div>
        <div class="cart-modal-footer">
            <div class="cart-total">
                <span><?php esc_html_e( 'Total:', 'hidayah' ); ?></span>
                <span id="cartTotalPrice">৳ ০</span>
            </div>
            <a href="<?php echo esc_url( home_url( '/cart' ) ); ?>" class="cart-checkout-btn">
                <?php esc_html_e( 'View Cart / Checkout', 'hidayah' ); ?>
            </a>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container"></div>
