<?php
/**
 * Archive Template (Fallback)
 * Used for any archive without a specific archive-{post_type}.php template.
 *
 * @package Hidayah
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="archive-section">
        <div class="container">
            <h1 class="section-title"><?php echo esc_html( hidayah_get_archive_title() ); ?></h1>

            <?php if ( have_posts() ) : ?>
                <div class="grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
                    <?php endwhile; ?>
                </div>
                <?php hidayah_pagination(); ?>
            <?php else : ?>
                <?php get_template_part( 'template-parts/content/content-none' ); ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
