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
    'products_items.php'      => class_exists('WooCommerce'),
    'testimonials.php'        => true,
    'posts_cpt.php'           => true,
    'custom-hero.php'         => true,
    'load-more.php'           => true,
    'cat_listed_cpt.php'      => true,
    'avatar_cpt.php'          => true,
    'cs_img.php'              => true,
    'woo_cat_bottom_desc.php' => class_exists('WooCommerce'),
    'products-loop.php'       => class_exists('WooCommerce'),
    'product_tabs.php'        => class_exists('WooCommerce'),
    'product_faq.php'         => class_exists('WooCommerce'),
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
