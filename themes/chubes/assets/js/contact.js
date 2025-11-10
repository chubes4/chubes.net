/**
 * Contact Form REST API Handler
 * 
 * Handles form submission via REST API with nonce security.
 * Uses chubes_contact_params object localized from PHP with rest_url and nonce.
 * Displays success/error messages and resets form on successful submission.
 */
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (!contactForm) {
        return;
    }
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = {
            contactName: form.querySelector('[name="contactName"]').value,
            contactEmail: form.querySelector('[name="contactEmail"]').value,
            contactWebsite: form.querySelector('[name="contactWebsite"]').value,
            contactMessage: form.querySelector('[name="contactMessage"]').value,
            contact_honeypot: form.querySelector('[name="contact_honeypot"]').value,
            contact_timestamp: form.querySelector('[name="contact_timestamp"]').value,
            nonce: chubes_contact_params.nonce
        };
        
        // Clear previous messages
        const errorEl = form.querySelector('.contact-error');
        const successEl = form.querySelector('.contact-success');
        errorEl.style.display = 'none';
        errorEl.textContent = '';
        successEl.style.display = 'none';
        successEl.textContent = '';
        
        fetch(chubes_contact_params.rest_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successEl.textContent = data.message;
                successEl.style.display = 'block';
                form.reset();
                // Update timestamp for next submission
                form.querySelector('[name="contact_timestamp"]').value = Math.floor(Date.now() / 1000);
            } else {
                errorEl.textContent = data.message || 'An error occurred. Please try again later.';
                errorEl.style.display = 'block';
            }
        })
        .catch(error => {
            errorEl.textContent = 'An error occurred. Please try again later.';
            errorEl.style.display = 'block';
        });
    });
});
