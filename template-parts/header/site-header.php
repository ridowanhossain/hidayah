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
$h_donate_label  = hidayah_opt( 'header_donation_label', __( 'Donate', 'hidayah' ) );
$h_show_cart     = hidayah_opt( 'header_show_cart', true );

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
            <div class="logo">
                <?php if ( $logo_url ) : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img src="<?php echo esc_url( $logo_url ); ?>"
                             alt="<?php echo esc_attr( $h_site_title ); ?>" />
                    </a>
                <?php elseif ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img src="<?php echo esc_url( HIDAYAH_URI . '/assets/images/logo-new.png' ); ?>"
                             alt="<?php echo esc_attr( $h_site_title ); ?>" />
                    </a>
                <?php endif; ?>
            </div>
            <div class="header-text">
                <?php if ( is_front_page() && is_home() ) : ?>
                    <h1><?php echo esc_html( $h_site_title ); ?></h1>
                <?php else : ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;">
                            <?php echo esc_html( $h_site_title ); ?>
                        </a>
                    </p>
                <?php endif; ?>
                <p><?php echo esc_html( $h_site_subtitle ); ?></p>
                <p id="hijri-date" class="hijri-date"></p>
            </div>
        </div>

        <!-- Right side: Cart + Donation button -->
        <div class="header-right">
            <?php if ( $h_show_cart ) : ?>
                <?php if ( function_exists( 'WC' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-btn" id="headerCartBtn">
                    <span class="material-symbols-outlined cart-icon">shopping_cart</span>
                    <span class="cart-count" id="cartCountBadge">
                        <?php echo intval( WC()->cart->get_cart_contents_count() ); ?>
                    </span>
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/cart' ) ); ?>" class="header-cart-btn" id="headerCartBtn">
                    <span class="material-symbols-outlined cart-icon">shopping_cart</span>
                    <span class="cart-count" id="cartCountBadge">0</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>

            <a href="<?php echo esc_url( $h_donate_url ); ?>" class="donation-btn">
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
            <button class="close-menu" aria-label="<?php esc_attr_e( 'Close menu', 'hidayah' ); ?>">
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
            <!-- Fallback nav: shown when no WordPress menu is assigned -->
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span><?php esc_html_e( 'Home', 'hidayah' ); ?></span></a></li>
                <li class="nav-item"><a href="<?php echo esc_url( home_url( '/darbar-sharif' ) ); ?>"><span><?php esc_html_e( 'Darbar Sharif', 'hidayah' ); ?></span></a></li>
                <li class="nav-item has-dropdown">
                    <a href="#"><span><?php esc_html_e( 'Publications', 'hidayah' ); ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'monthly_hd' ) ); ?>"><?php esc_html_e( 'Monthly Haqquer Dawat (PDF)', 'hidayah' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>"><?php esc_html_e( 'Books & Publications', 'hidayah' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'probondho' ) ); ?>"><?php esc_html_e( 'Articles & Essays', 'hidayah' ); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item has-dropdown">
                    <a href="#"><span><?php esc_html_e( 'Live & Media', 'hidayah' ); ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'audio' ) ); ?>"><?php esc_html_e( 'Audio Lectures', 'hidayah' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>"><?php esc_html_e( 'Video Lectures', 'hidayah' ); ?></a></li>
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'photo_gallery' ) ); ?>"><?php esc_html_e( 'Photo Gallery', 'hidayah' ); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item has-dropdown">
                    <a href="#"><span><?php esc_html_e( 'Education', 'hidayah' ); ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><?php esc_html_e( 'School Program', 'hidayah' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Madrasa Program', 'hidayah' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/admission-info' ) ); ?>"><?php esc_html_e( 'Admission Info', 'hidayah' ); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'dini_jiggasa' ) ); ?>"><span><?php esc_html_e( 'Islamic Q&A', 'hidayah' ); ?></span></a></li>
                <li class="nav-item has-dropdown">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'notice' ) ); ?>"><span><?php esc_html_e( 'Notices', 'hidayah' ); ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><?php esc_html_e( 'Darbar Notices', 'hidayah' ); ?></a></li>
                        <li><a href="#"><?php esc_html_e( 'Mahfil Announcements', 'hidayah' ); ?></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><span><?php esc_html_e( 'Contact', 'hidayah' ); ?></span></a></li>
            </ul>
        <?php endif; ?>

        <!-- Mobile menu footer -->
        <div class="mobile-menu-footer">
            <a href="<?php echo esc_url( home_url( '/hadiya' ) ); ?>" class="donation-btn full-width">
                <span class="donation-text"><?php esc_html_e( 'Donate', 'hidayah' ); ?></span>
            </a>
        </div>

    </div>
</nav>
