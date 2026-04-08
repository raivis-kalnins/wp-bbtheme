<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('wp_theme_style_defaults')) {
    function wp_theme_style_defaults() {
        return [
            'theme_brand_color'       => '#d21629',
            'theme_accent_color'      => '#4a4549',
            'theme_text_color'        => '#333333',
            'theme_heading_color'     => '#000000',
            'theme_background_color'  => '#ffffff',
            'theme_surface_color'     => '#F2F3F3',
            'theme_surface_alt_color' => '#EDEDED',
            'theme_border_color'      => '#d9dde3',
            'theme_grey_dark_color'   => '#4a4549',
            'theme_grey_light_color'  => '#EDEDED',
            'theme_success_color'     => '#48C52C',
            'theme_warning_color'     => '#f59e0b',
            'theme_danger_color'      => '#FF0000',
            'theme_info_color'        => '#2563eb',
            'theme_link_color'        => '#d21629',
            'theme_link_hover_color'  => '#a61222',

            'theme_container_width'   => '1200px',
            'theme_content_width'     => '840px',
            'theme_wide_width'        => '1280px',
            'theme_gutter_width'      => '1.5rem',
            'theme_section_spacing'   => 'clamp(2rem, 4vw, 5rem)',
            'theme_radius'            => '18px',

            'theme_body_font'         => "'Albert Sans', sans-serif",
            'theme_heading_font'      => "'Albert Sans', sans-serif",
            'theme_ui_font'           => "'Albert Sans', sans-serif",
            'theme_small_size'        => '14px',
            'theme_body_size'         => '16px',
            'theme_large_size'        => '20px',
            'theme_h1_size'           => '45px',
            'theme_h2_size'           => '30px',
            'theme_h3_size'           => '20px',
            'theme_h4_size'           => '16px',
            'theme_h5_size'           => '16px',
            'theme_h6_size'           => '12px',
        ];
    }
}

if (!function_exists('wp_theme_style_tokens')) {
    function wp_theme_style_tokens() {
        $defaults = wp_theme_style_defaults();
        $tokens = [];
        foreach ($defaults as $key => $default) {
            $tokens[$key] = function_exists('get_field') ? (get_field($key, 'option') ?: $default) : $default;
        }
        return $tokens;
    }
}

if (!function_exists('wp_theme_register_style_options_page')) {
    function wp_theme_register_style_options_page() {
        if (!function_exists('acf_add_options_page') || !function_exists('acf_add_options_sub_page')) {
            return;
        }

        // Create a parent page if not present already.
        acf_add_options_page([
            'page_title' => __('Theme Settings', 'wp-theme'),
            'menu_title' => __('Theme Settings', 'wp-theme'),
            'menu_slug'  => 'wp-theme-settings',
            'capability' => 'edit_theme_options',
            'redirect'   => true,
            'position'   => 61,
            'icon_url'   => 'dashicons-admin-customizer',
        ]);

        acf_add_options_sub_page([
            'page_title'  => __('Theme Styles', 'wp-theme'),
            'menu_title'  => __('Theme Styles', 'wp-theme'),
            'menu_slug'   => 'wp-theme-style-options',
            'parent_slug' => 'wp-theme-settings',
            'capability'  => 'edit_theme_options',
            'redirect'    => false,
        ]);
    }
}
add_action('acf/init', 'wp_theme_register_style_options_page', 5);

