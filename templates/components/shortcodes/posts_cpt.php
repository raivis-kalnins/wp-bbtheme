<?php
// Add short code blogs loop [posts_cpt post_type="slug" taxonomy="slug"]
function posts_cpt_globalShortcode($atts) {

    $atts = shortcode_atts(
        array(
            'post_type' => 'post',
            'taxonomy' => 'category',
            'posts_per_page' => -1,
            'order' => 'DESC',
        ),
        $atts,
        'posts_cpt'
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

    $loop = '';

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $post_date = get_the_date('F j, Y');
            $categories = get_the_terms(get_the_ID(), $taxonomy);            
            $categories_output = '';
            $categories_output_frontend = '';
            
            if ($categories && !is_wp_error($categories)) {
                $category_slugs = [];
                $category_items = []; // Array para almacenar icono + nombre juntos

                foreach ($categories as $category) {
                    $category_icon = get_field('category_icon', $taxonomy . '_' . $category->term_id);
                    $category_slugs[] = esc_html(str_replace(' ', '-', $category->slug));
                    // Crear el elemento de categoría con icono y nombre juntos
                    $icon_html = '';
                    if ($category_icon) {
                        $icon_html = '<img src="' . esc_url($category_icon['url']) . '" alt="' . esc_attr($category_icon['alt']) . '" class="category-icon" /> ';
                    }                    
                    $category_items[] = $icon_html . '<span>'. esc_html($category->name) .'</span>';
                }                
                $categories_output = implode(' ', $category_slugs);
                $categories_output_frontend = implode(', ', $category_items); // Iconos y nombres juntos
            }

            $the_title = get_the_title();
            $the_content = get_the_content();

            if ($the_title) {
                $clean_title = strip_tags($the_title);
                $trimmed_title = (strlen($clean_title) > 60) 
                    ? substr($clean_title, 0, 60) . '...' 
                    : $clean_title;                
                $post_the_title = '<div class="blog_posts__item title h4"><h3>'.$trimmed_title.'</h3></div>';
            } else {
                $post_the_title = '';
            }

            if ($the_content) {
                $clean_content = strip_tags($the_content);
                $trimmed_content = (strlen($clean_content) > 220) 
                    ? substr($clean_content, 0, 220) . '...' 
                    : $clean_content;
                
                $post_the_content = '<div class="blog_posts__item content p"><p>' . 
                    $trimmed_content . '</p></div>';
            } else {
                $post_the_content = '';
            }

            if (get_locale() == 'lv') { $btn_txt = 'Lasīt vēl'; } if (get_locale() == 'en_GB') { $btn_txt = 'Read more'; }

            $loop .= '
                        <div class="blog_posts '. $categories_output .' faux-link__element">
                            <div class="blog_posts__item image">' . get_the_post_thumbnail() . '</div>
                            <div class="blog_posts__item date h6"><a>'. $categories_output_frontend . '</a></div>
                            ' . $post_the_title . '
                            ' . $post_the_content . '
                            <div class="blog_posts__item link"><a href="' . get_the_permalink() . '" class="btn btn-link">'.$btn_txt.'</a></div>
                            <div class="blog_posts__item link-fake"><a href="' . get_the_permalink() . '" class="faux-link__overlay-link">'.$btn_txt.'</a></div>
                        </div>
                    ';
        }
        wp_reset_postdata();
    }

    return '<div class="posts_blogs-global">' . $loop . '</div>';
}
add_shortcode('posts_cpt', 'posts_cpt_globalShortcode');
?>
