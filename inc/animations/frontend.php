<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('bbtheme_get_core_animation_css')) {
    function bbtheme_get_core_animation_css($enabled_classes = []) {
        $definitions = [
            'fade-in' => ['keyframe' => 'bbthemeFadeIn', 'from' => 'opacity:0;', 'to' => 'opacity:1;'],
            'fade-in-up' => ['keyframe' => 'bbthemeFadeInUp', 'from' => 'opacity:0;transform:translate3d(0,24px,0);', 'to' => 'opacity:1;transform:translate3d(0,0,0);'],
            'fade-in-down' => ['keyframe' => 'bbthemeFadeInDown', 'from' => 'opacity:0;transform:translate3d(0,-24px,0);', 'to' => 'opacity:1;transform:translate3d(0,0,0);'],
            'fade-in-left' => ['keyframe' => 'bbthemeFadeInLeft', 'from' => 'opacity:0;transform:translate3d(-24px,0,0);', 'to' => 'opacity:1;transform:translate3d(0,0,0);'],
            'fade-in-right' => ['keyframe' => 'bbthemeFadeInRight', 'from' => 'opacity:0;transform:translate3d(24px,0,0);', 'to' => 'opacity:1;transform:translate3d(0,0,0);'],
            'zoom-in' => ['keyframe' => 'bbthemeZoomIn', 'from' => 'opacity:0;transform:scale3d(.92,.92,.92);', 'to' => 'opacity:1;transform:scale3d(1,1,1);'],
        ];

        if (!is_array($enabled_classes) || !$enabled_classes) {
            $enabled_classes = array_keys($definitions);
        }

        $enabled_classes = array_values(array_intersect($enabled_classes, array_keys($definitions)));
        if (!$enabled_classes) {
            return '';
        }

        $css = '';
        foreach ($enabled_classes as $class_name) {
            $definition = $definitions[$class_name];
            $css .= sprintf('.%1$s{animation-name:%2$s;animation-duration:var(--animate-duration,1s);animation-delay:var(--animate-delay,0s);animation-iteration-count:var(--animate-repeat,1);animation-fill-mode:both;will-change:opacity,transform;}', esc_attr($class_name), esc_attr($definition['keyframe']));
            $css .= sprintf('@keyframes %1$s{from{%2$s}to{%3$s}}', esc_attr($definition['keyframe']), $definition['from'], $definition['to']);
        }

        return $css;
    }
}

if (!function_exists('bbtheme_enqueue_animation_assets_for_context')) {
    function bbtheme_enqueue_animation_assets_for_context($is_admin = false) {
        $settings = bbtheme_get_animation_settings();
        if (empty($settings['enabled'])) {
            return;
        }

        $library = sanitize_key((string) ($settings['library'] ?? 'bbtheme-core'));
        $handle_prefix = $is_admin ? 'bbtheme-admin' : 'bbtheme';

        if ($library === 'animate') {
            wp_enqueue_style($handle_prefix . '-animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', [], '4.1.1');
        } elseif ($library === 'magic') {
            wp_enqueue_style($handle_prefix . '-magic-animations', 'https://cdnjs.cloudflare.com/ajax/libs/magic/1.1.0/magic.min.css', [], '1.1.0');
        } elseif ($library === 'hover') {
            wp_enqueue_style($handle_prefix . '-hover-css', 'https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover-min.css', [], '2.3.1');
        } elseif ($library === 'custom') {
            $custom_url = esc_url_raw((string) ($settings['custom_library_url'] ?? ''));
            if ($custom_url !== '') {
                wp_enqueue_style($handle_prefix . '-custom-animation-library', $custom_url, [], null);
            }
        }

        $core_css = bbtheme_get_core_animation_css($settings['enabled_classes'] ?? []);
        if ($core_css !== '') {
            $inline_handle = $handle_prefix . '-core-animation-inline';
            wp_register_style($inline_handle, false, [], null);
            wp_enqueue_style($inline_handle);
            wp_add_inline_style($inline_handle, $core_css);
        }
    }
}

if (!function_exists('bbtheme_enqueue_animation_assets')) {
    function bbtheme_enqueue_animation_assets() {
        bbtheme_enqueue_animation_assets_for_context(false);
    }
}
add_action('wp_enqueue_scripts', 'bbtheme_enqueue_animation_assets', 25);

if (!function_exists('bbtheme_enqueue_animation_admin_assets')) {
    function bbtheme_enqueue_animation_admin_assets($hook) {
        if ($hook !== 'settings_page_wp-theme-settings') {
            return;
        }
        bbtheme_enqueue_animation_assets_for_context(true);
    }
}
add_action('admin_enqueue_scripts', 'bbtheme_enqueue_animation_admin_assets', 25);

if (!function_exists('bbtheme_output_animation_variables')) {
    function bbtheme_output_animation_variables() {
        $settings = bbtheme_get_animation_settings();
        if (empty($settings['enabled'])) {
            return;
        }

        $css = ':root{'
            . '--animate-duration:' . esc_html($settings['default_duration']) . ';'
            . '--animate-delay:' . esc_html($settings['default_delay']) . ';'
            . '--animate-repeat:' . (esc_html((string) $settings['default_repeat']) === 'infinite' ? 'infinite' : (int) $settings['default_repeat']) . ';'
            . '}';

        if (!empty($settings['disable_on_mobile'])) {
            $css .= '@media (max-width: 767px){.animate__animated,.fade-in,.fade-in-up,.fade-in-down,.fade-in-left,.fade-in-right,.zoom-in{animation:none !important;}}';
        }

        if (!empty($settings['respect_reduced_motion'])) {
            $css .= '@media (prefers-reduced-motion: reduce){.animate__animated,.fade-in,.fade-in-up,.fade-in-down,.fade-in-left,.fade-in-right,.zoom-in{animation:none !important;transition:none !important;}}';
        }

        echo "<style id=\"bbtheme-animation-vars\">{$css}</style>";
    }
}
add_action('wp_head', 'bbtheme_output_animation_variables', 99);

if (!function_exists('bbtheme_enqueue_optional_motion_assets')) {
    function bbtheme_enqueue_optional_motion_assets() {
        if (!function_exists('wp_theme_style_tokens')) {
            return;
        }

        $tokens = wp_theme_style_tokens();

        if (!empty($tokens['theme_motion_enable_lottie'])) {
            wp_enqueue_script('bbtheme-dotlottie-player', 'https://unpkg.com/@lottiefiles/dotlottie-wc@latest/dist/dotlottie-wc.js', [], null, true);
        }

        if (!empty($tokens['theme_motion_enable_svg_motion'])) {
            $css = '.' . sanitize_html_class($tokens['theme_motion_svg_class']) . '{will-change:transform,opacity;}';
            wp_register_style('bbtheme-svg-motion-inline', false, [], null);
            wp_enqueue_style('bbtheme-svg-motion-inline');
            wp_add_inline_style('bbtheme-svg-motion-inline', $css);
        }
    }
}
add_action('wp_enqueue_scripts', 'bbtheme_enqueue_optional_motion_assets', 30);
