/**
 * Contact Form AJAX Handler
 * 
 * Handles form submission via AJAX with nonce security.
 * Uses chubes_contact_params object localized from PHP with ajax_url and nonce.
 * Displays success/error messages and resets form on successful submission.
 */
jQuery(document).ready(function($){
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var formData = $form.serialize();
        
        // Clear previous messages.
        $form.find('.contact-error, .contact-success').hide().text('');
        
        $.ajax({
            url: chubes_contact_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: formData + '&action=process_contact_form&nonce=' + chubes_contact_params.nonce,
            success: function(response) {
                if(response.success) {
                    $form.find('.contact-success').text(response.data.message).show();
                    $form[0].reset();
                } else {
                    $form.find('.contact-error').text(response.data.message).show();
                }
            },
            error: function() {
                $form.find('.contact-error').text('An error occurred. Please try again later.').show();
            }
        });
    });
});
