<?php
/*
Template Name: Services Page
*/
get_header(); 

$services = [
    [
        'title' => 'Web Development',
        'description' => "Transform your online presence with a custom or template-based website designed for speed, responsiveness, and engagement.\n\n• Stunning, mobile-first designs\n• Feature-rich, custom builds (including membership areas and advanced integrations)\n• Engineered for performance & SEO success\n• Specialized solutions are available for <a href='".home_url('/services/marine-industry-websites')."'>the marine industry</a> and <a href='/portfolio/music-venue-website'>the music industry</a>",
        'price' => "Starting at \$1,000 – \$5,000+",
    ],
    [
        'title' => 'SEO Optimization',
        'description' => "Boost your visibility with targeted SEO strategies focused on on-page excellence and technical precision.\n\n• Comprehensive on-page audits & keyword research\n• In-depth technical SEO evaluations & site speed enhancements\n• Tailored strategies to drive organic growth\n• <a href='".home_url('/free-local-seo-audits')."'>Free Local SEO Audits</a>",
        'price' => "Starting at \$500 – \$2,500+",
    ],
    [
        'title' => 'Content Strategy',
        'description' => "Engage your audience with compelling content that drives conversions and builds your brand.\n\n• Expert editorial planning & strategic topic research\n• High-quality blog posts, newsletters, and multimedia content\n• Custom messaging that captures your unique brand voice",
        'price' => "Starting at \$500 – \$2,000+",
    ],
    [
        'title' => 'AI Tools Integration',
        'description' => "Empower your business with cutting-edge AI solutions designed to streamline workflows and fuel innovation.\n\n• Seamless ChatGPT API integrations & custom chatbot development\n• Intelligent automation workflows & predictive analytics\n• Future-proof your operations with advanced technology",
        'price' => "Starting at \$1,000 – \$5,000+",
    ],
    [
        'title' => 'Custom WordPress Features',
        'description' => "Elevate your WordPress site with bespoke features that boost functionality and performance.\n\n• Single feature enhancements (e.g., custom forms, plugin tweaks)\n• Advanced integrations (APIs, custom post types)\n• Tailored solutions to optimize your site’s potential",
        'price' => "Starting at \$500 – \$5,000+",
    ],
    [
        'title' => 'Performance Optimization',
        'description' => "Deliver a seamless user experience by ensuring your website loads fast and performs flawlessly.\n\n• Core Web Vitals improvements & page speed enhancements\n• Advanced image optimization & caching techniques\n• In-depth technical audits to eliminate performance bottlenecks",
        'price' => "Starting at \$500 – \$2,000+",
    ],
    [
        'title' => 'Website Maintenance & Support',
        'description' => "Keep your site secure, updated, and running at peak performance with our proactive maintenance plans.\n\n• Timely security patches & regular plugin updates\n• Continuous performance monitoring & health checks\n• Priority support for rapid troubleshooting and fixes",
        'price' => "Plans from \$100/month",
    ],
    [
        'title' => 'E-Commerce Optimization',
        'description' => "Maximize your online store’s potential with a tailored approach to user experience and conversion.\n\n• Strategic enhancements for WooCommerce or Shopify stores\n• Streamlined checkout processes & effective cart recovery solutions\n• Optimized product pages to boost sales",
        'price' => "Starting at \$1,000 – \$5,000+",
    ],
    [
        'title' => 'Marketing Automation',
        'description' => "Revolutionize your marketing strategy with intelligent automation that drives leads and revenue.\n\n• AI-powered email campaigns & personalized follow-ups\n• Social media automation with API integrations\n• Optimized lead capture forms and targeted retargeting strategies",
        'price' => "Starting at \$500 – \$3,000+",
    ],
];
?>

<section class="post-content">
  <div class="container">
      <h1><?php the_title(); ?></h1>
      <div class="post-body">
          <?php the_content(); ?>
      </div>
  </div>
</section>

<section class="services-grid container">
    <?php foreach ($services as $service) : ?>
        <div class="service-item">
            <div class="service-header">
                <h3><?php echo $service['title']; ?></h3>
                <span class="dropdown-arrow">&#9662;</span> <!-- Unicode down arrow -->
            </div>
            <div class="service-content">
                <p><?php echo nl2br($service['description']); ?></p>
                <span class="price"><?php echo $service['price']; ?></span>
                <button class="btn get-quote">Let’s Build Something Great →</button>
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
    <span class="close">&times;</span>
    <h2 id="quoteModalTitle">Request Quote</h2>
    <form id="quoteForm" method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
      <!-- Use a custom action name so we can hook into it in PHP -->
      <input type="hidden" name="action" value="process_quote_form">
      <!-- Hidden field to store the selected service title -->
      <input type="hidden" name="service" id="quoteService" value="">
      
      <!-- Honeypot field (should remain empty) -->
      <div style="display:none;">
         <label for="website_honeypot">Website</label>
         <input type="text" name="website_honeypot" id="website_honeypot">
      </div>
      
      <!-- Timestamp to check for bots -->
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
      <!-- Error message container -->
      <p class="quote-error" style="display:none; color:red; text-align:center; margin-top:10px;"></p>
    </form>
  </div>
</div>




