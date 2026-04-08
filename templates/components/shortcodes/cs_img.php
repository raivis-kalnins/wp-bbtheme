<?php

function cs_img_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_avatar' => true,
        'class' => 'float-left',
        'size' => 64
    ), $atts);
    
    $output = '<div class="case-study-author ' . esc_attr($atts['class']) . '">';
    
    if ($atts['show_avatar']) {
        // Obtener el ID del post actual (case-study)
        $post_id = get_the_ID();
        
        // Obtener la imagen del campo ACF 'cs_image_person'
        $person_image = get_field('cs_image_person', $post_id);
        
        if ($person_image) {
            // Si existe la imagen ACF, mostrarla
            $output .= '<img src="' . esc_url($person_image['url']) . '" 
                             alt="' . esc_attr($person_image['alt']) . '" 
                             class="case-study-person-image" 
                             width="' . esc_attr($atts['size']) . '" 
                             height="' . esc_attr($atts['size']) . '" />';
        } else {
            // Fallback: avatar del autor si no hay imagen ACF
            $author_id = get_the_author_meta('ID');
            $output .= get_avatar($author_id, $atts['size']);
        }
    }
    
    $output .= '<span class="author-name">' . get_the_author() . '</span>';
    $output .= '</div>';
    
    return $output;
}

add_shortcode('cs_img', 'cs_img_shortcode');
