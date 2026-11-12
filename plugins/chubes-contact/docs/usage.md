# Usage Guide

## Contact Form Block

Add a secure contact form to any post or page using the Gutenberg editor.

### Features

- AJAX form submission
- Client-side validation
- Server-side security checks
- Honeypot spam protection
- Timestamp validation
- Email notifications

### Usage

1. Edit a post or page in Gutenberg
2. Add the "Contact Form" block
3. Configure form settings in the block sidebar
4. Publish the page

### Form Fields

- Name (required)
- Email (required)
- Subject (optional)
- Message (required)
- Honeypot field (hidden)

## REST API

### Endpoint

`POST /wp-json/chubes/v1/contact`

### Parameters

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "Contact Form Submission",
  "message": "Hello, I have a question...",
  "_wpnonce": "security_nonce"
}
```

### Response

Success:
```json
{
  "success": true,
  "message": "Message sent successfully"
}
```

Error:
```json
{
  "success": false,
  "message": "Validation error"
}
```

## Security Features

- WordPress nonce verification
- Honeypot spam protection
- Timestamp validation (prevents rapid submissions)
- Input sanitization
- Email validation

## Configuration

Form behavior can be customized via WordPress filters:

- `chubes_contact_form_fields` - Modify form fields
- `chubes_contact_email_recipient` - Change recipient email
- `chubes_contact_validation_rules` - Custom validation

## Email Notifications

Submitted forms are sent to the site admin email by default. Customize via:

```php
add_filter('chubes_contact_email_recipient', function($email) {
    return 'custom@email.com';
});
```