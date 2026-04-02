(function($) {
    'use strict';

    $(document).on('submit', 'form.cart', function(e) {
        var $form = $(this);
        var $button = $form.find('.single_add_to_cart_button');
        
        // Only trigger if it's a standard single product add to cart (not external)
        if ($form.closest('.product').hasClass('product-type-external')) {
            return;
        }

        e.preventDefault();

        $button.addClass('loading');

        var formData = new FormData($form[0]);
        formData.append('add-to-cart', $form.find('[name=add-to-cart]').val() || $form.find('button[name=add-to-cart]').val());
        formData.append('action', 'hidayah_ajax_add_to_cart');

        $.ajax({
            url: wc_single_ajax_params.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $button.removeClass('loading');
                
                if (response.error && response.product_url) {
                    window.location.href = response.product_url;
                    return;
                }

                // Trigger standard WooCommerce event
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                
                // Show side cart if desired (most themes do this)
                if (typeof window.openCartDrawer === 'function') {
                    window.openCartDrawer();
                }
            },
            error: function() {
                $button.removeClass('loading');
            }
        });
    });

})(jQuery);
