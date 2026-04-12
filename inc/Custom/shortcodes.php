<?php
if (!defined('ABSPATH')) exit;

function shortcode_needLogin() {
    if (is_user_logged_in()) {
        return '';
    }
    if (class_exists('WooCommerce')) {
        return '<div class="container login-form" style="margin-bottom:70px;">' . do_shortcode('[woocommerce_my_account]') . '</div>';
    }
    return '<div class="container login-form" style="margin-bottom:70px;">' . esc_html__('Please log in to continue.', 'wp-theme') . '</div>';
}
add_shortcode('need_login', 'shortcode_needLogin');

$component_dir = get_template_directory() . '/templates/components/shortcodes/';
$includes = [
    'custom-hero.php'         => true,
    'avatar_cpt.php'          => true,
];
foreach ($includes as $file => $enabled) {
    $path = $component_dir . $file;
    if ($enabled && file_exists($path)) {
        include_once $path;
    }
}

function shortcode_product_reviews() {
    if (!class_exists('WooCommerce')) {
        return '';
    }
    $enabled = function_exists('get_fields') ? (get_fields()['product_reviews'] ?? '') : '';
    if (!$enabled) {
        return '';
    }
    return do_blocks('<!-- wp:woocommerce/product-reviews --><div class="wp-block-woocommerce-product-reviews"><!-- wp:woocommerce/product-reviews-title /--><!-- wp:woocommerce/product-review-template /--><!-- wp:woocommerce/product-reviews-pagination /--><!-- wp:woocommerce/product-review-form /--></div><!-- /wp:woocommerce/product-reviews -->');
}
add_shortcode('product_reviews', 'shortcode_product_reviews');



if (!function_exists('wp_theme_current_language_label')) {
    function wp_theme_current_language_label() {
        $slug = '';
        if (function_exists('pll_current_language')) {
            $slug = (string) pll_current_language('slug');
        }
        $map = [
            'lv' => 'Latviešu valoda',
            'ru' => 'Русский',
            'en' => 'English',
        ];
        return $map[$slug] ?? '';
    }
}

if (!function_exists('wp_theme_resolve_menu_for_location')) {
    function wp_theme_resolve_menu_for_location($location) {
        $location = sanitize_key((string) $location);
        $locations = get_nav_menu_locations();
        if (!empty($locations[$location])) {
            $menu = wp_get_nav_menu_object((int) $locations[$location]);
            if ($menu && !is_wp_error($menu)) {
                return $menu;
            }
        }

        $base_names = [
            'wp-header-top-menu' => 'WP Header Top Menu',
            'wp-header-menu' => 'WP Header Menu',
            'wp-footer-menu' => 'WP Footer Menu',
        ];
        $base = $base_names[$location] ?? ucwords(str_replace('-', ' ', str_replace('wp-', '', $location)));
        $candidates = [$base];
        $lang_label = wp_theme_current_language_label();
        if ($lang_label !== '') {
            array_unshift($candidates, trim($base . ' ' . $lang_label));
        }
        foreach (array_unique($candidates) as $name) {
            $menu = wp_get_nav_menu_object($name);
            if ($menu && !is_wp_error($menu)) {
                return $menu;
            }
        }
        return null;
    }
}

