/**
 * Checkout Page Scripts
 * Handles sidebar coupon application via AJAX.
 */
jQuery(function($) {
    if ( typeof wc_checkout_params === 'undefined' ) {
        return false;
    }

    $(document.body).on('click', '#apply_sidebar_coupon', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $input = $('#sidebar_coupon_code');
        var coupon_code = $input.val();
        var $message = $('#sidebar_coupon_message');

        if (!coupon_code) {
            $message.text('Please enter a coupon code.').removeClass('success').addClass('error').show();
            return false;
        }

        $button.prop('disabled', true).text('Please wait...');
        $message.hide().removeClass('success error');

        var data = {
            security: wc_checkout_params.apply_coupon_nonce,
            coupon_code: coupon_code
        };

        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
            data: data,
            success: function(code) {
                $('.woocommerce-error, .woocommerce-message').remove();
                $button.prop('disabled', false).text('Apply');

                if (code) {
                    var $html = $.parseHTML(code);
                    var $err = $($html).find('.woocommerce-error');
                    var $msg = $($html).find('.woocommerce-message');

                    if ($err.length > 0) {
                        $message.text($err.text().trim()).removeClass('success').addClass('error').show();
                    } else {
                        var successMsg = $msg.length > 0 ? $msg.text().trim() : 'Coupon applied successfully.';
                        $message.text(successMsg).removeClass('error').addClass('success').show();
                        $input.val('');
                        // Trigger update_checkout to refresh order review
                        $(document.body).trigger('update_checkout');
                    }
                }
            },
            error: function() {
                $button.prop('disabled', false).text('Apply');
                $message.text('Server error. Please try again.').removeClass('success').addClass('error').show();
            }
        });
    });

    // Optional: Allow pressing Enter to apply coupon
    $('#sidebar_coupon_code').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#apply_sidebar_coupon').click();
        }
    });
});
