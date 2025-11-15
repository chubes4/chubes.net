<?php
/**
 * Contact Form REST API Endpoint
 * 
 * Provides REST endpoint for secure contact form submissions with spam protection.
 * Endpoint: POST /wp-json/chubes/v1/contact
 */

/**
 * Register Contact Form REST API Endpoint
 *
 * Creates the REST API endpoint for secure contact form submissions.
 * Endpoint: POST /wp-json/chubes/v1/contact
 * Includes comprehensive validation rules for all form fields.
 */
function register_contact_rest_endpoint() {
    register_rest_route( 'chubes/v1', '/contact', array(
        'methods'             => 'POST',
        'callback'            => 'handle_contact_form_rest',
        'permission_callback' => '__return_true', // Allow unauthenticated requests
        'args'                => array(
            'contactName'     => array(
                'type'     => 'string',
                'required' => true,
            ),
            'contactEmail'    => array(
                'type'     => 'string',
                'required' => true,
                'format'   => 'email',
            ),
            'contactWebsite'  => array(
                'type'     => 'string',
                'required' => false,
            ),
            'contactMessage'  => array(
                'type'     => 'string',
                'required' => true,
            ),
            'contact_honeypot' => array(
                'type'     => 'string',
                'required' => false,
            ),
            'contact_timestamp' => array(
                'type'     => 'integer',
                'required' => true,
            ),
            'nonce'           => array(
                'type'     => 'string',
                'required' => true,
            ),
        ),
    ) );
}
add_action( 'rest_api_init', 'register_contact_rest_endpoint' );

/**
 * Handle Contact Form REST API Submission
 *
 * Processes contact form submissions with comprehensive security checks including
 * nonce verification, honeypot spam protection, and timing validation.
 * Sends notification emails to both admin and user.
 *
 * @param WP_REST_Request $request The REST API request object
 * @return WP_REST_Response|WP_Error Success response or error object
 */
function handle_contact_form_rest( $request ) {
    // Verify nonce.
    $nonce = $request->get_param( 'nonce' );
    if ( ! wp_verify_nonce( $nonce, 'contact_nonce' ) ) {
        return new WP_Error( 'invalid_nonce', 'Security check failed.', array( 'status' => 403 ) );
    }
    
    // Honeypot spam check.
    $honeypot = $request->get_param( 'contact_honeypot' );
    if ( ! empty( $honeypot ) ) {
        return new WP_Error( 'spam_detected', 'Spam detected.', array( 'status' => 400 ) );
    }
    
    // Check that the form wasn't submitted too quickly.
    $submitted_time = intval( $request->get_param( 'contact_timestamp' ) );
    if ( ( time() - $submitted_time ) < 5 ) {
        return new WP_Error( 'too_fast', 'Form submitted too quickly. Please try again.', array( 'status' => 429 ) );
    }
    
    // Sanitize input.
    $name    = sanitize_text_field( $request->get_param( 'contactName' ) );
    $email   = sanitize_email( $request->get_param( 'contactEmail' ) );
    $website = sanitize_text_field( $request->get_param( 'contactWebsite' ) );
    $message = sanitize_textarea_field( $request->get_param( 'contactMessage' ) );
    
    // Prepare the email to be sent to the admin.
    $admin_email   = get_option( 'admin_email' );
    $email_subject = 'New Contact Message from Chubes.net';
    
    $email_body  = "Name: {$name}\n";
    $email_body .= "Email: {$email}\n";
    if ( $website ) {
        $email_body .= "Website: {$website}\n";
    }
    $email_body .= "Message:\n{$message}\n";
    
    // Set custom headers: send from chubes@chubes.net, with reply-to as submitter's email.
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: Chris Huber <chubes@chubes.net>',
        'Reply-To: ' . $email
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
    $user_body .= "Best regards,\nChris Huber";
    
    $user_sent = wp_mail( $email, $user_subject, $user_body, $headers );
    
    if ( $admin_sent ) {
        return rest_ensure_response( array(
            'success' => true,
            'message' => 'Thanks for reaching out! I will get back to you as soon as I can.',
        ) );
    } else {
        return new WP_Error( 'email_failed', 'An error occurred while sending your message. Please try again later.', array( 'status' => 500 ) );
    }
}
