<?php do_action('chubes_above_footer'); ?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-nav">
            <ul>
                <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                <li><a href="<?php echo home_url('/docs'); ?>">Documentation</a></li>
                <li><a href="<?php echo home_url('/blog'); ?>">Blog</a></li>
                <li><a href="<?php echo home_url('/journal'); ?>">Journal</a></li>
            </ul>
        </div>




        <div class="footer-info">
            <p><a href="mailto:chubes@chubes.net" class="footer-cta">chubes@chubes.net</a></p>
            <?php 
// Build the sprite URL with versioning based on file modification time.
$social_sprite = get_stylesheet_directory_uri() . '/assets/fonts/social-icons.svg?ver=' . filemtime(get_stylesheet_directory() . '/assets/fonts/social-icons.svg');
?>
<ul class="social-links">
  <li>
    <a href="https://x.com/chubes4" target="_blank" rel="noopener noreferrer">
      <svg class="icon icon-twitter">
        <use xlink:href="<?php echo $social_sprite; ?>#icon-twitter"></use>
      </svg>
    </a>
  </li>
  <li>
    <a href="https://instagram.com/chubes4" target="_blank" rel="noopener noreferrer">
      <svg class="icon icon-instagram">
        <use xlink:href="<?php echo $social_sprite; ?>#icon-instagram"></use>
      </svg>
    </a>
  </li>
  <li>
    <a href="https://github.com/chubes4" target="_blank" rel="noopener noreferrer">
      <svg class="icon icon-github">
        <use xlink:href="<?php echo $social_sprite; ?>#icon-github"></use>
      </svg>
    </a>
  </li>
</ul>

            <p>© <?php echo date('Y'); ?> chubes.net. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
