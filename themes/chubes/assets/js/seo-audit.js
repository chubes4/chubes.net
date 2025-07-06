jQuery(document).ready(function($) {
    $('#seoAuditForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            action: 'process_seo_audit_form',
            security: seo_audit_params.nonce,
            auditName: $('#auditName').val(),
            auditEmail: $('#auditEmail').val(),
            auditWebsite: $('#auditWebsite').val(),
            auditMessage: $('#auditMessage').val(),
            website_honeypot: $('#website_honeypot').val(),
            seo_timestamp: $('#seoTimestamp').val()
        };
        
        $.ajax({
            type: 'POST',
            url: seo_audit_params.ajax_url,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#seoAuditForm').html('<p class="success-message">Thank you! Your request for a Free Local SEO Audit has been received. I will review your website and get back to you soon.</p>');
                } else {
                    $('.seo-error').text(response.data.message).show();
                }
            },
            error: function() {
                $('.seo-error').text('An error occurred. Please try again.').show();
            }
        });
    });
});
