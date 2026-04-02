<?php
/**
 * Product quantity input
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

/* Translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

?>
<div class="sb-qty-wrap">
    <div class="sb-qty-stepper quantity">
        <button type="button" aria-label="<?php echo esc_attr__( 'Decrease', 'hidayah' ); ?>" class="sb-qty-btn minus-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.querySelector('input[type=number]').dispatchEvent(new Event('change', { bubbles: true }));">
            <span class="material-symbols-outlined">remove</span>
        </button>
        
        <input
            type="number"
            id="<?php echo esc_attr( $input_id ); ?>"
            class="sb-qty-input <?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
            step="<?php echo esc_attr( $step ); ?>"
            min="<?php echo esc_attr( $min_value ); ?>"
            max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
            name="<?php echo esc_attr( $input_name ); ?>"
            value="<?php echo esc_attr( $input_value ); ?>"
            title="<?php echo esc_attr_x( 'Qty', 'numeric input title', 'woocommerce' ); ?>"
            size="4"
            placeholder="<?php echo esc_attr( $placeholder ); ?>"
            inputmode="<?php echo esc_attr( $inputmode ); ?>"
            autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
        />
        
        <button type="button" aria-label="<?php echo esc_attr__( 'Increase', 'hidayah' ); ?>" class="sb-qty-btn plus-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.querySelector('input[type=number]').dispatchEvent(new Event('change', { bubbles: true }));">
            <span class="material-symbols-outlined">add</span>
        </button>
    </div>
</div>
