<?php
// Shortcode to display the AI Integration Contact Form
function ai_integration_contact_form_shortcode() {
    ob_start(); ?>
    <form id="aiIntegrationContactForm" class="ai-integration-contact-form" method="POST">
        <!-- Honeypot Field for Spam Protection -->
        <div style="display:none;">
            <label for="ai_website_honeypot">Website</label>
            <input type="text" name="ai_website_honeypot" id="ai_website_honeypot">
        </div>
        
        <!-- Timestamp Field for Bot Check -->
        <input type="hidden" name="ai_contact_timestamp" id="aiContactTimestamp" value="<?php echo time(); ?>">
        
        <h2>Get in Touch for AI Integration</h2>
        <label for="aiContactName">Name</label>
        <input type="text" id="aiContactName" name="aiContactName" required>
        
        <label for="aiContactEmail">Email</label>
        <input type="email" id="aiContactEmail" name="aiContactEmail" required>
        
        <label for="aiContactWebsite">Website URL (if applicable)</label>
        <input type="text" id="aiContactWebsite" name="aiContactWebsite">
        
        <label for="aiContactMessage">What Are Your AI Integration Needs?</label>
        <textarea id="aiContactMessage" name="aiContactMessage" required></textarea>
        
        <button type="submit" class="btn">Request a Free AI Consultation</button>
        <!-- Error/Success message container -->
        <p class="contact-message" style="display:none; text-align:center; margin-top:10px;"></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('ai_integration_contact', 'ai_integration_contact_form_shortcode');

// AJAX Handler for processing the form
function process_ai_integration_contact_form() {
    // Honeypot check
    if (!empty($_POST['ai_website_honeypot'])) {
        wp_send_json_error(array('message' => 'Spam detected.'));
        exit;
    }
    
    // Timestamp check for bots
    $submitted_time = intval($_POST['ai_contact_timestamp']);
    if ((time() - $submitted_time) < 5) {
        wp_send_json_error(array('message' => 'Form submitted too quickly. Please try again.'));
        exit;
    }
    
    // Sanitize input fields
    $name    = sanitize_text_field(wp_unslash($_POST['aiContactName']));
    $email   = sanitize_email(wp_unslash($_POST['aiContactEmail']));
    $website = sanitize_text_field(wp_unslash($_POST['aiContactWebsite']));
    $message = sanitize_textarea_field(wp_unslash($_POST['aiContactMessage']));
    
    // Prepare the email for admin
    $admin_email = get_option('admin_email');
    $subject     = "New AI Integration Inquiry from " . $name;
    
    $body  = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Website: " . $website . "\n";
    $body .= "Message: " . $message;
    
    // Set custom headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>'
    );
    
    wp_mail($admin_email, $subject, $body, $headers);
    
    // Confirmation email for the user
    $user_subject = "Thank You for Your AI Integration Inquiry";
    $user_body  = "Hi " . $name . ",\n\n";
    $user_body .= "Thank you for reaching out about AI integration. I'll review your message and contact you soon to discuss how we can transform your workflow with AI.\n\n";
    $user_body .= "Best regards,\nChris Huber\n" . get_bloginfo('name');
    
    wp_mail($email, $user_subject, $user_body, $headers);
    
    wp_send_json_success(array('message' => 'Thank you for your request! I\'ll get back to you soon.'));
    exit;
}
add_action('wp_ajax_process_ai_integration_contact_form', 'process_ai_integration_contact_form');
add_action('wp_ajax_nopriv_process_ai_integration_contact_form', 'process_ai_integration_contact_form');

// Enqueue the CSS and AJAX script only on the AI Integration page
function ai_integration_contact_enqueue_assets() {
    if (is_page('ai-integration')) { // Adjust the page slug as needed
        $theme_dir = get_template_directory_uri();
        // Dynamic versioning using file modification time
        $js_version = filemtime(get_template_directory() . '/assets/js/ai-integration-contact.js');
        $css_version = filemtime(get_template_directory() . '/assets/css/ai-integration-contact.css');
        
        wp_enqueue_script('ai-integration-contact-ajax', $theme_dir . '/assets/js/ai-integration-contact.js', array('jquery'), $js_version, true);
        wp_localize_script('ai-integration-contact-ajax', 'ai_integration_contact_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ai_integration_contact_nonce')
        ));
        
        wp_enqueue_style('ai-integration-contact-css', $theme_dir . '/assets/css/ai-integration-contact.css', array(), $css_version);
    }
}
add_action('wp_enqueue_scripts', 'ai_integration_contact_enqueue_assets');
?>
