<?php
if (!defined('ABSPATH')) {
    exit;
}

function wp_theme_style_defaults() {
    return [
        'theme_brand_color'      => '#d21629',
        'theme_text_color'       => '#333333',
        'theme_heading_color'    => '#000000',
        'theme_surface_color'    => '#F2F3F3',
        'theme_grey_dark_color'  => '#4a4549',
        'theme_grey_light_color' => '#EDEDED',
        'theme_success_color'    => '#48C52C',
        'theme_danger_color'     => '#FF0000',
        'theme_container_width'  => '1200px',
        'theme_content_width'    => '840px',
        'theme_body_font'        => "'Albert Sans', sans-serif",
        'theme_heading_font'     => "'Albert Sans', sans-serif",
        'theme_body_size'        => '16px',
        'theme_h1_size'          => '45px',
        'theme_h2_size'          => '30px',
        'theme_h3_size'          => '20px',
        'theme_radius'           => '18px',
    ];
}

function wp_theme_style_tokens() {
    $defaults = wp_theme_style_defaults();
    $tokens = [];

    foreach ($defaults as $key => $default) {
        $tokens[$key] = function_exists('get_field') ? (get_field($key, 'option') ?: $default) : $default;
    }

    return $tokens;
}

function wp_theme_register_style_options_page() {
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Theme Style Options', 'wp-theme'),
        'menu_title' => __('Theme Styles', 'wp-theme'),
        'menu_slug'  => 'wp-theme-style-options',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
        'position'   => 61,
    ]);
}
add_action('acf/init', 'wp_theme_register_style_options_page');

function wp_theme_register_style_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_wp_theme_style_options',
        'title' => 'Theme Style Options',
        'fields' => [
            ['key'=>'field_theme_brand_color','label'=>'Brand color','name'=>'theme_brand_color','type'=>'color_picker','default_value'=>'#d21629'],
            ['key'=>'field_theme_text_color','label'=>'Text color','name'=>'theme_text_color','type'=>'color_picker','default_value'=>'#333333'],
            ['key'=>'field_theme_heading_color','label'=>'Heading color','name'=>'theme_heading_color','type'=>'color_picker','default_value'=>'#000000'],
            ['key'=>'field_theme_surface_color','label'=>'Surface color','name'=>'theme_surface_color','type'=>'color_picker','default_value'=>'#F2F3F3'],
            ['key'=>'field_theme_grey_dark_color','label'=>'Grey dark','name'=>'theme_grey_dark_color','type'=>'color_picker','default_value'=>'#4a4549'],
            ['key'=>'field_theme_grey_light_color','label'=>'Grey light','name'=>'theme_grey_light_color','type'=>'color_picker','default_value'=>'#EDEDED'],
            ['key'=>'field_theme_success_color','label'=>'Success color','name'=>'theme_success_color','type'=>'color_picker','default_value'=>'#48C52C'],
            ['key'=>'field_theme_danger_color','label'=>'Danger color','name'=>'theme_danger_color','type'=>'color_picker','default_value'=>'#FF0000'],
            ['key'=>'field_theme_container_width','label'=>'Container width','name'=>'theme_container_width','type'=>'text','default_value'=>'1200px'],
            ['key'=>'field_theme_content_width','label'=>'Content width','name'=>'theme_content_width','type'=>'text','default_value'=>'840px'],
            ['key'=>'field_theme_radius','label'=>'Base radius','name'=>'theme_radius','type'=>'text','default_value'=>'18px'],
            ['key'=>'field_theme_body_font','label'=>'Body font stack','name'=>'theme_body_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif"],
            ['key'=>'field_theme_heading_font','label'=>'Heading font stack','name'=>'theme_heading_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif"],
            ['key'=>'field_theme_body_size','label'=>'Body font size','name'=>'theme_body_size','type'=>'text','default_value'=>'16px'],
            ['key'=>'field_theme_h1_size','label'=>'H1 size','name'=>'theme_h1_size','type'=>'text','default_value'=>'45px'],
            ['key'=>'field_theme_h2_size','label'=>'H2 size','name'=>'theme_h2_size','type'=>'text','default_value'=>'30px'],
            ['key'=>'field_theme_h3_size','label'=>'H3 size','name'=>'theme_h3_size','type'=>'text','default_value'=>'20px'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'wp-theme-style-options']]],
        'style' => 'seamless',
        'active' => true,
    ]);
}
add_action('acf/init', 'wp_theme_register_style_fields');

