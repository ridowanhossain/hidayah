<?php
/**
 * Sidebar Template
 * Loaded when get_sidebar() is called.
 *
 * @package Hidayah
 */

if ( ! is_active_sidebar( 'sidebar-main' ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar-main">
    <?php dynamic_sidebar( 'sidebar-main' ); ?>
</aside>
