<?php
/**
 * Template Name: Cart Page
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php the_title(); ?></h2>
        <p><?php echo get_post_meta( get_the_ID(), '_cart_hero_text', true ) ?: __( 'আপনার পছন্দের বইগুলো পর্যালোচনা করুন এবং অর্ডার সম্পন্ন করুন।', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'হোম', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="cart-page-layout" id="cartPageLayout">
            <!-- Empty Cart State -->
            <div class="cart-empty-state hidden" id="cartEmptyState">
                <div class="cart-empty-icon">
                    <span class="material-symbols-outlined">shopping_cart</span>
                </div>
                <h3><?php _e( 'আপনার কার্ট খালি', 'hidayah' ); ?></h3>
                <p><?php _e( 'এখনো কোনো বই কার্টে যোগ করা হয়নি।', 'hidayah' ); ?></p>
                <a class="btn" href="<?php echo esc_url( home_url( '/books/' ) ); ?>">
                    <span class="material-symbols-outlined">library_books</span>
                    <?php _e( 'বই কিনুন', 'hidayah' ); ?>
                </a>
            </div>

            <!-- Cart Has Items (JS will populate or WooCommerce integration) -->
            <div class="cart-has-items" id="cartHasItems">
                <div class="cart-table-wrap">
                    <div class="cart-table-header">
                        <span class="cart-th cart-th-product"><?php _e( 'বই', 'hidayah' ); ?></span>
                        <span class="cart-th cart-th-price"><?php _e( 'একক মূল্য', 'hidayah' ); ?></span>
                        <span class="cart-th cart-th-qty"><?php _e( 'পরিমাণ', 'hidayah' ); ?></span>
                        <span class="cart-th cart-th-sub"><?php _e( 'মোট', 'hidayah' ); ?></span>
                        <span class="cart-th cart-th-remove"></span>
                    </div>
                    <div class="cart-table-body" id="cartTableBody">
                        <!-- Rows injected by javascript or php -->
                    </div>
                </div>

                <div class="cart-actions-bar">
                    <a class="cart-continue-btn" href="<?php echo esc_url( home_url( '/books/' ) ); ?>">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <?php _e( 'কেনাকাটা চালিয়ে যান', 'hidayah' ); ?>
                    </a>
                    <button class="cart-clear-btn" id="cartClearBtn">
                        <span class="material-symbols-outlined">delete_sweep</span>
                        <?php _e( 'সব সরান', 'hidayah' ); ?>
                    </button>
                </div>
                
                <div class="cart-bottom-grid">
                    <div class="cart-coupon-box">
                        <h4 class="cart-box-title"><span class="material-symbols-outlined">discount</span><?php _e( 'কুপন কোড', 'hidayah' ); ?></h4>
                        <!-- Coupon form -->
                    </div>
                    <div class="cart-totals-card">
                        <h4 class="cart-box-title"><span class="material-symbols-outlined">receipt_long</span><?php _e( 'অর্ডার সারসংক্ষেপ', 'hidayah' ); ?></h4>
                        <!-- Totals -->
                        <a class="cart-checkout-btn" href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            <?php _e( 'চেকআউটে যান', 'hidayah' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
