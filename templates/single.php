<?php
/**
 * Single Post Template (Fallback)
 * Used for any post type without a specific single-{post_type}.php template.
 *
 * @package Hidayah
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content/content', get_post_type() );
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>
