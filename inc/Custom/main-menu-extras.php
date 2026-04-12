<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('wp_theme_nav_menu_objects_filter')) {
    function wp_theme_nav_menu_objects_filter($items, $args) {
        foreach ($items as $item) {
            if (!empty($item->menu_img)) {
                $item->classes[] = 'menu-item-img';
            }
            if (!empty($item->mega_post_id)) {
                $item->classes[] = 'menu-item-has-megamenu';
                $item->classes[] = 'megamenu-' . absint($item->mega_post_id);
            }
        }
        return $items;
    }
}
add_filter('wp_nav_menu_objects', 'wp_theme_nav_menu_objects_filter', 10, 2);
