<?php
// Shortcode to display the Contact Form for Web Development
function web_dev_contact_form_shortcode() {
    ob_start(); ?>
    <form id="webDevContactForm" class="web-dev-contact-form" method="POST">
        <!-- Honeypot Field for Spam Protection -->
        <div style="display:none;">
            <label for="website_honeypot">Website</label>
            <input type="text" name="website_honeypot" id="website_honeypot">
        </div>
        
        <!-- Timestamp Field for Bot Check -->
        <input type="hidden" name="contact_timestamp" id="contactTimestamp" value="<?php echo time(); ?>">
        
        <h2>Get in Touch for Your Website</h2>
        <label for="contactName">Name</label>
        <input type="text" id="contactName" name="contactName" required>
        
        <label for="contactEmail">Email</label>
        <input type="email" id="contactEmail" name="contactEmail" required>
        
        <label for="contactWebsite">Website URL (if applicable)</label>
        <input type="text" id="contactWebsite" name="contactWebsite">
        
        <label for="contactMessage">What Are Your Website Needs?</label>
        <textarea id="contactMessage" name="contactMessage" required></textarea>
        
        <button type="submit" class="btn">Request a Free Consultation</button>
        <!-- Error/Success message containers -->
        <p class="error-message"></p>
        <p class="success-message"></p>
    </form>
    <?php return ob_get_clean();
}
add_shortcode('web_dev_contact', 'web_dev_contact_form_shortcode');

// AJAX Handler for processing the form
function process_web_dev_contact_form() {
    // Honeypot check
    if (!empty($_POST['website_honeypot'])) {
        wp_send_json_error(array('message' => 'Spam detected.'));
        exit;
    }
    
    // Timestamp check for bots
    $submitted_time = intval($_POST['contact_timestamp']);
    if ((time() - $submitted_time) < 5) {
        wp_send_json_error(array('message' => 'Form submitted too quickly. Please try again.'));
        exit;
    }
    
    // Sanitize input fields
    $name    = sanitize_text_field(wp_unslash($_POST['contactName']));
    $email   = sanitize_email(wp_unslash($_POST['contactEmail']));
    $website = sanitize_text_field(wp_unslash($_POST['contactWebsite']));
    $message = sanitize_textarea_field(wp_unslash($_POST['contactMessage']));
    
    // Prepare the email for admin
    $admin_email = get_option('admin_email');
    $subject     = "New Web Development Inquiry from " . $name;
    
    $body  = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Website: " . $website . "\n";
    $body .= "Message: " . $message;
    
    // Set custom headers
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>'
    ];
    
    wp_mail($admin_email, $subject, $body, $headers);
    
    // Send confirmation email to user
    $user_subject = "Thank You for Your Web Development Inquiry";
    $user_body  = "Hi " . $name . ",\n\n";
    $user_body .= "Thank you for reaching out about your website needs. I'll review your message and contact you soon to discuss next steps.\n\n";
    $user_body .= "Best regards,\nChris Huber\n" . get_bloginfo('name');
    
    wp_mail($email, $user_subject, $user_body, $headers);
    
    // Send JSON response for AJAX
    wp_send_json_success(array('message' => 'Thank you for your request! I\'ll get back to you soon.'));
    exit;
}
add_action('wp_ajax_process_web_dev_contact_form', 'process_web_dev_contact_form');
add_action('wp_ajax_nopriv_process_web_dev_contact_form', 'process_web_dev_contact_form');

// Enqueue the CSS and AJAX script only on the Web Development Services page
function web_dev_contact_enqueue_assets() {
    if (is_page(array('web-development')) || is_single('why-small-businesses-need-a-website')) { 
        
        // Get theme directory path for filemtime()
        $theme_dir = get_template_directory();
        $theme_uri = get_template_directory_uri();

        // Define file paths
        $js_file = '/js/web-dev-contact.js';
        $css_file = '/css/web-dev-contact.css';

        // Get file modification time for cache busting
        $js_version = file_exists($theme_dir . $js_file) ? filemtime($theme_dir . $js_file) : null;
        $css_version = file_exists($theme_dir . $css_file) ? filemtime($theme_dir . $css_file) : null;

        // Enqueue the AJAX script with dynamic versioning
        wp_enqueue_script('web-dev-contact-ajax', $theme_uri . $js_file, array('jquery'), $js_version, true);
        wp_localize_script('web-dev-contact-ajax', 'web_dev_contact_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('web_dev_contact_nonce')
        ));

        // Enqueue the CSS with dynamic versioning
        wp_enqueue_style('web-dev-contact-css', $theme_uri . $css_file, array(), $css_version);
    }
}
add_action('wp_enqueue_scripts', 'web_dev_contact_enqueue_assets');
