<?php
/**
 * 404 Not Found Template
 *
 * @package Hidayah
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="text-align:center; padding: 80px 15px;">
        <h1 style="font-size: 100px; color: var(--primary-green-dark); margin: 0;">404</h1>
        <h2 style="margin: 10px 0 20px;"><?php esc_html_e( 'Page Not Found', 'hidayah' ); ?></h2>
        <p><?php esc_html_e( 'The page you are looking for may have been moved, renamed, or is temporarily unavailable.', 'hidayah' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn" style="margin-top: 20px;">
            <span class="material-symbols-outlined">home</span>
            <?php esc_html_e( 'Back to Homepage', 'hidayah' ); ?>
        </a>
    </div>
</main>

<?php get_footer(); ?>
