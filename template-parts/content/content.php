<?php
/**
 * Template Part: Content (Generic Fallback)
 * Used as a fallback card for any post type without a specific template.
 *
 * @package Hidayah
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
        </a>
    <?php endif; ?>
    <div class="content">
        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        <p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
        <div class="meta">
            <span><?php echo esc_html( hidayah_bangla_time_ago() ); ?></span>
        </div>
    </div>
</article>
