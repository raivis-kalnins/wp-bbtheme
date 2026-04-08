<?php
function testimonialsShortcode() {
    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'order' => 'ASC',
        'posts_per_page' => -1,
    );

    $the_query = new WP_Query($args);
    $loop = '';

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $testimonials = get_fields();
            $description = $testimonials["content"] ?? '';
            $name = $testimonials["name"] ?? '';
            $role = $testimonials["role"] ?? '';
            
            $thumbnail = get_the_post_thumbnail();
            $title = get_the_title();

            $image_html = $thumbnail ? 
                '<div class="testimonial-list__container--image">' . $thumbnail . '</div>' : 
                '';

            $testimonial_description = '';
            $testimonial_meta = '';
            
            if ($description) {
                $clean_description = strip_tags($description);
                $trimmed_description = (strlen($clean_description) > 150) 
                    ? substr($clean_description, 0, 150) . '...' 
                    : $clean_description;
                
                $testimonial_description .= '<div class="testimonial-description">' . 
                    wp_kses_post($trimmed_description) . '</div>';
            }
            
            if ($name || $role) {
                $testimonial_meta .= '<div class="testimonial-meta">';
                if ($name) {
                    $testimonial_meta .= '<span class="testimonial-name">' . 
                        esc_html($name) . '</span>';
                }
                if ($role) {
                    $testimonial_meta .= '<span class="testimonial-role">' . 
                        esc_html($role) . '</span>';
                }
                $testimonial_meta .= '</div>';
            }

            $loop .= '<div class="swiper-slide">
                <div class="testimonial-list__item faux-link__element">
                    <h3 class="testimonial-list__item--text h3">' . esc_html($title) . '</h3>
                    ' . $testimonial_description . '
                    <div class="testimonial-list__container">' . 
                       '<div class="subcontainer">' . $image_html . 
                         $testimonial_meta . '</div>
                    </div>
                </div>
            </div>';
        }
        wp_reset_postdata();
    } else {
        $loop = '<div class="swiper-slide"><p>No testimonials found.</p></div>';
    }

    return '<div class="posts-testimonials-slider swiper">
        <div class="testimonial-list swiper-wrapper">' . $loop . '</div>
     </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>';
}
add_shortcode('testimonials', 'testimonialsShortcode');