function wp_theme_style_css_from_tokens($tokens) {
    $map = [
        '--wp-brand-color'      => $tokens['theme_brand_color'],
        '--wp-text-color'       => $tokens['theme_text_color'],
        '--wp-heading-color'    => $tokens['theme_heading_color'],
        '--wp-light-bg-color'   => $tokens['theme_surface_color'],
        '--wp-grey-dark-color'  => $tokens['theme_grey_dark_color'],
        '--wp-grey-light-color' => $tokens['theme_grey_light_color'],
        '--wp-green-color'      => $tokens['theme_success_color'],
        '--wp-red-color'        => $tokens['theme_danger_color'],
        '--wp-container-width'  => $tokens['theme_container_width'],
        '--wp-content-width'    => $tokens['theme_content_width'],
        '--wp-theme-radius'     => $tokens['theme_radius'],
        '--wp-body-font'        => $tokens['theme_body_font'],
        '--wp-headings-font'    => $tokens['theme_heading_font'],
        '--wp-body-size'        => $tokens['theme_body_size'],
        '--wp-h1-font-size'     => $tokens['theme_h1_size'],
        '--wp-h2-font-size'     => $tokens['theme_h2_size'],
        '--wp-h3-font-size'     => $tokens['theme_h3_size'],
    ];

    $css = ':root{';
    foreach ($map as $name => $value) {
        $css .= sprintf('%s:%s;', $name, trim((string) $value));
    }
    $css .= '}';

    return $css;
}

function wp_theme_enqueue_generated_style_tokens() {
    $tokens = wp_theme_style_tokens();
    $css = wp_theme_style_css_from_tokens($tokens);
    if (wp_style_is('wp-theme-style', 'enqueued')) {
        wp_add_inline_style('wp-theme-style', $css);
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);
add_action('admin_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);

function wp_theme_write_generated_style_files($post_id) {
    if ($post_id !== 'options') {
        return;
    }

    $tokens = wp_theme_style_tokens();
    $scss = "/* Auto-generated from ACF Theme Style Options. */\n";
    $scss .= '$acf-brandcolor: ' . $tokens['theme_brand_color'] . " !default;\n";
    $scss .= '$acf-grey-txt: ' . $tokens['theme_text_color'] . " !default;\n";
    $scss .= '$acf-heading-color: ' . $tokens['theme_heading_color'] . " !default;\n";
    $scss .= '$acf-light-bg: ' . $tokens['theme_surface_color'] . " !default;\n";
    $scss .= '$acf-grey-dark: ' . $tokens['theme_grey_dark_color'] . " !default;\n";
    $scss .= '$acf-grey-light: ' . $tokens['theme_grey_light_color'] . " !default;\n";
    $scss .= '$acf-green: ' . $tokens['theme_success_color'] . " !default;\n";
    $scss .= '$acf-red: ' . $tokens['theme_danger_color'] . " !default;\n";
    $scss .= '$acf-container-width: ' . $tokens['theme_container_width'] . " !default;\n";
    $scss .= '$acf-content-width: ' . $tokens['theme_content_width'] . " !default;\n";
    $scss .= '$acf-body-font: ' . $tokens['theme_body_font'] . " !default;\n";
    $scss .= '$acf-headings-font: ' . $tokens['theme_heading_font'] . " !default;\n";
    $scss .= '$acf-body-size: ' . $tokens['theme_body_size'] . " !default;\n";
    $scss .= '$acf-h1-font-size: ' . $tokens['theme_h1_size'] . " !default;\n";
    $scss .= '$acf-h2-font-size: ' . $tokens['theme_h2_size'] . " !default;\n";
    $scss .= '$acf-h3-font-size: ' . $tokens['theme_h3_size'] . " !default;\n";
    $scss .= '$acf-radius: ' . $tokens['theme_radius'] . " !default;\n";

    $css = "/* Auto-generated from ACF Theme Style Options. */\n" . wp_theme_style_css_from_tokens($tokens);

    $scss_file = trailingslashit(get_template_directory()) . 'src/scss/_acf-variables.generated.scss';
    $css_file  = trailingslashit(get_template_directory()) . 'assets/css/acf-theme-vars.css';

    if (wp_is_writable(dirname($scss_file))) {
        file_put_contents($scss_file, $scss);
    }
    if (wp_is_writable(dirname($css_file))) {
        file_put_contents($css_file, $css);
    }
}
add_action('acf/save_post', 'wp_theme_write_generated_style_files', 20);
