<?php
// Get ACF Images for sub menus and prepare megamenu panels.
function wp_nav_menu_objects( $items, $args ) {

    $menu = wp_get_nav_menu_object($args->menu);
    $menuImage = '<script>document.addEventListener("DOMContentLoaded",function(){const nSub=document.getElementsByClassName("dropdown-menu"),img="<li class=megaMenuImage></li>",v1=nSub[0],v2=nSub[1],v3=nSub[2],v4=nSub[3],v5=nSub[4],v6=nSub[5],v7=nSub[6],v8=nSub[7],v9=nSub[8];if(v1){v1.innerHTML+=img};if(v2){v2.innerHTML+=img};if(v3){v3.innerHTML+=img};if(v4){v4.innerHTML+=img};if(v5){v5.innerHTML+=img};if(v6){v6.innerHTML+=img};if(v7){v7.innerHTML+=img};if(v8){v8.innerHTML+=img};if(v9){v9.innerHTML+=img};});</script>';
    echo $menuImage;

    foreach( $items as $item ) :
        $menu_img = wp_get_attachment_image_url( $item->menu_img, 'full' );
        if( $menu_img ) :
            $item->classes[] = 'menu-item-img';
            $item->title .= "<style>#menu-item-$item->ID.nav-item-$item->ID.active ~ li.megaMenuImage {background-image:url('$menu_img');}</style>";
        endif;

        $mega_post_id = absint($item->mega_post_id ?? 0);
        if ( $mega_post_id ) :
            $item->classes[] = 'has-megamenu-panel';
            $item->classes[] = 'megamenu-' . $mega_post_id;
            $item->wp_theme_megamenu_panel = '<div class="megamenu-modal megamenu-' . $mega_post_id . '" hidden>' . do_blocks(get_post_field('post_content', $mega_post_id)) . '</div>';
        endif;
    endforeach;

    if ($menu) {
        $menu_classes = [];
        if (get_field('sticky_header', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'header', 'class' => 'wp-nav-menu__sticky-header'];
        }
        if (get_field('mega_menu', $menu) === 'true') {
            $menu_classes[] = ['selector' => '.header-nav', 'class' => 'wp-nav-menu__megamenu'];
        }
        if (get_field('last_button', $menu) === 'true') {
            $menu_classes[] = ['selector' => '.header-nav', 'class' => 'wp-nav-menu__lastnavbtn'];
        }
        if (get_field('search_bar', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'body', 'class' => 'wp-nav-menu__search_bar'];
        }
        if (get_field('customer_account', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'body', 'class' => 'wp-nav-menu__account'];
        }
        if (get_field('mini_cart', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'body', 'class' => 'wp-nav-menu__cart'];
        }
        if (get_field('light_dark', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'body', 'class' => 'wp-nav-menu__light-dark'];
        }
        if (get_field('language_bar', $menu) === 'true') {
            $menu_classes[] = ['selector' => 'body', 'class' => 'wp-nav-menu__lang'];
        }

        if (!empty($menu_classes)) {
            $script = '<script>document.addEventListener("DOMContentLoaded",function(){';
            foreach ($menu_classes as $definition) {
                $script .= 'var el=document.querySelector(' . wp_json_encode($definition['selector']) . ');if(el){el.classList.add(' . wp_json_encode($definition['class']) . ');}';
            }
            $script .= '});</script>';
            echo $script;
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'wp_nav_menu_objects', 10, 2);
