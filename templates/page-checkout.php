<?php
/**
 * Template Name: Checkout Page
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<section class="archive-hero">
    <div class="archive-hero-content">
        <h2><?php the_title(); ?></h2>
        <p><?php echo get_post_meta( get_the_ID(), '_checkout_hero_text', true ) ?: __( 'আপনার ডেলিভারি ঠিকানা ও পেমেন্ট তথ্য দিন এবং অর্ডার সম্পন্ন করুন।', 'hidayah' ); ?></p>
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

        <!-- Steps -->
        <div class="co-steps">
            <div class="co-step done"><div class="co-step-icon"><span class="material-symbols-outlined">shopping_cart</span></div><span class="co-step-label"><?php _e( 'কার্ট', 'hidayah' ); ?></span></div>
            <div class="co-step-line done"></div>
            <div class="co-step active"><div class="co-step-icon"><span class="material-symbols-outlined">location_on</span></div><span class="co-step-label"><?php _e( 'ঠিকানা ও পেমেন্ট', 'hidayah' ); ?></span></div>
            <div class="co-step-line"></div>
            <div class="co-step"><div class="co-step-icon"><span class="material-symbols-outlined">check_circle</span></div><span class="co-step-label"><?php _e( 'সম্পন্ন', 'hidayah' ); ?></span></div>
        </div>

        <div class="archive-layout co-layout">
            <!-- LEFT: FORM -->
            <div class="archive-main">
                <form class="co-form" id="checkoutForm">
                    <div class="co-form-section">
                        <h3 class="co-section-title"><span class="material-symbols-outlined">person</span><?php _e( 'ক্রেতার তথ্য', 'hidayah' ); ?></h3>
                        <!-- Fields -->
                    </div>
                    <!-- ... more sections ... -->
                </form>
            </div>

            <!-- RIGHT: SUMMARY -->
            <aside class="archive-sidebar">
                <div class="col-inner">
                    <div class="co-summary-card">
                        <h3 class="co-summary-title"><span class="material-symbols-outlined">receipt_long</span><?php _e( 'অর্ডার সারসংক্ষেপ', 'hidayah' ); ?></h3>
                        <!-- Items & Totals -->
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
