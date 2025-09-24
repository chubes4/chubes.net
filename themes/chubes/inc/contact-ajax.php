<?php
/**
 * Contact Form AJAX Handler
 * 
 * Secure AJAX contact form with spam protection.
 */

function contact_enqueue_assets() {
    if ( is_page('contact') || is_page_template('page-contact.php') ) {
        $theme_dir  = get_template_directory_uri();
        $theme_path = get_template_directory();
        
        wp_enqueue_style( 'contact-css', $theme_dir . '/assets/css/contact.css', array(), filemtime( $theme_path . '/assets/css/contact.css' ) );
        wp_enqueue_script( 'contact-js', $theme_dir . '/assets/js/contact.js', array('jquery'), filemtime( $theme_path . '/assets/js/contact.js' ), true );
        
        // Localize the script with the AJAX URL and a security nonce.
        wp_localize_script( 'contact-js', 'chubes_contact_params', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'contact_nonce' ),
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'contact_enqueue_assets' );

function process_contact_form() {
    check_ajax_referer( 'contact_nonce', 'nonce' );
    
    // Honeypot spam check.
    if ( ! empty( $_POST['contact_honeypot'] ) ) {
        wp_send_json_error( array( 'message' => 'Spam detected.' ) );
        wp_die();
    }
    
    // Check that the form wasn't submitted too quickly.
    $submitted_time = intval($_POST['contact_timestamp'] ?? 0);
    if ( ( time() - $submitted_time ) < 5 ) {
        wp_send_json_error( array( 'message' => 'Form submitted too quickly. Please try again.' ) );
        wp_die();
    }
    
    $name    = sanitize_text_field( wp_unslash( $_POST['contactName'] ) );
    $email   = sanitize_email( wp_unslash( $_POST['contactEmail'] ) );
    $website = sanitize_text_field( wp_unslash( $_POST['contactWebsite'] ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['contactMessage'] ) );
    
    // Prepare the email to be sent to the admin.
    $admin_email   = get_option( 'admin_email' );
    $email_subject = 'New Contact Message from Chubes.net';
    
    $email_body  = "Name: {$name}\n";
    $email_body .= "Email: {$email}\n";
    if ( $website ) {
         $email_body .= "Website: {$website}\n";
    }
    $email_body .= "Message:\n{$message}\n";
    
    // Set custom headers: send from chubes@chubes.net.
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>'
    );
    
    // Send email to admin.
    $admin_sent = wp_mail( $admin_email, $email_subject, $email_body, $headers );
    
    // Prepare confirmation email to the user.
    $user_subject = 'Thanks for reaching out!';
    $user_body  = "Hi {$name},\n\n";
    $user_body .= "Thank you for reaching out. I've received your message and will get back to you as soon as possible.\n\n";
    $user_body .= "In the meantime, feel free to check out my work:\n";
    $user_body .= "- WordPress plugins: https://chubes.net/plugins\n";
    $user_body .= "- Documentation: https://chubes.net/docs\n";
    $user_body .= "- Extra Chill music platform: https://extrachill.com\n";
    $user_body .= "- Blog: https://chubes.net/blog\n";
    $user_body .= "- Games: https://chubes.net/game\n\n";
    $user_body .= "Best regards,\nChris Huber";
    
    $user_sent = wp_mail( $email, $user_subject, $user_body, $headers );
    
    if ( $admin_sent ) {
        wp_send_json_success( array( 'message' => 'Thanks for reaching out! I will get back to you as soon as I can.' ) );
    } else {
        wp_send_json_error( array( 'message' => 'An error occurred while sending your message. Please try again later.' ) );
    }
    wp_die();
}
add_action( 'wp_ajax_process_contact_form', 'process_contact_form' );
add_action( 'wp_ajax_nopriv_process_contact_form', 'process_contact_form' );
