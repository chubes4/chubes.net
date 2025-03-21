jQuery(document).ready(function($) {
    $('#webDevContactForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: web_dev_contact_params.ajax_url,
            type: 'POST',
            data: formData + '&action=process_web_dev_contact_form&_wpnonce=' + web_dev_contact_params.nonce,
            dataType: 'json',
            beforeSend: function() {
                $('.error-message, .success-message').hide().text('');
                $('button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    $('.success-message').text(response.data.message).show();
                    $('#webDevContactForm')[0].reset();
                } else {
                    $('.error-message').text(response.data.message).show();
                }
            },
            error: function(xhr, status, error) {
                $('.error-message').text('An error occurred. Please try again.').show();
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });
});