<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9GBTD0EBFS"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-9GBTD0EBFS');
</script>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="container">
        <!-- Site Title / Logo -->
        <h1 class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php bloginfo('name'); ?>
            </a>
        </h1>
    </div>
<!-- Minimal Two-Bar Icon -->
<div class="hamburger" id="hamburger">
  <span class="bar bar1"></span>
  <span class="bar bar2"></span>
</div>

<!-- Fullscreen Overlay -->
<div class="nav-overlay" id="overlay">
  <nav class="overlay-nav">
    <ul>
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
      <li><a href="<?php echo esc_url( home_url( '/portfolio' ) ); ?>">Portfolio</a></li>
      <li><a href="<?php echo esc_url( home_url( '/services' ) ); ?>">Services</a></li>
      <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
      <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
    </ul>
  </nav>
</div>
</header>

<?php
// Action hook for content before main - used for breadcrumbs
do_action('chubes_before_main_content');
?>
