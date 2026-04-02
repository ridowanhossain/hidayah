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
        <p><?php echo get_post_meta( get_the_ID(), '_cart_hero_text', true ) ?: __( 'Review your selected items and proceed to checkout.', 'hidayah' ); ?></p>
    </div>
</section>

<section class="archive-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="archive-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'hidayah' ); ?></a>
            <span class="material-symbols-outlined breadcrumb-sep">chevron_right</span>
            <span class="breadcrumb-current"><?php the_title(); ?></span>
        </nav>

        <div class="cart-page-layout-woo">
            <?php echo do_shortcode( '[woocommerce_cart]' ); ?>
        </div>
    </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
