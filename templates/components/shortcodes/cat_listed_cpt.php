<?php
// Add short code blogs loop [cat_listed_cpt post_type="slug" taxonomy="slug"]
function cat_listed_cpt_globalShortcode($atts) {

    $atts = shortcode_atts(
        array(
            'post_type' => 'post',
            'taxonomy' => 'category',
            'posts_per_page' => -1,
			'orderby' =>  'menu_order',
			'order' =>  [ 'title' => 'DESC', 'menu_order' => 'ASC' ],
        ),
        $atts,
        'cat_listed_case_study'
    );

    $taxonomy = ($atts['post_type'] == 'post') ? 'category' : $atts['taxonomy'];

    // Prepare WP_Query Query Arguments
    $args = array(
        'post_type' => $atts['post_type'],
        'post_status' => 'publish',
        'order' => $atts['order'],
        'posts_per_page' => $atts['posts_per_page'],
    );

    $the_query = new WP_Query($args);
    $categories = array();

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();            
            $post_categories = get_the_terms(get_the_ID(), $taxonomy);            
            if ($post_categories && !is_wp_error($post_categories)) {
                foreach ($post_categories as $category) {
                    $categories[$category->term_id] = $category;
                }
            }
        }
        wp_reset_postdata();
    }

    $loop = '<ul class="posts-blogs-categories">';
    foreach ($categories as $category) {
        $category_icon = get_field('category_icon', $taxonomy . '_' . $category->term_id);        
        $icon_html = '';
        if ($category_icon) {
            $icon_html = '<img src="' . esc_url($category_icon['url']) . '" alt="' . esc_attr($category_icon['alt']) . '" class="category-icon" />';
        }
        $loop .= '<li><a>' . $icon_html .'<span>'. esc_html($category->name) . '</span></a></li>';
    }
    $loop .= '<li class="all-btn" style="position:absolute;right:0;top:-20px;display:none"><a class="all" style="padding:0;border:0;font-size:14px;color:red">Clear</a></li></ul>';
    return '<div class="posts_cat-global">' . $loop . '</div>';
}
add_shortcode('cat_listed_cpt', 'cat_listed_cpt_globalShortcode');
