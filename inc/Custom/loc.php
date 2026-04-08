<?php
if (!defined('ABSPATH')) exit;

function wp_theme_loc_enabled() {
    return function_exists('get_field') && wp_theme_acf_get('loc_pages', 'option') === 'true';
}

if (wp_theme_loc_enabled()) {
    add_action('init', function () {
        register_post_type('lp', [
            'labels' => [
                'name' => __('Locations', 'wp-theme'),
                'singular_name' => __('Location', 'wp-theme'),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'menu_icon' => 'dashicons-location',
            'rewrite' => ['slug' => 'lp'],
        ]);
    });

    add_shortcode('wp_paginated_lp', function () {
        $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
        $query = new WP_Query([
            'post_type' => 'lp',
            'posts_per_page' => 35,
            'paged' => $paged,
            'ignore_sticky_posts' => true,
            'no_found_rows' => false,
        ]);
        ob_start();
        if ($query->have_posts()) {
            echo '<div class="loc-pages_wrap"><ul class="loc-pages_items">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li class="loc-pages_item"><h3><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3></li>';
            }
            echo '</ul></div><div class="pagination" style="margin:30px 0;">';
            echo paginate_links([
                'total' => $query->max_num_pages,
                'current' => $paged,
                'mid_size' => 2,
                'prev_text' => '<b>&#10094;</b>',
                'next_text' => '<b>&#10095;</b>',
            ]);
            echo '</div>';
        }
        wp_reset_postdata();
        return ob_get_clean();
    });

    add_shortcode('wp_random_lp_rand_foo', function () {
        if (!is_front_page()) {
            return '';
        }
        $query = new WP_Query([
            'post_type' => 'lp',
            'posts_per_page' => 10,
            'orderby' => 'rand',
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
        ]);
        ob_start();
        if ($query->have_posts()) {
            echo '<div class="loc-pages_wrap" style="position:absolute;width:fit-content;display:flex;transform:translateX(-50%);left:50%;margin:-40px 0 0 0"><ul class="loc-pages_items" style="display:inline-flex;margin:0">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li class="loc-pages_item" style="color:rgba(255,255,255,0.5);font-size:12px;display:flex;padding-left:10px"><a href="' . esc_url(get_permalink()) . '" style="color:rgba(255,255,255,0.5);font-size:12px">' . esc_html(get_the_title()) . '</a></li>';
            }
            echo '</ul></div>';
        }
        wp_reset_postdata();
        return ob_get_clean();
    });

    add_action('wp_head', function () {
        if (!is_singular('lp')) return;
        $post_id = get_the_ID();
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => wp_theme_acf_get('business_type', $post_id, 'LocalBusiness'),
            '@id' => get_permalink($post_id) . '#localbusiness',
            'name' => wp_theme_acf_get('business_name', $post_id, get_the_title($post_id)),
            'url' => get_permalink($post_id),
            'telephone' => wp_theme_acf_get('phone', $post_id),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => wp_theme_acf_get('address', $post_id),
                'addressLocality' => wp_theme_acf_get('city', $post_id),
                'addressRegion' => wp_theme_acf_get('county', $post_id),
                'postalCode' => wp_theme_acf_get('zip', $post_id),
                'addressCountry' => 'US',
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    });
}
