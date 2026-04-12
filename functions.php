<?php
if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(get_template_directory() . '/vendor/autoload.php')) {
    require_once get_template_directory() . '/vendor/autoload.php';
}

function wp_theme_acf_get($key, $post_id = 'option', $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($key, $post_id);
        return ($value !== null && $value !== false && $value !== '') ? $value : $default;
    }
    return $default;
}

function wp_theme_option_enabled($key, $post_id = 'option', $default = false) {
    $value = wp_theme_acf_get($key, $post_id, $default ? 1 : 0);

    if (is_bool($value)) {
        return $value;
    }

    if (is_numeric($value)) {
        return (int) $value === 1;
    }

    return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
}

function wp_theme_setup() {
    load_theme_textdomain('wp-theme', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['comment-form','comment-list','gallery','caption','style','script','search-form']);

    register_nav_menus([
        'wp-header-top-menu' => __('WP Header Top Menu', 'wp-theme'),
        'wp-header-menu'     => __('WP Header Menu', 'wp-theme'),
        'wp-footer-menu'     => __('WP Footer Menu', 'wp-theme'),
    ]);
}
add_action('after_setup_theme', 'wp_theme_setup');


if (!function_exists('wp_theme_ensure_demo_menus')) {
    function wp_theme_ensure_demo_menus() {
        $menus = [
            'wp-header-top-menu' => [
                'name' => 'WP Header Top Menu',
                'items' => [
                    ['title' => 'Latviešu valoda', 'type' => 'custom', 'url' => home_url('/')],
                    ['title' => 'Русский', 'type' => 'custom', 'url' => home_url('/')],
                    ['title' => 'English', 'type' => 'custom', 'url' => home_url('/')],
                ],
            ],
            'wp-header-menu' => [
                'name' => 'WP Header Menu',
                'items' => [
                    ['title' => 'Home', 'type' => 'custom', 'url' => home_url('/')],
                    ['title' => 'About', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'about'],
                    ['title' => 'Services', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'services'],
                    ['title' => 'Web Design', 'type' => 'custom', 'url' => home_url('/services/#web-design'), 'parent_slug' => 'services'],
                    ['title' => 'Growth SEO', 'type' => 'custom', 'url' => home_url('/services/#growth-seo'), 'parent_slug' => 'services'],
                    ['title' => 'Contact', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'contact'],
                ],
            ],
            'wp-footer-menu' => [
                'name' => 'WP Footer Menu',
                'items' => [
                    ['title' => 'Company', 'type' => 'custom', 'url' => '#'],
                    ['title' => 'About', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'about', 'parent_slug' => 'company'],
                    ['title' => 'Services', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'services', 'parent_slug' => 'company'],
                    ['title' => 'Contact', 'type' => 'post_type', 'object' => 'page', 'object_slug' => 'contact', 'parent_slug' => 'company'],
                    ['title' => 'Resources', 'type' => 'custom', 'url' => '#'],
                    ['title' => 'Blog', 'type' => 'custom', 'url' => home_url('/blog/'), 'parent_slug' => 'resources'],
                    ['title' => 'Pricing', 'type' => 'custom', 'url' => home_url('/#pricing'), 'parent_slug' => 'resources'],
                ],
            ],
        ];

        $locations = get_theme_mod('nav_menu_locations', []);
        if (!is_array($locations)) {
            $locations = [];
        }

        foreach ($menus as $location => $config) {
            $menu_name = $config['name'];
            $menu = wp_get_nav_menu_object($menu_name);
            $menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu($menu_name);
            if ($menu_id < 1 || is_wp_error($menu_id)) {
                continue;
            }

            $existing_items = wp_get_nav_menu_items($menu_id) ?: [];
            foreach ($existing_items as $existing_item) {
                wp_delete_post((int) $existing_item->ID, true);
            }

            $parents = [];
            foreach ($config['items'] as $item) {
                $parent_id = 0;
                if (!empty($item['parent_slug']) && isset($parents[$item['parent_slug']])) {
                    $parent_id = (int) $parents[$item['parent_slug']];
                }

                $args = [
                    'menu-item-title' => $item['title'],
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $parent_id,
                    'menu-item-type' => $item['type'],
                ];

                if ($item['type'] === 'post_type') {
                    $target = get_page_by_path($item['object_slug']);
                    if (!$target instanceof WP_Post) {
                        continue;
                    }
                    $args['menu-item-object'] = $item['object'];
                    $args['menu-item-object-id'] = (int) $target->ID;
                } else {
                    $args['menu-item-url'] = $item['url'];
                }

                $item_id = wp_update_nav_menu_item($menu_id, 0, $args);
                if (!is_wp_error($item_id)) {
                    $parents[sanitize_title($item['title'])] = (int) $item_id;
                }
            }

            $locations[$location] = $menu_id;
        }

        set_theme_mod('nav_menu_locations', $locations);
    }
}


