<?php
/**
 * Template Part: Content None
 * Shown when no posts are found.
 *
 * @package Hidayah
 */
?>
<div class="no-results not-found">
    <h2><?php esc_html_e( 'Nothing Found', 'hidayah' ); ?></h2>
    <p><?php esc_html_e( 'Sorry, no content matched your search. Please try again or browse using the search form below.', 'hidayah' ); ?></p>
    <?php get_search_form(); ?>
</div>
