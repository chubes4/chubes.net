<?php

function chubes_customize_register($wp_customize) {
    $wp_customize->add_section('why_choose_me_section', array(
        'title'    => __('Why Choose Me Image', 'chubes-theme'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('why_choose_me_image', array(
        'default'   => get_template_directory_uri() . '/images/default-image.jpg',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'why_choose_me_image_control', array(
        'label'    => __('Upload an Image', 'chubes-theme'),
        'section'  => 'why_choose_me_section',
        'settings' => 'why_choose_me_image',
    )));

    // Trust Logos Section
    $wp_customize->add_section('trust_logos_section', array(
        'title'    => __('Trust Logos', 'chubes'),
        'priority' => 35,
    ));

    // WordPress Logo
    $wp_customize->add_setting('wordpress_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'wordpress_logo', array(
        'label'       => __('WordPress Logo', 'chubes'),
        'section'     => 'trust_logos_section',
        'settings'    => 'wordpress_logo',
        'mime_type'   => 'image',
        'description' => __('Upload the WordPress logo image', 'chubes'),
    )));

    // Chrome Logo
    $wp_customize->add_setting('chrome_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'chrome_logo', array(
        'label'       => __('Chrome Logo', 'chubes'),
        'section'     => 'trust_logos_section',
        'settings'    => 'chrome_logo',
        'mime_type'   => 'image',
        'description' => __('Upload the Chrome logo image', 'chubes'),
    )));

    // Additional Logo 1 (for future use)
    $wp_customize->add_setting('additional_logo_1', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'additional_logo_1', array(
        'label'       => __('Additional Logo 1', 'chubes'),
        'section'     => 'trust_logos_section',
        'settings'    => 'additional_logo_1',
        'mime_type'   => 'image',
        'description' => __('Upload an additional logo (optional)', 'chubes'),
    )));

    // Additional Logo 2 (for future use)
    $wp_customize->add_setting('additional_logo_2', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'additional_logo_2', array(
        'label'       => __('Additional Logo 2', 'chubes'),
        'section'     => 'trust_logos_section',
        'settings'    => 'additional_logo_2',
        'mime_type'   => 'image',
        'description' => __('Upload an additional logo (optional)', 'chubes'),
    )));
}
add_action('customize_register', 'chubes_customize_register');