if (!function_exists('wp_theme_register_style_fields')) {
    function wp_theme_register_style_fields() {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key'    => 'group_wp_theme_style_options',
            'title'  => __('Theme Style Options', 'wp-theme'),
            'fields' => [
                ['key'=>'tab_theme_colors','label'=>'Colors','type'=>'tab'],
                ['key'=>'field_theme_brand_color','label'=>'Brand','name'=>'theme_brand_color','type'=>'color_picker','default_value'=>'#d21629'],
                ['key'=>'field_theme_accent_color','label'=>'Accent','name'=>'theme_accent_color','type'=>'color_picker','default_value'=>'#4a4549'],
                ['key'=>'field_theme_text_color','label'=>'Text','name'=>'theme_text_color','type'=>'color_picker','default_value'=>'#333333'],
                ['key'=>'field_theme_heading_color','label'=>'Heading','name'=>'theme_heading_color','type'=>'color_picker','default_value'=>'#000000'],
                ['key'=>'field_theme_background_color','label'=>'Background','name'=>'theme_background_color','type'=>'color_picker','default_value'=>'#ffffff'],
                ['key'=>'field_theme_surface_color','label'=>'Surface','name'=>'theme_surface_color','type'=>'color_picker','default_value'=>'#F2F3F3'],
                ['key'=>'field_theme_surface_alt_color','label'=>'Surface Alt','name'=>'theme_surface_alt_color','type'=>'color_picker','default_value'=>'#EDEDED'],
                ['key'=>'field_theme_border_color','label'=>'Border','name'=>'theme_border_color','type'=>'color_picker','default_value'=>'#d9dde3'],
                ['key'=>'field_theme_grey_dark_color','label'=>'Grey Dark','name'=>'theme_grey_dark_color','type'=>'color_picker','default_value'=>'#4a4549'],
                ['key'=>'field_theme_grey_light_color','label'=>'Grey Light','name'=>'theme_grey_light_color','type'=>'color_picker','default_value'=>'#EDEDED'],
                ['key'=>'field_theme_success_color','label'=>'Success','name'=>'theme_success_color','type'=>'color_picker','default_value'=>'#48C52C'],
                ['key'=>'field_theme_warning_color','label'=>'Warning','name'=>'theme_warning_color','type'=>'color_picker','default_value'=>'#f59e0b'],
                ['key'=>'field_theme_danger_color','label'=>'Danger','name'=>'theme_danger_color','type'=>'color_picker','default_value'=>'#FF0000'],
                ['key'=>'field_theme_info_color','label'=>'Info','name'=>'theme_info_color','type'=>'color_picker','default_value'=>'#2563eb'],
                ['key'=>'field_theme_link_color','label'=>'Link','name'=>'theme_link_color','type'=>'color_picker','default_value'=>'#d21629'],
                ['key'=>'field_theme_link_hover_color','label'=>'Link Hover','name'=>'theme_link_hover_color','type'=>'color_picker','default_value'=>'#a61222'],

                ['key'=>'tab_theme_layout','label'=>'Layout','type'=>'tab'],
                ['key'=>'field_theme_container_width','label'=>'Container Width','name'=>'theme_container_width','type'=>'text','default_value'=>'1200px'],
                ['key'=>'field_theme_content_width','label'=>'Content Width','name'=>'theme_content_width','type'=>'text','default_value'=>'840px'],
                ['key'=>'field_theme_wide_width','label'=>'Wide Width','name'=>'theme_wide_width','type'=>'text','default_value'=>'1280px'],
                ['key'=>'field_theme_gutter_width','label'=>'Gutter','name'=>'theme_gutter_width','type'=>'text','default_value'=>'1.5rem'],
                ['key'=>'field_theme_section_spacing','label'=>'Section Spacing','name'=>'theme_section_spacing','type'=>'text','default_value'=>'clamp(2rem, 4vw, 5rem)'],
                ['key'=>'field_theme_radius','label'=>'Radius','name'=>'theme_radius','type'=>'text','default_value'=>'18px'],

                ['key'=>'tab_theme_typography','label'=>'Typography','type'=>'tab'],
                ['key'=>'field_theme_body_font','label'=>'Body Font Stack','name'=>'theme_body_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif"],
                ['key'=>'field_theme_heading_font','label'=>'Heading Font Stack','name'=>'theme_heading_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif"],
                ['key'=>'field_theme_ui_font','label'=>'UI Font Stack','name'=>'theme_ui_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif"],
                ['key'=>'field_theme_small_size','label'=>'Small Text','name'=>'theme_small_size','type'=>'text','default_value'=>'14px'],
                ['key'=>'field_theme_body_size','label'=>'Body Text','name'=>'theme_body_size','type'=>'text','default_value'=>'16px'],
                ['key'=>'field_theme_large_size','label'=>'Large Text','name'=>'theme_large_size','type'=>'text','default_value'=>'20px'],
                ['key'=>'field_theme_h1_size','label'=>'H1','name'=>'theme_h1_size','type'=>'text','default_value'=>'45px'],
                ['key'=>'field_theme_h2_size','label'=>'H2','name'=>'theme_h2_size','type'=>'text','default_value'=>'30px'],
                ['key'=>'field_theme_h3_size','label'=>'H3','name'=>'theme_h3_size','type'=>'text','default_value'=>'20px'],
                ['key'=>'field_theme_h4_size','label'=>'H4','name'=>'theme_h4_size','type'=>'text','default_value'=>'16px'],
                ['key'=>'field_theme_h5_size','label'=>'H5','name'=>'theme_h5_size','type'=>'text','default_value'=>'16px'],
                ['key'=>'field_theme_h6_size','label'=>'H6','name'=>'theme_h6_size','type'=>'text','default_value'=>'12px'],
            ],
            'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'wp-theme-style-options']]],
            'style'    => 'seamless',
            'active'   => true,
        ]);
    }
}
add_action('acf/init', 'wp_theme_register_style_fields', 20);

if (!function_exists('wp_theme_style_css_from_tokens')) {
    function wp_theme_style_css_from_tokens($tokens) {
        $map = [
            '--wp-brand-color'       => $tokens['theme_brand_color'],
            '--wp-accent-color'      => $tokens['theme_accent_color'],
            '--wp-text-color'        => $tokens['theme_text_color'],
            '--wp-heading-color'     => $tokens['theme_heading_color'],
            '--wp-background-color'  => $tokens['theme_background_color'],
            '--wp-light-bg-color'    => $tokens['theme_surface_color'],
            '--wp-surface-alt-color' => $tokens['theme_surface_alt_color'],
            '--wp-border-color'      => $tokens['theme_border_color'],
            '--wp-grey-dark-color'   => $tokens['theme_grey_dark_color'],
            '--wp-grey-light-color'  => $tokens['theme_grey_light_color'],
            '--wp-green-color'       => $tokens['theme_success_color'],
            '--wp-warning-color'     => $tokens['theme_warning_color'],
            '--wp-red-color'         => $tokens['theme_danger_color'],
            '--wp-info-color'        => $tokens['theme_info_color'],
            '--wp-link-color'        => $tokens['theme_link_color'],
            '--wp-link-hover-color'  => $tokens['theme_link_hover_color'],
            '--wp-container-width'   => $tokens['theme_container_width'],
            '--wp-content-width'     => $tokens['theme_content_width'],
            '--wp-wide-width'        => $tokens['theme_wide_width'],
            '--wp-gutter-width'      => $tokens['theme_gutter_width'],
            '--wp-section-spacing'   => $tokens['theme_section_spacing'],
            '--wp-theme-radius'      => $tokens['theme_radius'],
            '--wp-body-font'         => $tokens['theme_body_font'],
            '--wp-headings-font'     => $tokens['theme_heading_font'],
            '--wp-ui-font'           => $tokens['theme_ui_font'],
            '--wp-font-size-small'   => $tokens['theme_small_size'],
            '--wp-body-size'         => $tokens['theme_body_size'],
            '--wp-font-size-medium'  => $tokens['theme_large_size'],
            '--wp-h1-font-size'      => $tokens['theme_h1_size'],
            '--wp-h2-font-size'      => $tokens['theme_h2_size'],
            '--wp-h3-font-size'      => $tokens['theme_h3_size'],
            '--wp-h4-font-size'      => $tokens['theme_h4_size'],
            '--wp-h5-font-size'      => $tokens['theme_h5_size'],
            '--wp-h6-font-size'      => $tokens['theme_h6_size'],
        ];

        $css = ":root{";
        foreach ($map as $name => $value) {
            $css .= sprintf('%s:%s;', esc_html($name), trim((string) $value));
        }
        $css .= "}";
        return $css;
    }
}

if (!function_exists('wp_theme_output_style_tokens')) {
    function wp_theme_output_style_tokens() {
        $tokens = wp_theme_style_tokens();
        $css = wp_theme_style_css_from_tokens($tokens);
        echo '<style id="wp-theme-style-tokens">' . $css . '</style>';
    }
}
add_action('wp_head', 'wp_theme_output_style_tokens', 8);
add_action('admin_head', 'wp_theme_output_style_tokens', 8);

if (!function_exists('wp_theme_enqueue_generated_style_tokens')) {
    function wp_theme_enqueue_generated_style_tokens() {
        $tokens = wp_theme_style_tokens();
        $css = wp_theme_style_css_from_tokens($tokens);
        if (wp_style_is('wp-theme-style', 'enqueued')) {
            wp_add_inline_style('wp-theme-style', $css);
        }
        if (wp_style_is('wp-theme-dist', 'enqueued')) {
            wp_add_inline_style('wp-theme-dist', $css);
        }
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);
add_action('admin_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);

if (!function_exists('wp_theme_write_generated_style_files')) {
    function wp_theme_write_generated_style_files($post_id) {
        if ($post_id !== 'options') {
            return;
        }

        $tokens = wp_theme_style_tokens();

        $scss = "/* Auto-generated from ACF Theme Style Options. */\n";
        $scss .= '$acf-brandcolor: ' . $tokens['theme_brand_color'] . " !default;\n";
        $scss .= '$acf-accent-color: ' . $tokens['theme_accent_color'] . " !default;\n";
        $scss .= '$acf-grey-txt: ' . $tokens['theme_text_color'] . " !default;\n";
        $scss .= '$acf-heading-color: ' . $tokens['theme_heading_color'] . " !default;\n";
        $scss .= '$acf-background-color: ' . $tokens['theme_background_color'] . " !default;\n";
        $scss .= '$acf-light-bg: ' . $tokens['theme_surface_color'] . " !default;\n";
        $scss .= '$acf-surface-alt: ' . $tokens['theme_surface_alt_color'] . " !default;\n";
        $scss .= '$acf-border-color: ' . $tokens['theme_border_color'] . " !default;\n";
        $scss .= '$acf-grey-dark: ' . $tokens['theme_grey_dark_color'] . " !default;\n";
        $scss .= '$acf-grey-light: ' . $tokens['theme_grey_light_color'] . " !default;\n";
        $scss .= '$acf-green: ' . $tokens['theme_success_color'] . " !default;\n";
        $scss .= '$acf-warning: ' . $tokens['theme_warning_color'] . " !default;\n";
        $scss .= '$acf-red: ' . $tokens['theme_danger_color'] . " !default;\n";
        $scss .= '$acf-info: ' . $tokens['theme_info_color'] . " !default;\n";
        $scss .= '$acf-link-color: ' . $tokens['theme_link_color'] . " !default;\n";
        $scss .= '$acf-link-hover-color: ' . $tokens['theme_link_hover_color'] . " !default;\n";
        $scss .= '$acf-container-width: ' . $tokens['theme_container_width'] . " !default;\n";
        $scss .= '$acf-content-width: ' . $tokens['theme_content_width'] . " !default;\n";
        $scss .= '$acf-wide-width: ' . $tokens['theme_wide_width'] . " !default;\n";
        $scss .= '$acf-gutter-width: ' . $tokens['theme_gutter_width'] . " !default;\n";
        $scss .= '$acf-section-spacing: ' . $tokens['theme_section_spacing'] . " !default;\n";
        $scss .= '$acf-body-font: ' . $tokens['theme_body_font'] . " !default;\n";
        $scss .= '$acf-headings-font: ' . $tokens['theme_heading_font'] . " !default;\n";
        $scss .= '$acf-ui-font: ' . $tokens['theme_ui_font'] . " !default;\n";
        $scss .= '$acf-small-size: ' . $tokens['theme_small_size'] . " !default;\n";
        $scss .= '$acf-body-size: ' . $tokens['theme_body_size'] . " !default;\n";
        $scss .= '$acf-large-size: ' . $tokens['theme_large_size'] . " !default;\n";
        $scss .= '$acf-h1-font-size: ' . $tokens['theme_h1_size'] . " !default;\n";
        $scss .= '$acf-h2-font-size: ' . $tokens['theme_h2_size'] . " !default;\n";
        $scss .= '$acf-h3-font-size: ' . $tokens['theme_h3_size'] . " !default;\n";
        $scss .= '$acf-h4-font-size: ' . $tokens['theme_h4_size'] . " !default;\n";
        $scss .= '$acf-h5-font-size: ' . $tokens['theme_h5_size'] . " !default;\n";
        $scss .= '$acf-h6-font-size: ' . $tokens['theme_h6_size'] . " !default;\n";
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
}
add_action('acf/save_post', 'wp_theme_write_generated_style_files', 20);
