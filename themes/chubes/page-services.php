<?php
/*
Template Name: Services Page
*/
get_header(); 

$services = [
    [
        'title' => 'Small Business Web Development',
        'id' => 'web-development',
        'description' => "Custom websites built with grit and purpose—no templates, no bloat, just clean code that drives real results for your local business.\n\n• Fast-loading, mobile-first designs\n• Custom features built specifically for your business needs\n• Hand-coded for peak performance and SEO success\n• Specialized solutions for <a href='".home_url('/services/marine-industry-websites')."'>the marine industry</a> and <a href='/portfolio/music-venue-website'>the music industry</a>",
        'price' => "Starting at \$1,500 – \$5,000+",
    ],
    [
        'title' => 'Local SEO & Performance',
        'id' => 'local-seo',
        'description' => "Turn your website into a local powerhouse. Data-driven optimization that brings customers to your door, proven by my own success stories.\n\n• Comprehensive local SEO audits & strategic keyword mapping\n• Google Business Profile optimization & citation building\n• Performance tuning that Google rewards\n• <a href='".home_url('/services/free-local-seo-audits')."'>Free Local SEO Audits</a>",
        'price' => "Starting at \$750 – \$2,500+",
    ],
    [
        'title' => 'Content Marketing Strategy',
        'id' => 'content-marketing',
        'description' => "Scale your content like I scaled Extra Chill to 300k monthly visitors. Leverage a comprehensive content marketing strategy that combines smart automation with creative, strategic planning—so your content works for you 24/7.\n\n• Strategic planning for organic growth\n• High-converting blog posts and landing pages\n• Email campaigns that build lasting customer relationships and drive revenue",
        'price' => "Starting at \$500 – \$2,000+",
    ],
    
    [
        'title' => 'AI & Automation',
        'id' => 'ai-automation',
        'description' => "Transform into a digital powerhouse with custom AI integration services for small businesses. Harness intelligent tools that automate tasks, enhance customer support, and deliver actionable insights.\n\n• Chatbot development & virtual assistants\n• Workflow automation & RPA solutions\n• AI-driven marketing and content enhancement\n• Custom AI integrations that give you a competitive edge",
        'price' => "Starting at \$1,000 – \$5,000+",
    ],
    [
        'title' => 'WordPress Customization',
        'id' => 'wordpress-customization',
        'description' => "Get exactly what you need, not what a theme forces on you. Custom WordPress solutions that make your site work for your business.\n\n• Custom post types and fields for your specific industry\n• Plugin customizations and integrations\n• Performance-focused enhancements",
        'price' => "Starting at \$500 – \$5,000+",
    ],
    [
        'title' => 'Performance Optimization',
        'id' => 'performance-optimization',
        'description' => "Speed wins in today's digital landscape. Turn your sluggish site into a conversion machine with targeted optimizations.\n\n• Core Web Vitals improvements that Google rewards\n• Advanced caching and image optimization\n• Technical debt elimination and code cleanup",
        'price' => "Starting at \$500 – \$2,000+",
    ],
    [
        'title' => 'Website Maintenance',
        'id' => 'website-maintenance',
        'description' => "Focus on your business while I handle your website. Proactive maintenance that prevents issues before they happen.\n\n• Security hardening and regular updates\n• Performance monitoring and optimization\n• Technical support when you need it most",
        'price' => "Plans from \$100/month",
    ],
    [
        'title' => 'E-Commerce Solutions',
        'id' => 'ecommerce-solutions',
        'description' => "Turn browsers into buyers with an e-commerce experience built for conversions and growth.\n\n• Custom WooCommerce or Shopify enhancements\n• Streamlined checkout processes that reduce abandonment\n• Integration with your inventory and shipping workflows",
        'price' => "Starting at \$1,000 – \$5,000+",
    ],
    [
        'title' => 'Marketing Automation',
        'id' => 'marketing-automation',
        'description' => "Put your marketing on autopilot without losing the personal touch that wins customers.\n\n• Email sequences that nurture leads into customers\n• Social media automation that maintains your authentic voice\n• Lead generation systems that work 24/7",
        'price' => "Starting at \$500 – \$3,000+",
    ],
];
?>

<section class="post-content">
  <div class="container">
      <h1><?php the_title(); ?></h1>
      <div class="services-intro">
          <p>No cookie-cutter solutions or unnecessary features—just custom digital work that delivers measurable results for your business. Each service is built on battle-tested strategies I've used to grow my own projects.</p>
          <p>Click any service below to learn more or request a quote.</p>
      </div>
      <div class="global-quote-button">
          <button class="btn get-quote">Get a Quote Now</button>
      </div>
  </div>
</section>

<section class="services-grid container">
<?php foreach ($services as $service) : ?>
    <div class="service-item" id="<?php echo $service['id']; ?>">
        <div class="service-header">
            <div class="service-title-area">
                <h3><?php echo $service['title']; ?></h3>
            </div>
            <span class="dropdown-arrow">▾</span>
        </div>
        <div class="service-content">
            <p><?php echo $service['description']; ?></p>
            <span class="price"><?php echo $service['price']; ?></span>
            <?php 
            if ($service['id'] === 'web-development') : ?>
                <a href="<?php echo home_url('/services/web-development/'); ?>" class="btn">Learn More →</a>
            <?php elseif ($service['id'] === 'local-seo') : ?>
                <a href="<?php echo home_url('/services/local-seo/'); ?>" class="btn">Learn More →</a>
            <?php elseif ($service['id'] === 'ai-automation') : ?>
                <a href="<?php echo home_url('/services/ai-integration/'); ?>" class="btn">Learn More →</a>
            <?php elseif ($service['id'] === 'wordpress-customization') : ?>
                <a href="<?php echo home_url('/services/wordpress-customization/'); ?>" class="btn">Learn More →</a>
            <?php else : ?>
                <button class="btn get-quote">Let's Build Something Great →</button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

</section>

<!-- Back to Homepage -->
<div class="post-navigation">
    <a href="<?php echo home_url(); ?>" class="btn secondary">
        ← Back to Chubes.net
    </a>
</div>

<?php get_footer(); ?>

<!-- Quote Modal Markup -->
<div id="quoteModal" class="modal">
  <div class="modal-content">
    <span class="close">×</span>
    <h2 id="quoteModalTitle">Request Quote</h2>
    <form id="quoteForm" method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
      <input type="hidden" name="action" value="process_quote_form">
      <input type="hidden" name="service" id="quoteService" value="">
      
      <div style="display:none;">
         <label for="website_honeypot">Website</label>
         <input type="text" name="website_honeypot" id="website_honeypot">
      </div>
      
      <input type="hidden" name="quote_timestamp" id="quoteTimestamp" value="<?php echo time(); ?>">
      
      <label for="quoteName">Name</label>
      <input type="text" id="quoteName" name="quoteName" required>
      
      <label for="quoteEmail">Email</label>
      <input type="email" id="quoteEmail" name="quoteEmail" required>
      
      <label for="quoteWebsite">Website URL (if relevant)</label>
      <input type="text" id="quoteWebsite" name="quoteWebsite">
      
      <label for="quoteMessage">What Are You Looking to Accomplish?</label>
      <textarea id="quoteMessage" name="quoteMessage" required></textarea>
      
      <button type="submit" class="btn">Submit</button>
      <p class="error-message"></p>
      <p class="success-message"></p>
    </form>
  </div>
</div>