if (!function_exists('wp_theme_render_mega_panel')) {
    function wp_theme_render_mega_panel($menu_item, $index = 0) {
        if (!wp_theme_option_enabled('theme_enable_cpt_megamenu', 'option', false) || !post_type_exists('megamenu')) {
            return '';
        }

        $slug_base = sanitize_title($menu_item->title);
        $candidate_slugs = array_filter([
            $slug_base,
            $slug_base . '-mega-menu',
            $slug_base . '-megamenu',
        ]);

        $mega_post = null;
        foreach ($candidate_slugs as $slug) {
            $mega_post = get_page_by_path($slug, OBJECT, 'megamenu');
            if ($mega_post instanceof WP_Post) {
                break;
            }
        }

        if (!$mega_post instanceof WP_Post) {
            $posts = get_posts([
                'post_type' => 'megamenu',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            $mega_post = !empty($posts[0]) ? $posts[0] : null;
        }

        if (!$mega_post instanceof WP_Post) {
            return '';
        }

        $panel_id = 'wp-theme-mega-panel-' . absint($mega_post->ID) . '-' . intval($index);
        $content = apply_filters('the_content', $mega_post->post_content);
        if (trim((string) $content) === '') {
            return '';
        }

        return '<div id="' . esc_attr($panel_id) . '" class="wp-theme-mega-panel card border-0 shadow-lg"><div class="card-body">' . $content . '</div></div>';
    }
}

if (!function_exists('wp_theme_menu_shortcode')) {
    function wp_theme_menu_shortcode($atts = []) {
        $atts = shortcode_atts([
            'location' => '',
            'class' => '',
            'menu_class' => 'nav',
            'container_class' => 'wp-theme-menu-shortcode',
            'depth' => 3,
            'fallback' => '',
        ], $atts, 'wp_theme_menu');

        $location = sanitize_key((string) ($atts['location'] ?? ''));
        if ($location === '') {
            return '';
        }

        $menu_obj = wp_theme_resolve_menu_for_location($location);
        $assigned = $menu_obj instanceof WP_Term;
        $container_class = trim('wp-theme-menu-shortcode ' . (string) $atts['container_class']);
        $menu_class = trim((string) $atts['menu_class'] . ' ' . (string) $atts['class']);

        if (!$assigned) {
            $fallback = trim((string) ($atts['fallback'] ?? ''));
            if ($fallback === '') {
                $fallback = ucfirst(str_replace(['wp-', '-'], ['', ' '], $location));
            }
            return '<div class="' . esc_attr($container_class) . ' wp-theme-menu-shortcode--empty"><span>' . esc_html($fallback) . '</span></div>';
        }

        $args = [
            'menu' => $menu_obj ? (int) $menu_obj->term_id : 0,
            'container' => false,
            'menu_class' => $menu_class,
            'depth' => max(1, absint($atts['depth'] ?? 3)),
            'fallback_cb' => false,
            'echo' => false,
        ];

        if (class_exists('bootstrap_5_wp_nav_menu_walker')) {
            $args['walker'] = new bootstrap_5_wp_nav_menu_walker();
        }

        $menu_html = (string) wp_nav_menu($args);

        $is_header = $location === 'wp-header-menu';
        if (!$is_header) {
            return '<div class="' . esc_attr($container_class) . '">' . $menu_html . '</div>';
        }

        $desktop_mode = sanitize_key((string) wp_theme_acf_get('theme_header_menu_mode', 'option', 'dropdown'));
        if (!in_array($desktop_mode, ['dropdown', 'megamenu'], true)) {
            $desktop_mode = 'dropdown';
        }

        $menu_items = $menu_obj ? wp_get_nav_menu_items($menu_obj->term_id) : [];
        $mega_panels = '';
        if ($desktop_mode === 'megamenu' && !empty($menu_items)) {
            $top_items = array_values(array_filter($menu_items, static function ($item) {
                return intval($item->menu_item_parent) === 0;
            }));
            foreach ($top_items as $index => $menu_item) {
                $panel = wp_theme_render_mega_panel($menu_item, $index);
                if ($panel !== '') {
                    $mega_panels .= $panel;
                }
            }
        }

        $toggle = '<button class="wp-theme-menu-toggle btn btn-outline-dark" type="button" aria-expanded="false" aria-controls="wp-theme-header-nav"><span class="wp-theme-menu-toggle__bars"><span></span><span></span><span></span></span><span class="wp-theme-menu-toggle__label">Menu</span></button>';

        $panels_wrap = $mega_panels !== '' ? '<div class="wp-theme-mega-panels">' . $mega_panels . '</div>' : '';

        return '<div class="' . esc_attr($container_class) . ' wp-theme-menu-shortcode--header wp-theme-menu-mode--' . esc_attr($desktop_mode) . '" data-header-mode="' . esc_attr($desktop_mode) . '">' . $toggle . '<nav id="wp-theme-header-nav" class="wp-theme-header-nav" aria-label="' . esc_attr__('Primary menu', 'wp-theme') . '">' . $menu_html . '</nav>' . $panels_wrap . '</div>';
    }
}
add_shortcode('wp_theme_menu', 'wp_theme_menu_shortcode');


if (!function_exists('wp_theme_site_logo_shortcode')) {
    function wp_theme_site_logo_shortcode($atts = []) {
        $atts = shortcode_atts(['variant' => 'header', 'class' => ''], $atts, 'wp_theme_site_logo');
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo_html = '';
        if ($custom_logo_id) {
            $logo_html = wp_get_attachment_image($custom_logo_id, 'full', false, [
                'class' => trim('wp-theme-site-logo-img ' . (string)$atts['class']),
                'loading' => 'lazy',
                'decoding' => 'async',
                'alt' => get_bloginfo('name'),
            ]);
        }
        if ($logo_html === '') {
            $fallback = get_template_directory_uri() . '/assets/img/logo-placeholder-gray.svg';
            $logo_html = '<img class="wp-theme-site-logo-img ' . esc_attr((string)$atts['class']) . '" src="' . esc_url($fallback) . '" alt="' . esc_attr(get_bloginfo('name')) . '" loading="lazy" width="220" height="60" />';
        }
        return '<a class="wp-theme-site-logo-link" href="' . esc_url(home_url('/')) . '">' . $logo_html . '</a>';
    }
}
add_shortcode('wp_theme_site_logo', 'wp_theme_site_logo_shortcode');

if (!function_exists('wp_theme_header_extras_shortcode')) {
    function wp_theme_header_extras_shortcode($atts = []) {
        $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
        $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
        $wishlist_url = home_url('/wishlist/');
        $lang = '<div class="wp-theme-header-extra wp-theme-header-extra--lang"><span>EN</span><span class="separator">/</span><span>LV</span></div>';
        if (function_exists('pll_the_languages')) {
            $langs = pll_the_languages(['raw' => 1, 'hide_if_empty' => 0, 'hide_if_no_translation' => 0]);
            if (is_array($langs) && !empty($langs)) {
                $parts = [];
                foreach ($langs as $lang_item) {
                    $parts[] = '<a href="' . esc_url($lang_item['url']) . '" class="lang-link' . (!empty($lang_item['current_lang']) ? ' is-current' : '') . '">' . esc_html($lang_item['name']) . '</a>';
                }
                $lang = '<div class="wp-theme-header-extra wp-theme-header-extra--lang">' . implode('<span class="separator">/</span>', $parts) . '</div>';
            }
        }
        $toggle = '<button type="button" class="wp-theme-header-extra wp-theme-header-extra--theme" data-wp-theme-toggle aria-label="Toggle dark mode"><span>☀</span><span>🌙</span></button>';
        $account = '<a class="wp-theme-header-extra wp-theme-header-extra--account" href="' . esc_url($account_url) . '"><span class="label">Customer Account</span></a>';
        $cart = '<a class="wp-theme-header-extra wp-theme-header-extra--cart" href="' . esc_url($cart_url) . '"><span class="label">Mini Cart</span></a>';
        $wishlist = '<a class="wp-theme-header-extra wp-theme-header-extra--wishlist" href="' . esc_url($wishlist_url) . '"><span class="label">Wishlist</span></a>';
        $mega = wp_theme_option_enabled('theme_enable_cpt_megamenu', 'option', false) ? '<span class="wp-theme-header-extra wp-theme-header-extra--mega"><span class="label">Mega Menu</span></span>' : '';
        $button = '<a class="btn btn-primary wp-theme-header-cta" href="' . esc_url(home_url('/contact/')) . '">Last Button</a>';
        return '<div class="wp-theme-header-extras d-flex flex-wrap align-items-center justify-content-center justify-content-xl-end gap-2">' . $account . $cart . $wishlist . $mega . $lang . $toggle . $button . '</div>';
    }
}
add_shortcode('wp_theme_header_extras', 'wp_theme_header_extras_shortcode');

if (!function_exists('wp_theme_footer_menu_shortcode')) {
    function wp_theme_footer_menu_shortcode() {
        return do_shortcode('[wp_theme_menu location="wp-footer-menu" class="flex-column gap-2" menu_class="navbar-nav flex-column gap-2" fallback="Assign WP Footer Menu in Appearance → Menus"]');
    }
}
add_shortcode('wp_theme_footer_menu', 'wp_theme_footer_menu_shortcode');

add_action('wp_footer', function () {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggles = document.querySelectorAll('[data-wp-theme-toggle]');
        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                document.body.classList.toggle('wp-theme-dark-mode');
            });
        });
    });
    </script>
    <?php
}, 99);


add_filter('the_content', function ($content) {
    $patterns = [
        '#<p>\s*(<div class="wp-theme-menu-shortcode[^>]*>.*?</div>)\s*</p>#si',
        '#<p>\s*(<div class="wp-theme-header-extras[^>]*>.*?</div>)\s*</p>#si',
        '#<p>\s*(<a class="wp-theme-site-logo-link[^>]*>.*?</a>)\s*</p>#si',
    ];
    foreach ($patterns as $pattern) {
        $content = preg_replace($pattern, '$1', $content);
    }
    return $content;
}, 20);
