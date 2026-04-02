<?php
/**
 * Template Name: Checkout Page
 *
 * @package Hidayah
 */
get_header();

while ( have_posts() ) : the_post();
?>

<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

<?php endwhile; ?>
<?php get_footer(); ?>
