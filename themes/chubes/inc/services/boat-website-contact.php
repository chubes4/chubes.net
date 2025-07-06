<?php
/*
 * Boat Website Contact Modal Popup
 * For: custom-websites-for-boat-companies page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue Scripts and Styles only on the specific page
function boat_website_enqueue_assets() {
    if (is_page('marine-industry-websites')) {
        $js_path = get_template_directory() . '/assets/js/boat-contact-modal.js';
        $css_path = get_template_directory() . '/assets/css/boat-contact-modal.css';

        // Dynamic versioning based on file modification time
        $js_version = file_exists($js_path) ? filemtime($js_path) : '1.0';
        $css_version = file_exists($css_path) ? filemtime($css_path) : '1.0';

        wp_enqueue_script(
            'boat-contact-modal-js',
            get_template_directory_uri() . '/assets/js/boat-contact-modal.js',
            ['jquery'],
            $js_version,
            true
        );

        wp_enqueue_style(
            'boat-contact-modal-css',
            get_template_directory_uri() . '/assets/css/boat-contact-modal.css',
            [],
            $css_version
        );

        // Localize script for AJAX URL
        wp_localize_script('boat-contact-modal-js', 'boat_vars', [
            'ajaxUrl' => admin_url('admin-ajax.php')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'boat_website_enqueue_assets');

// AJAX Handler for Form Submission
function process_boat_contact_form() {
    // Check honeypot
    if (!empty($_POST['boat_honeypot'])) {
        wp_send_json_error(['message' => 'Spam detected.']);
        exit;
    }

    // Check submission speed
    $submitted_time = intval($_POST['boat_timestamp']);
    if ((time() - $submitted_time) < 5) {
        wp_send_json_error(['message' => 'Form submitted too quickly. Please try again.']);
        exit;
    }

    // Sanitize inputs
    $name = sanitize_text_field(wp_unslash($_POST['boatName']));
    $email = sanitize_email(wp_unslash($_POST['boatEmail']));
    $company = sanitize_text_field(wp_unslash($_POST['boatCompany']));
    $message = sanitize_textarea_field(wp_unslash($_POST['boatMessage']));

    // Prepare admin email
    $admin_email = get_option('admin_email');
    $subject = "New Boat Website Consultation Request from $name";
    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Company: $company\n";
    $body .= "Message: $message";
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>' 
    ];

    // Send email to admin
    wp_mail($admin_email, $subject, $body, $headers);

    // Send confirmation email to user
    $user_subject = "Your Boat Website Consultation Request";
    $user_body = "Hi $name,\n\n";
    $user_body .= "Thank you for requesting a consultation for your boat business, $company. I'll reach out soon to discuss your website needs.\n\n";
    $user_body .= "Best,\nChris Huber";
    wp_mail($email, $user_subject, $user_body, $headers);

    // Send JSON success response
    wp_send_json_success(['message' => 'Thank you! I\'ll contact you soon to discuss your boat website.']);
    exit;
}
add_action('wp_ajax_process_boat_contact_form', 'process_boat_contact_form');
add_action('wp_ajax_nopriv_process_boat_contact_form', 'process_boat_contact_form');