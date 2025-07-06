<?php
function process_quote_form() {
    // Check honeypot field; if filled, likely spam.
    if ( ! empty( $_POST['website_honeypot'] ) ) {
        wp_die("Spam detected.");
    }
    
    // Check time; if the form is submitted too quickly, it may be a bot.
    $submitted_time = intval( $_POST['quote_timestamp'] );
    if ( ( time() - $submitted_time ) < 5 ) {
        wp_die("Form submitted too quickly. Please try again.");
    }
    
    // Unslash and then sanitize inputs
    $service      = sanitize_text_field( wp_unslash( $_POST['service'] ) );
    $name         = sanitize_text_field( wp_unslash( $_POST['quoteName'] ) );
    $email        = sanitize_email( wp_unslash( $_POST['quoteEmail'] ) );
    $user_website = sanitize_text_field( wp_unslash( $_POST['quoteWebsite'] ) );
    $message      = sanitize_textarea_field( wp_unslash( $_POST['quoteMessage'] ) );
    
    // Prepare the email for admin
    $admin_email = get_option( 'admin_email' );
    $subject     = "New Quote Request: " . $service;
    
    $body  = "Service: " . $service . "\n";
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Website: " . $user_website . "\n";
    $body .= "Message: " . $message;
    
    // Set custom headers to control the sender details
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>'
    ];
    
    wp_mail( $admin_email, $subject, $body, $headers );
    
    // Prepare the confirmation email for the user
    $user_subject = "Your Quote Request for " . $service;
    $user_body  = "Hi " . $name . ",\n\n";
    $user_body .= "Thank you for your quote request regarding " . $service . ". I will get back to you soon.\n\n";
    $user_body .= "Best regards,\n" . get_bloginfo( 'name' );
    
    wp_mail( $email, $user_subject, $user_body, $headers );
    
    // Return a JSON response for AJAX requests
    if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
         strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
        wp_send_json_success( array( 'message' => 'Thank you for your request! I will get back to you soon.' ) );
        exit;
    }
    
    // Fallback redirect
    wp_redirect( home_url( '/thank-you/' ) );
    exit;
}
add_action( 'admin_post_process_quote_form', 'process_quote_form' );
add_action( 'admin_post_nopriv_process_quote_form', 'process_quote_form' );
?>
