(function init($) {
    sendResetPasswordLink();
})(jQuery);

function sendResetPasswordLink(){
    jQuery(document).on('click', '.rest__button', function () {
        var email = jQuery('#resetPasswordCustomForm').val();
        var data = new FormData();

        if(email) {
            data.append('email', email);

            jQuery.ajax({
                url: ajaxAccountResetPassword,
                type: 'POST',
                contentType: false,
                processData: false,
                data: data,
                success: function (response) {
                    if (response === 'true') {

                        jQuery(".rest__button").removeClass('buttonRed');
                        jQuery(".rest-password-modal-message").removeClass('active');
                        jQuery(".rest-password-modal").removeClass('active');
                        jQuery(".rest-password-modal-thanks").addClass('active');
                    }
                    if (response === 'false') {

                        jQuery(".rest-password-modal-message").addClass('active');
                        jQuery(".rest__button").addClass('buttonRed');
                        jQuery(".rest-password-modal").effect( "shake", {times:4}, 1000 );
                    }
                },
            });
        }else{
            jQuery(".rest-password-modal-message").addClass('active');
            jQuery(".rest__button").addClass('buttonRed');
            jQuery(".rest-password-modal").effect( "shake", {times:4}, 1000 );
        }
    });
}