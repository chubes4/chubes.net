<?php
/**
 * Contact Form Block Template
 */
?>

<div <?php echo get_block_wrapper_attributes(array('class' => 'contact-form-section')); ?>>
    <div class="container">
        <form id="contactForm" class="contact-form" method="post">
            <h2>Get in Touch</h2>
            <!-- Honeypot Field for Spam Protection -->
            <div class="contact-honeypot">
                <label for="contact_honeypot">Leave this field empty</label>
                <input type="text" id="contact_honeypot" name="contact_honeypot" value="">
            </div>
            
            <!-- Timestamp Field for Bot Check -->
            <input type="hidden" name="contact_timestamp" id="contact_timestamp" value="<?php echo time(); ?>">
            
            <label for="contactName">Name</label>
            <input type="text" id="contactName" name="contactName" required>
            
            <label for="contactEmail">Email</label>
            <input type="email" id="contactEmail" name="contactEmail" required>
            
            <label for="contactWebsite">Website (Optional)</label>
            <input type="text" id="contactWebsite" name="contactWebsite" placeholder="https://example.com">
            
            <label for="contactMessage">Message</label>
            <textarea id="contactMessage" name="contactMessage" required></textarea>
            
            <button type="submit" class="btn">Send Message</button>
            
            <!-- Error & Success Message Containers --> 
            <p class="contact-error"></p>
            <p class="contact-success"></p>
        </form>
    </div>
</div>
