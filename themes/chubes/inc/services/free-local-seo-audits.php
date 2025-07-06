<?php
// Shortcode to display the Free Local SEO Audits form
function free_local_seo_audit_form_shortcode() {
    ob_start(); ?>
    <form id="seoAuditForm" class="seo-audit-form" method="POST">
        <!-- Honeypot Field for Spam Protection -->
        <div style="display:none;">
            <label for="website_honeypot">Website</label>
            <input type="text" name="website_honeypot" id="website_honeypot">
        </div>
        
        <!-- Timestamp Field for Bot Check -->
        <input type="hidden" name="seo_timestamp" id="seoTimestamp" value="<?php echo time(); ?>">
        <h2>Get Your Free Local SEO Audit</h2>
        <label for="auditName">Name</label>
        <input type="text" id="auditName" name="auditName" required>
        
        <label for="auditEmail">Email</label>
        <input type="email" id="auditEmail" name="auditEmail" required>
        
        <label for="auditWebsite">Website URL</label>
        <input type="text" id="auditWebsite" name="auditWebsite" required>
        
        <label for="auditMessage">What Are Your Main SEO Challenges?</label>
        <textarea id="auditMessage" name="auditMessage" required></textarea>
        
        <button type="submit" class="btn">Request Free SEO Audit</button>
        <!-- Error message container -->
        <p class="seo-error" style="display:none; color:red; text-align:center; margin-top:10px;"></p>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('free_local_seo_audit', 'free_local_seo_audit_form_shortcode');

// AJAX Handler for processing the form
function process_seo_audit_form() {
    // Honeypot check
    if ( ! empty( $_POST['website_honeypot'] ) ) {
        wp_send_json_error( array( 'message' => 'Spam detected.' ) );
        exit;
    }
    
    // Timestamp check for bots
    $submitted_time = intval( $_POST['seo_timestamp'] );
    if ( ( time() - $submitted_time ) < 5 ) {
        wp_send_json_error( array( 'message' => 'Form submitted too quickly. Please try again.' ) );
        exit;
    }
    
    // Sanitize input fields
    $name    = sanitize_text_field( wp_unslash( $_POST['auditName'] ) );
    $email   = sanitize_email( wp_unslash( $_POST['auditEmail'] ) );
    $website = sanitize_text_field( wp_unslash( $_POST['auditWebsite'] ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['auditMessage'] ) );
    
    // Prepare the email for admin
    $admin_email = get_option( 'admin_email' );
    $subject     = "New Local SEO Audit Request from " . $name;
    
    $body  = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Website: " . $website . "\n";
    $body .= "Message: " . $message;
    
    // Set custom headers
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>'
    ];
    
    wp_mail( $admin_email, $subject, $body, $headers );
    
    // Confirmation email for the user
    $user_subject = "Your Free Local SEO Audit Request";
    $user_body  = "Hi " . $name . ",\n\n";
    $user_body .= "Thank you for requesting a free local SEO audit. I will review your website and send the audit report soon.\n\n";
    $user_body .= "Best regards,\nChris Huber\n" . get_bloginfo( 'name' );
    
    wp_mail( $email, $user_subject, $user_body, $headers );
    
    // Send JSON response for AJAX
    wp_send_json_success( array( 'message' => 'Thank you for your request! I will get back to you soon.' ) );
    exit;
}
add_action( 'wp_ajax_process_seo_audit_form', 'process_seo_audit_form' );
add_action( 'wp_ajax_nopriv_process_seo_audit_form', 'process_seo_audit_form' );

// Enqueue the CSS and AJAX script only on the /free-local-seo-audits page
function seo_audit_enqueue_assets() {
    if ( is_page( array( 'free-local-seo-audits', 'local-seo' ) ) ) {
        // Enqueue the AJAX script
        wp_enqueue_script( 'seo-audit-ajax', get_template_directory_uri() . '/assets/js/seo-audit.js', array('jquery'), null, true );
        wp_localize_script( 'seo-audit-ajax', 'seo_audit_params', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'seo_audit_nonce' )
        ));
        
        // Enqueue the CSS for the SEO Audit form
        wp_enqueue_style( 'seo-audit-css', get_template_directory_uri() . '/assets/css/free-local-seo-audits.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'seo_audit_enqueue_assets' );


