<?php
/**
 * Theme Customizer Settings
 * 
 * Registers customizer sections and controls for editable theme content.
 */

/**
 * Register Customizer Settings for Hero Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function chubes_customize_register($wp_customize) {
    
    $wp_customize->add_section('chubes_hero_section', array(
        'title'    => __('Hero Section', 'chubes'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('chubes_hero_heading', array(
        'default'           => 'Home of Chris Huber, a.k.a. Chubes',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('chubes_hero_heading', array(
        'label'   => __('Hero Heading', 'chubes'),
        'section' => 'chubes_hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('chubes_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'chubes_hero_image', array(
        'label'     => __('Hero Image', 'chubes'),
        'section'   => 'chubes_hero_section',
        'mime_type' => 'image',
    )));

    $default_bio = "WordPress developer & music journalist based in Austin, TX.\n\n";
    $default_bio .= "Founder of Extra Chill, an independent music platform. Creator of Data Machine, an AI-powered automation plugin for WordPress.\n\n";
    $default_bio .= "I love to experiment and learn new things.\n\n";
    $default_bio .= "This website is filled with information about my projects and my life.\n\n";
    $default_bio .= "Perpetually under construction.";

    $wp_customize->add_setting('chubes_hero_bio', array(
        'default'           => $default_bio,
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('chubes_hero_bio', array(
        'label'   => __('Hero Bio', 'chubes'),
        'section' => 'chubes_hero_section',
        'type'    => 'textarea',
    ));
}
add_action('customize_register', 'chubes_customize_register');
