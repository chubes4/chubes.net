/**
 * Contact Form REST API Handler
 * 
 * Handles form submission via REST API with nonce security.
 * Uses chubes_contact_params object localized from PHP with rest_url and nonce.
 * Displays success/error messages and resets form on successful submission.
 */
jQuery(document).ready(function($){
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var formData = {
            contactName: $form.find('[name="contactName"]').val(),
            contactEmail: $form.find('[name="contactEmail"]').val(),
            contactWebsite: $form.find('[name="contactWebsite"]').val(),
            contactMessage: $form.find('[name="contactMessage"]').val(),
            contact_honeypot: $form.find('[name="contact_honeypot"]').val(),
            contact_timestamp: $form.find('[name="contact_timestamp"]').val(),
            nonce: chubes_contact_params.nonce
        };
        
        // Clear previous messages.
        $form.find('.contact-error, .contact-success').hide().text('');
        
        $.ajax({
            url: chubes_contact_params.rest_url,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                if(response.success) {
                    $form.find('.contact-success').text(response.message).show();
                    $form[0].reset();
                    // Update timestamp for next submission
                    $form.find('[name="contact_timestamp"]').val(Math.floor(Date.now() / 1000));
                } else {
                    $form.find('.contact-error').text(response.message).show();
                }
            },
            error: function(xhr) {
                var errorMessage = 'An error occurred. Please try again later.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $form.find('.contact-error').text(errorMessage).show();
            }
        });
    });
});
