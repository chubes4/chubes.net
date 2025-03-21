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
}
add_action('customize_register', 'chubes_customize_register');
