<?php
// Shortcode to display the WordPress Customization Contact Form
function wp_customization_contact_form_shortcode() {
    ob_start(); ?>
    <form id="wpCustomizationContactForm" class="wp-customization-contact-form" method="POST">
        <!-- Honeypot Field for Spam Protection -->
        <div style="display:none;">
            <label for="wp_website_honeypot">Website</label>
            <input type="text" name="wp_website_honeypot" id="wp_website_honeypot">
        </div>
        
        <!-- Timestamp Field for Bot Check -->
        <input type="hidden" name="wp_contact_timestamp" id="wpContactTimestamp" value="<?php echo time(); ?>">
        
        <h2>Get in Touch for WordPress Customization</h2>
        <label for="wpContactName">Name</label>
        <input type="text" id="wpContactName" name="wpContactName" required>
        
        <label for="wpContactEmail">Email</label>
        <input type="email" id="wpContactEmail" name="wpContactEmail" required>
        
        <label for="wpContactWebsite">Website URL (if applicable)</label>
        <input type="text" id="wpContactWebsite" name="wpContactWebsite">
        
        <label for="wpContactMessage">Describe the Custom Feature or Integration You Need</label>
        <textarea id="wpContactMessage" name="wpContactMessage" required></textarea>
        
        <button type="submit" class="btn">Request a Free Consultation</button>
        <!-- Error/Success message container -->
        <p class="contact-message" style="display:none; text-align:center; margin-top:10px;"></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_customization_contact', 'wp_customization_contact_form_shortcode');

// AJAX Handler for processing the form
function process_wp_customization_contact_form() {
    // Honeypot check
    if (!empty($_POST['wp_website_honeypot'])) {
        wp_send_json_error(array('message' => 'Spam detected.'));
        exit;
    }
    
    // Timestamp check for bots
    $submitted_time = intval($_POST['wp_contact_timestamp']);
    if ((time() - $submitted_time) < 5) {
        wp_send_json_error(array('message' => 'Form submitted too quickly. Please try again.'));
        exit;
    }
    
    // Sanitize input fields
    $name    = sanitize_text_field(wp_unslash($_POST['wpContactName']));
    $email   = sanitize_email(wp_unslash($_POST['wpContactEmail']));
    $website = sanitize_text_field(wp_unslash($_POST['wpContactWebsite']));
    $message = sanitize_textarea_field(wp_unslash($_POST['wpContactMessage']));
    
    // Prepare the email for admin
    $admin_email = get_option('admin_email');
    $subject     = "New WordPress Customization Inquiry from " . $name;
    
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
    $user_subject = "Thank You for Your WordPress Customization Inquiry";
    $user_body  = "Hi " . $name . ",\n\n";
    $user_body .= "Thank you for reaching out about your WordPress customization needs. I’ll review your message and get back to you soon to discuss how we can add that advanced feature to your site.\n\n";
    $user_body .= "Best regards,\nChris Huber\n" . get_bloginfo('name');
    
    wp_mail($email, $user_subject, $user_body, $headers);
    
    wp_send_json_success(array('message' => 'Thank you for your request! I’ll get back to you soon.'));
    exit;
}
add_action('wp_ajax_process_wp_customization_contact_form', 'process_wp_customization_contact_form');
add_action('wp_ajax_nopriv_process_wp_customization_contact_form', 'process_wp_customization_contact_form');

// Enqueue the CSS and AJAX script only on the WordPress Customization page
function wp_customization_contact_enqueue_assets() {
    if (is_page('wordpress-customization')) { // Adjust the page slug as needed
        $theme_dir = get_template_directory_uri();
        // Dynamic versioning using file modification time
        $js_version = filemtime(get_template_directory() . '/js/wordpress-customization-contact.js');
        $css_version = filemtime(get_template_directory() . '/css/wordpress-customization-contact.css');
        
        wp_enqueue_script('wp-customization-contact-ajax', $theme_dir . '/js/wordpress-customization-contact.js', array('jquery'), $js_version, true);
        wp_localize_script('wp-customization-contact-ajax', 'wp_customization_contact_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wp_customization_contact_nonce')
        ));
        
        wp_enqueue_style('wp-customization-contact-css', $theme_dir . '/css/wordpress-customization-contact.css', array(), $css_version);
    }
}
add_action('wp_enqueue_scripts', 'wp_customization_contact_enqueue_assets');
?>
