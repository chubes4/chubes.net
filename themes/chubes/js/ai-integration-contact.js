jQuery(document).ready(function($) {
    $('#aiIntegrationContactForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize() + '&action=process_ai_integration_contact_form';
        
        // Clear any previous messages
        form.find('.contact-message').hide().text('');
        
        $.ajax({
            type: 'POST',
            url: ai_integration_contact_params.ajax_url,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    form.find('.contact-message')
                        .removeClass('error-message')
                        .addClass('success-message')
                        .text(response.data.message)
                        .fadeIn();
                    form[0].reset();
                } else {
                    form.find('.contact-message')
                        .removeClass('success-message')
                        .addClass('error-message')
                        .text(response.data.message)
                        .fadeIn();
                }
            },
            error: function(xhr, status, error) {
                form.find('.contact-message')
                    .removeClass('success-message')
                    .addClass('error-message')
                    .text('An error occurred. Please try again.')
                    .fadeIn();
            }
        });
    });
});