if (!function_exists('wp_theme_maybe_assign_existing_demo_menus')) {
    function wp_theme_maybe_assign_existing_demo_menus() {
        if (is_admin() || wp_doing_ajax()) {
            return;
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        if (!is_array($locations)) {
            $locations = [];
        }
        $changed = false;
        foreach ([
            'wp-header-top-menu' => 'WP Header Top Menu',
            'wp-header-menu' => 'WP Header Menu',
            'wp-footer-menu' => 'WP Footer Menu',
        ] as $location => $menu_name) {
            if (!empty($locations[$location])) {
                continue;
            }
            $menu = wp_get_nav_menu_object($menu_name);
            if ((!$menu || is_wp_error($menu)) && function_exists('pll_current_language')) {
                $suffix_map = ['lv' => 'Latviešu valoda', 'ru' => 'Русский', 'en' => 'English'];
                $suffix = $suffix_map[(string) pll_current_language('slug')] ?? '';
                if ($suffix !== '') {
                    $menu = wp_get_nav_menu_object(trim($menu_name . ' ' . $suffix));
                }
            }
            if ($menu && !is_wp_error($menu)) {
                $locations[$location] = (int) $menu->term_id;
                $changed = true;
            }
        }
        if ($changed) {
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}
add_action('init', 'wp_theme_maybe_assign_existing_demo_menus', 50);


add_action('send_headers', function () {
    if (is_admin() || wp_doing_ajax() || is_user_logged_in()) {
        return;
    }
    if (!wp_theme_option_enabled('theme_enable_frontend_cache_headers', 'option', true)) {
        return;
    }
    header('Cache-Control: public, max-age=300, s-maxage=300');
});

add_filter('wp_resource_hints', function ($urls, $relation_type) {
    if ($relation_type === 'dns-prefetch') {
        $urls[] = '//cdn.jsdelivr.net';
        $urls[] = '//placehold.co';
    }
    if ($relation_type === 'preconnect') {
        $urls[] = ['href' => 'https://cdn.jsdelivr.net', 'crossorigin' => ''];
    }
    return $urls;
}, 10, 2);

function wp_theme_enqueue_assets() {
    $theme = wp_get_theme();
    wp_enqueue_style('wp-theme-style', get_stylesheet_uri(), [], $theme->get('Version'));
    $generated_vars = get_template_directory() . '/assets/css/acf-theme-vars.css';
    if (file_exists($generated_vars)) {
        wp_enqueue_style('wp-theme-acf-vars', get_template_directory_uri() . '/assets/css/acf-theme-vars.css', ['wp-theme-style'], filemtime($generated_vars));
    }
    wp_enqueue_script('wp-theme-inline', get_template_directory_uri() . '/assets/js/theme.js', [], $theme->get('Version'), true);
    wp_add_inline_script('wp-theme-inline', 'window.wpThemeHome=' . wp_json_encode(home_url('/')) . ';', 'before');

    $manifest = get_template_directory() . '/dist/manifest.json';
    if (!file_exists($manifest)) {
        return;
    }

    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) {
        return;
    }

    $assets = [
        'src/scss/public.scss' => 'wp-theme-dist',
        'src/js/main.js'       => 'wp-theme-app',
    ];

    foreach ($assets as $source => $handle) {
        if (empty($data[$source]['file'])) {
            continue;
        }
        $file = get_template_directory_uri() . '/dist/' . ltrim($data[$source]['file'], '/');
        if (str_ends_with($source, '.scss')) {
            wp_enqueue_style($handle, $file, ['wp-theme-style'], null);
        } else {
            wp_enqueue_script($handle, $file, [], null, true);
        }
    }

    if (wp_theme_option_enabled('alpine_js', 'option')) {
        wp_enqueue_script('alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js', [], '3.14.3', true);
    }
    if (wp_theme_option_enabled('media_glightbox', 'option')) {
        wp_enqueue_style('glightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.1/css/glightbox.min.css', [], '3.3.1');
        wp_enqueue_script('glightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.1/js/glightbox.min.js', [], '3.3.1', true);
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_assets', 20);

function wp_theme_cleanup() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
}
add_action('init', 'wp_theme_cleanup');

function wp_theme_body_classes($classes) {
    if (is_singular()) {
        $post = get_queried_object();
        if ($post instanceof WP_Post && !empty($post->post_name)) {
            $classes[] = sanitize_html_class($post->post_name);
        }
    }
    if (is_page()) {
        $parents = get_post_ancestors(get_the_ID());
        $id = $parents ? $parents[count($parents) - 1] : get_the_ID();
        if ($id) {
            $slug = get_post_field('post_name', $id);
            if ($slug) {
                $classes[] = 'top-parent-' . sanitize_html_class(strtolower($slug));
            }
        }
    }
    return $classes;
}
add_filter('body_class', 'wp_theme_body_classes');

function wp_theme_skip_link() {
    echo '<a class="skip-link screen-reader-text skip-link" href="#wp-theme-main">' . esc_html__('Skip to content', 'wp-theme') . '</a>';
}
add_action('wp_body_open', 'wp_theme_skip_link');

function wp_theme_pattern_categories() {
    register_block_pattern_category('wp-patterns-main', ['label' => __('WP Patterns', 'wp-theme')]);
    register_block_pattern_category('wp-patterns-main-core', ['label' => __('WP Core Patterns', 'wp-theme')]);
}
add_action('init', 'wp_theme_pattern_categories');

function wp_theme_breadcrumbs_shortcode() {
    if (function_exists('yoast_breadcrumb')) {
        return yoast_breadcrumb('<nav class="wp-theme-breadcrumbs" aria-label="Breadcrumbs">', '</nav>', false);
    }
    if (function_exists('bcn_display')) {
        ob_start();
        echo '<nav class="wp-theme-breadcrumbs" aria-label="Breadcrumbs">';
        bcn_display();
        echo '</nav>';
        return ob_get_clean();
    }
    $items = ['<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'wp-theme') . '</a>'];
    if (is_category() || is_singular('post')) {
        $items[] = esc_html__('Blog', 'wp-theme');
    }
    if (is_singular()) {
        $items[] = esc_html(get_the_title());
    } elseif (is_archive()) {
        $items[] = esc_html(post_type_archive_title('', false) ?: get_the_archive_title());
    } elseif (is_search()) {
        $items[] = sprintf(esc_html__('Search: %s', 'wp-theme'), get_search_query());
    }
    return '<nav class="wp-theme-breadcrumbs" aria-label="Breadcrumbs">' . implode(' &rsaquo; ', array_filter($items)) . '</nav>';
}
add_shortcode('wp_theme_breadcrumbs', 'wp_theme_breadcrumbs_shortcode');

function wp_theme_more_posts_intro_shortcode() {
    $text = '';
    if (function_exists('get_fields')) {
        $fields = get_fields();
        $text = $fields['single_blog_post_more_posts_intro'] ?? '';
    }
    $blog_url = get_post_type_archive_link('post') ?: home_url('/blog/');
    $label = get_locale() === 'lv' ? 'Uz blogu' : 'Back to Blog';
    return '<div class="wp-theme-meta"><a class="wp-block-button__link" href="' . esc_url($blog_url) . '">' . esc_html($label) . '</a></div>' . wp_kses_post($text);
}
add_shortcode('single_blog_post_more_posts_intro', 'wp_theme_more_posts_intro_shortcode');

function wp_theme_article_intro_shortcode() {
    if (!function_exists('get_fields')) {
        return '';
    }
    $fields = get_fields();
    return wp_kses_post($fields['single_blog_post_intro'] ?? '');
}
add_shortcode('single_blog_post_intro', 'wp_theme_article_intro_shortcode');

function wp_theme_add_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'wp_theme_add_svg_upload');

add_image_size('haizdesign-admin-post-featured-image', 120, 120, false);
function wp_theme_add_thumbnail_column($columns) {
    $columns['haizdesign_thumb'] = __('Featured Image', 'wp-theme');
    return $columns;
}
add_filter('manage_posts_columns', 'wp_theme_add_thumbnail_column', 2);
add_filter('manage_pages_columns', 'wp_theme_add_thumbnail_column', 2);
function wp_theme_show_thumbnail_column($column, $post_id) {
    if ($column === 'haizdesign_thumb') {
        if (function_exists('the_post_thumbnail')) {
            echo get_the_post_thumbnail($post_id, 'haizdesign-admin-post-featured-image');
        }
    }
}
add_action('manage_posts_custom_column', 'wp_theme_show_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'wp_theme_show_thumbnail_column', 5, 2);

function wp_theme_disable_comments() {
    foreach (['post', 'page'] as $type) {
        if (post_type_supports($type, 'comments')) {
            remove_post_type_support($type, 'comments');
        }
    }
    remove_menu_page('edit-comments.php');
}
add_action('admin_init', 'wp_theme_disable_comments');

function wp_theme_comments_redirect() {
    if (is_singular() && (comments_open() || get_comments_number())) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
}
add_action('template_redirect', 'wp_theme_comments_redirect');


function wp_theme_login_logo() {
    if (!function_exists('get_field')) {
        return;
    }

    if (!wp_theme_option_enabled('theme_login_logo_enabled', 'option', false)) {
        return;
    }

    $logo = wp_theme_acf_get('theme_login_logo', 'option', "");
    if (empty($logo)) {
        return;
    }

    $logo_url = "";

    if (is_array($logo)) {
        if (!empty($logo['ID'])) {
            $logo_url = wp_get_attachment_url((int) $logo['ID']);
        } elseif (!empty($logo['id'])) {
            $logo_url = wp_get_attachment_url((int) $logo['id']);
        } elseif (!empty($logo['url'])) {
            $logo_url = (string) $logo['url'];
        }
    } elseif (is_numeric($logo)) {
        $logo_url = wp_get_attachment_url((int) $logo);
    } elseif (is_string($logo)) {
        $logo_url = $logo;
    }

    if (empty($logo_url)) {
        return;
    }

    $width  = absint(wp_theme_acf_get('theme_login_logo_width', 'option', '160')) ?: 160;
    $height = absint(wp_theme_acf_get('theme_login_logo_height', 'option', '80')) ?: 80;

    echo '<style id="wp-theme-login-logo">
        body.login{background:#f6f7fb;}
        .login #login{width:min(92vw,380px);padding-top:5vh;}
        .login #login h1{margin-bottom:18px;}
        .login #login h1 a,
        .login h1 a{
            background: none !important;
            background-image: url(' . esc_url($logo_url) . ') !important;
            background-position: center center !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            width:' . absint($width) . 'px !important;
            height:' . absint($height) . 'px !important;
            max-width:100% !important;
            display:block !important;
            text-indent:-9999px !important;
            overflow:hidden !important;
            padding-bottom:0 !important;
            margin:0 auto !important;
        }
        .login form{
            border:1px solid #d9dde3;
            border-radius:16px;
            box-shadow:0 10px 28px rgba(17,24,39,.08);
        }
        .login #backtoblog,.login #nav{text-align:center;}
    </style>';
}
add_action('login_enqueue_scripts', 'wp_theme_login_logo', 99);

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

add_filter('login_headerurl', function () {
    return home_url('/');
});



function wp_theme_image_seo_defaults() {
    return [
        'theme_media_lazyload_images' => true,
        'theme_media_alt_from_filename' => true,
        'theme_media_add_dimensions' => true,
    ];
}

function wp_theme_image_seo_enabled($key, $default = true) {
    $defaults = wp_theme_image_seo_defaults();
    $fallback = array_key_exists($key, $defaults) ? (bool) $defaults[$key] : (bool) $default;
    return wp_theme_option_enabled($key, 'option', $fallback);
}

function wp_theme_humanize_image_filename($value) {
    $value = (string) $value;
    $value = pathinfo($value, PATHINFO_FILENAME);
    $value = preg_replace('/[-_]+/', ' ', $value);
    $value = preg_replace('/\s+/', ' ', (string) $value);
    $value = trim((string) $value);
    return $value !== '' ? ucwords($value) : '';
}

function wp_theme_get_attachment_alt_fallback($attachment_id) {
    $attachment_id = absint($attachment_id);
    if (!$attachment_id) {
        return '';
    }

    $stored_alt = trim((string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true));
    if ($stored_alt !== '') {
        return $stored_alt;
    }

    $title = trim((string) get_the_title($attachment_id));
    if ($title !== '') {
        return $title;
    }

    $file = get_attached_file($attachment_id);
    if ($file) {
        return wp_theme_humanize_image_filename((string) basename((string) $file));
    }

    return '';
}

function wp_theme_resolve_attachment_dimensions($attachment_id) {
    $attachment_id = absint($attachment_id);
    if (!$attachment_id) {
        return [0, 0];
    }

    $meta = wp_get_attachment_metadata($attachment_id);
    if (is_array($meta) && !empty($meta['width']) && !empty($meta['height'])) {
        return [absint($meta['width']), absint($meta['height'])];
    }

    $file = get_attached_file($attachment_id);
    if ($file && file_exists($file)) {
        $size = @getimagesize($file);
        if (is_array($size) && !empty($size[0]) && !empty($size[1])) {
            return [absint($size[0]), absint($size[1])];
        }
    }

    return [0, 0];
}

function wp_theme_guess_image_dimensions_from_src($src) {
    $src = (string) $src;
    if ($src === '') {
        return [0, 0];
    }

    $attachment_id = attachment_url_to_postid($src);
    if ($attachment_id) {
        return wp_theme_resolve_attachment_dimensions($attachment_id);
    }

    if (preg_match('~placehold\.co/(\d+)x(\d+)~i', $src, $matches)) {
        return [absint($matches[1]), absint($matches[2])];
    }

    $parsed = wp_parse_url($src, PHP_URL_PATH);
    if ($parsed && str_starts_with((string) $parsed, '/wp-content/uploads/')) {
        $file = ABSPATH . ltrim((string) $parsed, '/');
        if (file_exists($file)) {
            $size = @getimagesize($file);
            if (is_array($size) && !empty($size[0]) && !empty($size[1])) {
                return [absint($size[0]), absint($size[1])];
            }
        }
    }

    return [0, 0];
}

function wp_theme_get_image_alt_from_src($src) {
    $src = (string) $src;
    if ($src === '') {
        return '';
    }

    $attachment_id = attachment_url_to_postid($src);
    if ($attachment_id) {
        return wp_theme_get_attachment_alt_fallback($attachment_id);
    }

    $path = (string) wp_parse_url($src, PHP_URL_PATH);
    return wp_theme_humanize_image_filename((string) basename($path));
}

function wp_theme_enforce_attachment_image_seo($attr, $attachment, $size) {
    if (!is_array($attr)) {
        $attr = [];
    }

    $attachment_id = ($attachment instanceof WP_Post) ? (int) $attachment->ID : 0;

    if (wp_theme_image_seo_enabled('theme_media_lazyload_images', true) && empty($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    if (empty($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }

    if (wp_theme_image_seo_enabled('theme_media_alt_from_filename', true) && empty(trim((string) ($attr['alt'] ?? '')))) {
        $fallback_alt = wp_theme_get_attachment_alt_fallback($attachment_id);
        if ($fallback_alt !== '') {
            $attr['alt'] = $fallback_alt;
            if (get_post_meta($attachment_id, '_wp_attachment_image_alt', true) === '') {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $fallback_alt);
            }
        }
    }

    if (wp_theme_image_seo_enabled('theme_media_add_dimensions', true)) {
        [$width, $height] = wp_theme_resolve_attachment_dimensions($attachment_id);
        if (empty($attr['width']) && $width > 0) {
            $attr['width'] = $width;
        }
        if (empty($attr['height']) && $height > 0) {
            $attr['height'] = $height;
        }
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'wp_theme_enforce_attachment_image_seo', 20, 3);

function wp_theme_inject_image_seo_attributes_into_html($content) {
    if (!is_string($content) || $content === '' || stripos($content, '<img') === false) {
        return $content;
    }

    return preg_replace_callback('/<img\b[^>]*>/i', function ($matches) {
        $img = $matches[0];
        $src = '';

        if (preg_match('/\ssrc=["\']([^"\']+)["\']/i', $img, $src_match)) {
            $src = html_entity_decode((string) $src_match[1], ENT_QUOTES);
        }

        if (wp_theme_image_seo_enabled('theme_media_lazyload_images', true) && !preg_match('/\sloading=/i', $img)) {
            $img = preg_replace('/<img/i', '<img loading="lazy"', $img, 1);
        }

        if (!preg_match('/\sdecoding=/i', $img)) {
            $img = preg_replace('/<img/i', '<img decoding="async"', $img, 1);
        }

        if (wp_theme_image_seo_enabled('theme_media_alt_from_filename', true)) {
            $needs_alt = !preg_match('/\salt=["\']([^"\']*)["\']/i', $img, $alt_match) || trim((string) ($alt_match[1] ?? '')) === '';
            if ($needs_alt && $src !== '') {
                $fallback_alt = wp_theme_get_image_alt_from_src($src);
                if ($fallback_alt !== '') {
                    if (preg_match('/\salt=["\'][^"\']*["\']/i', $img)) {
                        $img = preg_replace('/\salt=["\'][^"\']*["\']/i', ' alt="' . esc_attr($fallback_alt) . '"', $img, 1);
                    } else {
                        $img = preg_replace('/<img/i', '<img alt="' . esc_attr($fallback_alt) . '"', $img, 1);
                    }
                }
            }
        }

        if (wp_theme_image_seo_enabled('theme_media_add_dimensions', true) && $src !== '' && (!preg_match('/\swidth=["\']\d+["\']/i', $img) || !preg_match('/\sheight=["\']\d+["\']/i', $img))) {
            [$width, $height] = wp_theme_guess_image_dimensions_from_src($src);
            if ($width > 0 && !preg_match('/\swidth=["\']\d+["\']/i', $img)) {
                $img = preg_replace('/<img/i', '<img width="' . absint($width) . '"', $img, 1);
            }
            if ($height > 0 && !preg_match('/\sheight=["\']\d+["\']/i', $img)) {
                $img = preg_replace('/<img/i', '<img height="' . absint($height) . '"', $img, 1);
            }
        }

        return $img;
    }, $content);
}
add_filter('the_content', 'wp_theme_inject_image_seo_attributes_into_html', 20);
add_filter('render_block', 'wp_theme_inject_image_seo_attributes_into_html', 20);
add_filter('post_thumbnail_html', 'wp_theme_inject_image_seo_attributes_into_html', 20);

add_action('admin_notices', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    if (!function_exists('acf_add_options_page')) {
        echo '<div class="notice notice-warning"><p><strong>WP BBTheme:</strong> ACF Pro is required for Theme Styles options.</p></div>';
    }
});

foreach (['acf-theme-options.php','tpl-helper.php','shortcodes.php','main-menu-extras.php','loc.php','info.php','block-types.php','wp-nav-walker.php'] as $file) {
    $paths = [
        get_template_directory() . '/inc/custom/' . $file,
        get_template_directory() . '/inc/Custom/' . $file,
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
}

$bbtheme_animation_bootstrap = get_template_directory() . '/inc/animations/bootstrap.php';
if (file_exists($bbtheme_animation_bootstrap)) {
    require_once $bbtheme_animation_bootstrap;
}




function wp_theme_post_ordering_enabled() {
    return wp_theme_option_enabled('theme_enable_post_ordering', 'option', true);
}

function wp_theme_conditional_post_type_option_map() {
    return [
        'products' => 'theme_enable_post_ordering_products',
        'case-study' => 'theme_enable_post_ordering_case_study',
        'testimonial' => 'theme_enable_post_ordering_testimonial',
        'megamenu' => 'theme_enable_cpt_megamenu',
    ];
}

function wp_theme_is_conditional_post_type($post_type) {
    $post_type = sanitize_key((string) $post_type);
    return array_key_exists($post_type, wp_theme_conditional_post_type_option_map());
}

function wp_theme_conditional_post_type_enabled($post_type) {
    $post_type = sanitize_key((string) $post_type);
    $option_map = wp_theme_conditional_post_type_option_map();

    if (!isset($option_map[$post_type])) {
        return true;
    }

    return wp_theme_option_enabled($option_map[$post_type], 'option', false);
}

function wp_theme_should_expose_post_type($post_type) {
    return wp_theme_conditional_post_type_enabled($post_type);
}

add_filter('register_post_type_args', function ($args, $post_type) {
    if (!wp_theme_is_conditional_post_type($post_type)) {
        return $args;
    }

    if (wp_theme_should_expose_post_type($post_type)) {
        if ($post_type === 'megamenu') {
            $args['show_ui'] = true;
            $args['show_in_menu'] = 'themes.php';
            $args['show_in_admin_bar'] = false;
        }

        return $args;
    }

    $args['public'] = false;
    $args['show_ui'] = false;
    $args['show_in_menu'] = false;
    $args['show_in_admin_bar'] = false;
    $args['show_in_nav_menus'] = false;
    $args['publicly_queryable'] = false;
    $args['exclude_from_search'] = true;
    $args['has_archive'] = false;
    $args['rewrite'] = false;
    $args['show_in_rest'] = false;

    return $args;
}, 100, 2);

add_action('admin_menu', function () {
    foreach (wp_theme_conditional_post_type_option_map() as $post_type => $option_key) {
        $menu_slug = 'edit.php?post_type=' . $post_type;

        if (!wp_theme_should_expose_post_type($post_type)) {
            remove_menu_page($menu_slug);
            continue;
        }

        if ($post_type === 'megamenu') {
            remove_menu_page($menu_slug);
            add_submenu_page(
                'themes.php',
                __('Megamenu', 'wp-theme'),
                __('Megamenu', 'wp-theme'),
                'edit_posts',
                $menu_slug
            );
        }
    }
}, 999);

add_action('registered_post_type', function ($post_type, $post_type_object) {
    if ($post_type !== 'megamenu' || !wp_theme_should_expose_post_type('megamenu')) {
        return;
    }

    global $wp_post_types;

    if (isset($wp_post_types['megamenu'])) {
        $wp_post_types['megamenu']->show_in_menu = 'themes.php';
        $wp_post_types['megamenu']->show_in_admin_bar = false;
    }
}, 100, 2);

add_action('current_screen', function ($screen) {
    if (!is_object($screen) || empty($screen->post_type)) {
        return;
    }

    $post_type = sanitize_key((string) $screen->post_type);
    if (!wp_theme_is_conditional_post_type($post_type) || wp_theme_should_expose_post_type($post_type)) {
        return;
    }

    if (is_admin()) {
        wp_safe_redirect(admin_url());
        exit;
    }
}, 1);

add_action('template_redirect', function () {
    if (is_admin()) {
        return;
    }

    if (is_post_type_archive(['products', 'case-study', 'testimonial']) || is_singular(['products', 'case-study', 'testimonial'])) {
        $post_type = '';

        if (is_singular()) {
            $post_type = get_post_type(get_queried_object_id());
        } else {
            $post_type = get_query_var('post_type');
            if (is_array($post_type)) {
                $post_type = reset($post_type);
            }
        }

        $post_type = sanitize_key((string) $post_type);
        if ($post_type && !wp_theme_should_expose_post_type($post_type)) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
        }
    }
}, 1);

add_action('pre_get_posts', function ($query) {
    if (!($query instanceof WP_Query)) {
        return;
    }

    $post_type = $query->get('post_type');
    if (empty($post_type)) {
        return;
    }

    $disabled = [];
    foreach (array_keys(wp_theme_conditional_post_type_option_map()) as $candidate) {
        if (!wp_theme_should_expose_post_type($candidate)) {
            $disabled[] = $candidate;
        }
    }

    if (!$disabled) {
        return;
    }

    if (is_array($post_type)) {
        $post_type = array_values(array_diff($post_type, $disabled));
        if (!$post_type) {
            $post_type = 'post';
        }
        $query->set('post_type', $post_type);
        return;
    }

    if (in_array($post_type, $disabled, true)) {
        $query->set('post_type', 'post');
    }
}, 5);

function wp_theme_post_ordering_type_option_map() {
    return wp_theme_conditional_post_type_option_map();
}

function wp_theme_post_type_ordering_enabled($post_type) {
    $post_type = sanitize_key((string) $post_type);

    if ($post_type === '') {
        return false;
    }

    if (!wp_theme_post_ordering_enabled()) {
        return false;
    }

    $option_map = wp_theme_post_ordering_type_option_map();
    if (isset($option_map[$post_type])) {
        return wp_theme_option_enabled($option_map[$post_type], 'option', false);
    }

    return true;
}

function wp_theme_post_ordering_supported_types() {
    $post_types = get_post_types([
        'public' => true,
        'show_ui' => true,
    ], 'objects');

    $supported = [];

    foreach ($post_types as $post_type => $object) {
        if (in_array($post_type, ['attachment', 'acf-field', 'acf-field-group', 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation'], true)) {
            continue;
        }

        if (!wp_theme_post_type_ordering_enabled($post_type)) {
            continue;
        }

        $supported[] = $post_type;
    }

    return $supported;
}

add_action('init', function () {
    if (!wp_theme_post_ordering_enabled()) {
        return;
    }

    foreach (wp_theme_post_ordering_supported_types() as $post_type) {
        add_post_type_support($post_type, 'page-attributes');
    }
}, 30);

function wp_theme_is_post_ordering_screen() {
    if (!is_admin() || !wp_theme_post_ordering_enabled()) {
        return false;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->base !== 'edit') {
        return false;
    }

    return in_array($screen->post_type, wp_theme_post_ordering_supported_types(), true);
}

add_action('current_screen', function ($screen) {
    if (!is_object($screen) || empty($screen->post_type) || !wp_theme_post_ordering_enabled()) {
        return;
    }

    if (!in_array($screen->post_type, wp_theme_post_ordering_supported_types(), true)) {
        return;
    }

    if ($screen->post_type === 'page') {
        add_filter('manage_pages_columns', 'wp_theme_add_order_column', 20);
        add_action('manage_pages_custom_column', 'wp_theme_render_order_column', 10, 2);
        return;
    }

    add_filter('manage_' . $screen->post_type . '_posts_columns', 'wp_theme_add_order_column', 20);
    add_action('manage_' . $screen->post_type . '_posts_custom_column', 'wp_theme_render_order_column', 10, 2);
});

function wp_theme_add_order_column($columns) {
    if (!wp_theme_post_ordering_enabled()) {
        return $columns;
    }

    $new_columns = [];
    foreach ($columns as $key => $label) {
        $new_columns[$key] = $label;
        if ($key === 'title') {
            $new_columns['menu_order'] = __('Order', 'wp-theme');
        }
    }

    if (!isset($new_columns['menu_order'])) {
        $new_columns['menu_order'] = __('Order', 'wp-theme');
    }

    return $new_columns;
}

function wp_theme_render_order_column($column, $post_id) {
    if ($column !== 'menu_order' || !wp_theme_post_ordering_enabled()) {
        return;
    }

    echo '<span class="wp-theme-order-handle" title="' . esc_attr__('Drag to reorder', 'wp-theme') . '">&#x2630;</span> ';
    echo '<span class="wp-theme-order-value">' . esc_html((string) get_post_field('menu_order', $post_id)) . '</span>';
}

add_action('restrict_manage_posts', function () {
    if (!wp_theme_is_post_ordering_screen()) {
        return;
    }

    echo '<input type="hidden" name="orderby" value="menu_order" />';
    echo '<input type="hidden" name="order" value="asc" />';
});

add_action('pre_get_posts', function ($query) {
    if (!($query instanceof WP_Query) || !$query->is_main_query() || !wp_theme_post_ordering_enabled()) {
        return;
    }

    if (is_admin()) {
        global $pagenow;

        if ($pagenow !== 'edit.php') {
            return;
        }

        $screen_post_type = $query->get('post_type');
        if (!$screen_post_type) {
            $screen_post_type = 'post';
        }

        if (!in_array($screen_post_type, wp_theme_post_ordering_supported_types(), true)) {
            return;
        }

        if (!isset($_GET['orderby']) || $_GET['orderby'] === '' || $_GET['orderby'] === 'date' || $_GET['orderby'] === 'title') {
            $query->set('orderby', 'menu_order title');
            $query->set('order', 'ASC');
        }
        return;
    }

    if ($query->is_singular()) {
        return;
    }

    $post_type = $query->get('post_type');
    if (empty($post_type)) {
        $post_type = $query->is_home() ? 'post' : 'page';
    }

    $supported = wp_theme_post_ordering_supported_types();
    if (is_array($post_type)) {
        if (!array_intersect($post_type, $supported)) {
            return;
        }
    } elseif (!in_array($post_type, $supported, true)) {
        return;
    }

    $query->set('orderby', ['menu_order' => 'ASC', 'title' => 'ASC', 'date' => 'DESC']);
    $query->set('order', 'ASC');
}, 40);

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'edit.php' || !wp_theme_is_post_ordering_screen()) {
        return;
    }

    wp_enqueue_script('jquery-ui-sortable');
    wp_add_inline_style('wp-admin', '
        .column-menu_order { width: 90px; }
        .wp-theme-order-handle { cursor: move; font-size: 18px; line-height: 1; display: inline-block; }
        .wp-list-table tbody tr.is-dragging { opacity: 0.7; }
        .wp-list-table tbody tr td.column-menu_order { white-space: nowrap; }
        .wp-list-table tbody tr.ui-sortable-helper { background: #fff; }
    ');

    $wp_theme_order_nonce = wp_create_nonce('wp_theme_save_post_order');
    $wp_theme_order_post_type = 'post';
    if (function_exists('get_current_screen')) {
        $current_screen = get_current_screen();
        if ($current_screen && !empty($current_screen->post_type)) {
            $wp_theme_order_post_type = sanitize_key((string) $current_screen->post_type);
        } elseif (isset($_GET['post_type'])) {
            $wp_theme_order_post_type = sanitize_key((string) $_GET['post_type']);
        }
    } elseif (isset($_GET['post_type'])) {
        $wp_theme_order_post_type = sanitize_key((string) $_GET['post_type']);
    }

    wp_add_inline_script('jquery-ui-sortable', '
        jQuery(function($) {
            var $table = $("#the-list");
            if (!$table.length) {
                $table = $(".wp-list-table tbody").first();
            }
            if (!$table.length) {
                return;
            }

            $table.sortable({
                axis: "y",
                items: "> tr[id^=\"post-\"]",
                handle: ".wp-theme-order-handle",
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).outerWidth());
                    });
                    return ui;
                },
                start: function(event, ui) {
                    ui.item.addClass("is-dragging");
                },
                stop: function(event, ui) {
                    ui.item.removeClass("is-dragging");
                },
                update: function() {
                    var order = [];
                    $table.children("tr[id^=\"post-\"]").each(function(index) {
                        var id = $(this).attr("id");
                        if (!id || id.indexOf("post-") !== 0) {
                            return;
                        }
                        order.push({
                            id: parseInt(id.replace("post-", ""), 10),
                            menu_order: index
                        });
                        $(this).find(".wp-theme-order-value").text(index);
                    });

                    if (!order.length) {
                        return;
                    }

                    $.post(ajaxurl, {
                        action: "wp_theme_save_post_order",
                        nonce: "' . esc_js($wp_theme_order_nonce) . '",
                        order: order,
                        post_type: "' . esc_js($wp_theme_order_post_type) . '"
                    });
                }
            });
        });
    ');
});

add_action('wp_ajax_wp_theme_save_post_order', function () {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => 'forbidden'], 403);
    }

    check_ajax_referer('wp_theme_save_post_order', 'nonce');

    $order = isset($_POST['order']) && is_array($_POST['order']) ? $_POST['order'] : [];
    foreach ($order as $row) {
        $post_id = isset($row['id']) ? absint($row['id']) : 0;
        $menu_order = isset($row['menu_order']) ? intval($row['menu_order']) : 0;

        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            continue;
        }

        wp_update_post([
            'ID' => $post_id,
            'menu_order' => $menu_order,
        ]);
    }

    wp_send_json_success();
});

add_action('admin_notices', function () {
    if (!wp_theme_is_post_ordering_screen()) {
        return;
    }

    echo '<div class="notice notice-info is-dismissible"><p>'
        . esc_html__('Drag rows by the Order handle to change display order. Theme Settings → Enable Post Ordering controls this feature.', 'wp-theme')
        . '</p></div>';
});

function wp_theme_enqueue_demo_homepage_assets() {
    if (!is_singular()) {
        return;
    }

    $post_id = get_queried_object_id();
    if (!$post_id || !get_post_meta($post_id, '_wp_theme_demo_homepage', true)) {
        return;
    }

    $demo_css = get_template_directory() . '/assets/css/homepage-demo.css';
    if (file_exists($demo_css)) {
        wp_enqueue_style(
            'wp-theme-homepage-demo',
            get_template_directory_uri() . '/assets/css/homepage-demo.css',
            ['wp-theme-style'],
            filemtime($demo_css)
        );
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_demo_homepage_assets', 40);
