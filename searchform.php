<?php
/**
 * Search Form Template
 *
 * @package Hidayah
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'hidayah' ); ?></span>
        <input type="search"
               class="search-field"
               placeholder="<?php esc_attr_e( 'What are you looking for?', 'hidayah' ); ?>"
               value="<?php echo get_search_query(); ?>"
               name="s"
               id="search-field">
    </label>
    <button type="submit" class="search-submit btn">
        <span class="material-symbols-outlined">search</span>
        <span><?php esc_html_e( 'Search', 'hidayah' ); ?></span>
    </button>
</form>
