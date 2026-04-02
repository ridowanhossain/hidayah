<?php
/**
 * Template Part: Site Header
 * Converted from index.html
 *
 * All UI strings use __() / esc_html_e() for i18n.
 * Bengali text in fallback nav items will be translatable via .po files.
 *
 * @package Hidayah
 */

// ── Header Settings from Theme Options ──────────────────────────────────────
$h_site_title    = hidayah_opt( 'header_site_title',    get_bloginfo( 'name' ) );
$h_site_subtitle = hidayah_opt( 'header_site_subtitle', get_bloginfo( 'description' ) );
$h_donate_url    = hidayah_opt( 'header_donation_url',  home_url( '/hadiya' ) );
$h_donate_label  = hidayah_opt( 'header_donation_label', __( 'Donation', 'hidayah' ) );
$h_show_cart     = hidayah_opt( 'header_show_cart', true );
$h_hide_donate_m = hidayah_opt( 'header_hide_donation_mobile', false );
$h_show_date     = hidayah_opt( 'header_show_date', true );

// ── Logo from General Settings (CSF media field) ─────────────────────────────
$logo_data = hidayah_opt( 'site_logo' );
$logo_url  = is_array( $logo_data ) ? ( $logo_data['url'] ?? '' ) : '';
?>

<header id="masthead">
    <div class="header-wrap">

        <!-- Mobile menu toggle -->
        <div class="m-menu">
            <div class="menu-toggle" id="mobile-menu">
                <span class="material-symbols-outlined">menu</span>
            </div>
        </div>

        <!-- Logo & Site Title -->
        <div class="header-left">
            <?php if ( $logo_url || has_custom_logo() ) : ?>
                <div class="logo">
                    <?php if ( $logo_url ) : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <img src="<?php echo esc_url( $logo_url ); ?>"
                                 alt="<?php echo esc_attr( $h_site_title ); ?>" />
                        </a>
                    <?php else : ?>
                        <?php the_custom_logo(); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="header-text">
                <p class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;">
                        <?php echo esc_html( $h_site_title ); ?>
                    </a>
                </p>
                <p><?php echo esc_html( $h_site_subtitle ); ?></p>
                <?php if ( $h_show_date ) : ?>
                    <p id="hijri-date" class="hijri-date"></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right side: Cart + Donation button -->
        <div class="header-right">
            <?php if ( $h_show_cart ) : ?>
                <div class="cart-wrapper">
                    <?php if ( function_exists( 'WC' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-btn" id="headerCartBtn">
                        <span class="material-symbols-outlined cart-icon">shopping_cart</span>
                        <span class="cart-count" id="cartCountBadge">
                            <?php echo intval( WC()->cart->get_cart_contents_count() ); ?>
                        </span>
                    </a>
                    <?php else : ?>
                    <button class="header-cart-btn" id="headerCartBtn">
                        <span class="material-symbols-outlined cart-icon">shopping_cart</span>
                        <span class="cart-count" id="cartCountBadge">0</span>
                    </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <a href="<?php echo esc_url( $h_donate_url ); ?>" class="donation-btn<?php echo $h_hide_donate_m ? ' hide-m' : ''; ?>">
                <span class="material-symbols-outlined donation-icon">volunteer_activism</span>
                <span class="donation-text"><?php echo esc_html( $h_donate_label ); ?></span>
            </a>
        </div>

    </div>
</header>

<!-- Mobile overlay -->
<div class="nav-overlay"></div>

<!-- Navigation -->
<nav id="site-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'hidayah' ); ?>">
    <div class="nav-wrap">

        <!-- Mobile menu header -->
        <div class="nav-header-mobile">
            <span class="nav-title"><?php esc_html_e( 'Menu', 'hidayah' ); ?></span>
            <button class="close-menu" aria-label="<?php esc_attr_e( 'Close', 'hidayah' ); ?>">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <?php
        if ( has_nav_menu( 'primary' ) ) :
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'nav-menu',
                'item_spacing'   => 'discard',
                'fallback_cb'    => false,
            ) );
        else :
        ?>
            <!-- Fallback nav: removed as requested -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <p style="padding: 20px; color: rgba(255,255,255,0.6); font-size: 14px;">
                        <?php esc_html_e( 'No menu set. Please set a menu from Appearance > Menus.', 'hidayah' ); ?>
                    </p>
                </li>
            </ul>
        <?php endif; ?>

        <!-- Mobile menu footer -->
        <div class="mobile-menu-footer<?php echo $h_hide_donate_m ? ' hide-m' : ''; ?>">
            <a href="<?php echo esc_url( $h_donate_url ); ?>" class="donation-btn full-width">
                <span class="donation-text"><?php echo esc_html( $h_donate_label ); ?></span>
            </a>
        </div>
    </div>
</nav>
