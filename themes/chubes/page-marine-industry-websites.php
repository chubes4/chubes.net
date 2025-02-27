<?php get_header(); ?>

<main class="site-main single-post">
    <section class="post-content">
        <div class="container">
            <!-- Post Title -->
            <h1><?php the_title(); ?></h1>

            <!-- Sticky Navigation -->
            <nav class="section-nav sticky">
                <ul>
                    <li><a href="#tour-boat-charters">Tour Boat Charters</a></li>
                    <li><a href="#boat-repair-shops">Boat Repair Shops</a></li>
                    <li><a href="#boat-dealerships">Boat Dealerships</a></li>
                    <li><a href="#marine-supply-stores">Marine Supply Stores</a></li>
                    <li><a href="#marinas">Marinas</a></li>
                </ul>
            </nav>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-image">
                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-body">
                <?php the_content(); ?>
            </div>

            <!-- Back to Homepage -->
            <div class="post-navigation custom-boat-websites">
                <a href="<?php echo home_url(); ?>" class="btn secondary">
                    ← Back to Chubes.net
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>

    <!-- Contact Modal Markup -->
    <div id="boatContactModal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <h2 id="boatModalTitle">Request a Free Consultation</h2>
            <form id="boatContactForm" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="process_boat_contact_form">
                
                <!-- Honeypot field -->
                <div style="display:none;">
                    <label for="boat_honeypot">Website</label>
                    <input type="text" name="boat_honeypot" id="boat_honeypot">
                </div>
                
                <!-- Timestamp -->
                <input type="hidden" name="boat_timestamp" id="boatTimestamp" value="<?php echo time(); ?>">
                
                <label for="boatName">Name</label>
                <input type="text" id="boatName" name="boatName" required>
                
                <label for="boatEmail">Email</label>
                <input type="email" id="boatEmail" name="boatEmail" required>
                
                <label for="boatCompany">Company Name</label>
                <input type="text" id="boatCompany" name="boatCompany" required>
                
                <label for="boatMessage">Tell Me About Your Boat Business</label>
                <textarea id="boatMessage" name="boatMessage" required></textarea>
                
                <button type="submit" class="btn">Submit</button>
                <p class="contact-error" style="display:none; color:red; text-align:center; margin-top:10px;"></p>
            </form>
        </div>
    </div>