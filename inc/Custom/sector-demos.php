<?php
/**
 * Shared demo-import contract.
 *
 * Child themes provide content/presentation through filters. Integrations such
 * as WooCommerce add data through actions, keeping the parent sector-neutral.
 */

defined( 'ABSPATH' ) || exit;

function wp_theme_get_demo_profile() {
	$profile = array(
		'id'               => 'business',
		'name'             => __( 'Business', 'wp-theme' ),
		'eyebrow'          => __( 'Independent business starter', 'wp-theme' ),
		'hero_title'       => __( 'A flexible website that grows with your business.', 'wp-theme' ),
		'hero_text'        => __( 'Launch a clear, accessible website using native WordPress blocks and reusable WP BBuilder layouts.', 'wp-theme' ),
		'hero_image'       => 'https://placehold.co/1200x900/e5e7eb/334155?text=Your+Business',
		'primary_label'    => __( 'Explore services', 'wp-theme' ),
		'primary_url'      => '#services',
		'secondary_label'  => __( 'Talk to us', 'wp-theme' ),
		'secondary_url'    => '#contact',
		'commerce'         => 'auto',
		'services'         => array(
			array( __( 'Strategy', 'wp-theme' ), __( 'A practical plan shaped around your market, goals and customers.', 'wp-theme' ) ),
			array( __( 'Design', 'wp-theme' ), __( 'Clear interfaces and content patterns built for real people.', 'wp-theme' ) ),
			array( __( 'Delivery', 'wp-theme' ), __( 'A maintainable WordPress build your team can keep improving.', 'wp-theme' ) ),
		),
		'industries'       => array( __( 'Professional services', 'wp-theme' ), __( 'Retail and ecommerce', 'wp-theme' ), __( 'Property and places', 'wp-theme' ), __( 'Technology teams', 'wp-theme' ) ),
		'about_title'      => __( 'Useful websites, built around useful content.', 'wp-theme' ),
		'about_text'       => __( 'This starter uses familiar WordPress editing tools, restrained styling and reusable sections. Replace the demo copy and images without rebuilding the page structure.', 'wp-theme' ),
		'about_image'      => 'https://placehold.co/1000x760/d1d5db/1f2937?text=Image+and+Text+Block',
		'cta_title'        => __( 'Ready to build the next version of your website?', 'wp-theme' ),
		'cta_text'         => __( 'Start with the imported structure, then make it unmistakably yours.', 'wp-theme' ),
	);

	$profile = apply_filters( 'wp_theme_demo_profile', $profile );
	$profile = is_array( $profile ) ? $profile : array();
	$custom  = wp_theme_sector_customizer_values();
	foreach ( array( 'hero_title', 'hero_text', 'hero_image' ) as $field ) {
		if ( isset( $custom[ $field ] ) && '' !== $custom[ $field ] ) {
			$profile[ $field ] = $custom[ $field ];
		}
	}

	return wp_parse_args( $profile, array(
		'id' => 'business', 'name' => __( 'Business', 'wp-theme' ), 'commerce' => 'auto',
		'services' => array(), 'industries' => array(),
	) );
}

/**
 * Values are stored as theme mods, so every child theme keeps its own setup.
 */
function wp_theme_sector_customizer_values() {
	$values = array();
	foreach ( array( 'brand_color', 'hero_title', 'hero_text', 'hero_image', 'card_radius' ) as $field ) {
		$value = get_theme_mod( 'wp_theme_sector_' . $field, false );
		if ( false !== $value ) {
			$values[ $field ] = $value;
		}
	}

	return $values;
}

/**
 * Register a small, native customization surface shared by every sector child.
 * Presentation is still output by the child stylesheets.
 */
function wp_theme_sector_customize_register( $customizer ) {
	$customizer->add_section( 'wp_theme_sector_presentation', array(
		'title'       => __( 'Sector demo presentation', 'wp-theme' ),
		'description' => __( 'These choices are stored separately for each child theme.', 'wp-theme' ),
		'priority'    => 35,
	) );

	$fields = array(
		'brand_color' => array( 'label' => __( 'Brand colour', 'wp-theme' ), 'type' => 'color', 'sanitize' => 'sanitize_hex_color' ),
		'hero_title'  => array( 'label' => __( 'Homepage hero title', 'wp-theme' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'hero_text'   => array( 'label' => __( 'Homepage hero introduction', 'wp-theme' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'hero_image'  => array( 'label' => __( 'Homepage hero image URL', 'wp-theme' ), 'type' => 'url', 'sanitize' => 'esc_url_raw' ),
		'card_radius' => array( 'label' => __( 'Card corner radius', 'wp-theme' ), 'type' => 'select', 'sanitize' => 'sanitize_text_field', 'choices' => array( '0px' => __( 'Square', 'wp-theme' ), '8px' => __( 'Small', 'wp-theme' ), '14px' => __( 'Medium', 'wp-theme' ), '22px' => __( 'Large', 'wp-theme' ) ) ),
	);

	foreach ( $fields as $field => $config ) {
		$id = 'wp_theme_sector_' . $field;
		$customizer->add_setting( $id, array( 'type' => 'theme_mod', 'transport' => 'refresh', 'sanitize_callback' => $config['sanitize'] ) );
		if ( 'color' === $config['type'] && class_exists( 'WP_Customize_Color_Control' ) ) {
			$customizer->add_control( new WP_Customize_Color_Control( $customizer, $id, array( 'label' => $config['label'], 'section' => 'wp_theme_sector_presentation' ) ) );
			continue;
		}
		$args = array( 'label' => $config['label'], 'section' => 'wp_theme_sector_presentation', 'type' => $config['type'] );
		if ( isset( $config['choices'] ) ) {
			$args['choices'] = $config['choices'];
		}
		$customizer->add_control( $id, $args );
	}
}
add_action( 'customize_register', 'wp_theme_sector_customize_register' );

/**
 * Child themes call this to expose customizer values as their own CSS tokens.
 */
function wp_theme_sector_customizer_css( $brand_default, $radius_default, $brand_variable = '--sector-primary', $radius_variable = '--sector-radius' ) {
	$brand  = sanitize_hex_color( get_theme_mod( 'wp_theme_sector_brand_color', $brand_default ) );
	$radius = get_theme_mod( 'wp_theme_sector_card_radius', $radius_default );
	$radius = in_array( $radius, array( '0px', '8px', '14px', '22px' ), true ) ? $radius : $radius_default;

	return ':root{' . $brand_variable . ':' . ( $brand ?: $brand_default ) . ';' . $radius_variable . ':' . $radius . ';}';
}

function wp_theme_demo_commerce_enabled( $profile = null ) {
	$profile = is_array( $profile ) ? $profile : wp_theme_get_demo_profile();
	$setting = isset( $profile['commerce'] ) ? $profile['commerce'] : 'auto';

	if ( false === $setting || 'no' === $setting || 'off' === $setting ) {
		return false;
	}

	return class_exists( 'WooCommerce' ) && defined( 'WP_THEME_WOO_SUPPORT_VERSION' );
}

function wp_theme_demo_profile_body_class( $classes ) {
	$profile   = wp_theme_get_demo_profile();
	$classes[] = 'wp-theme-sector-' . sanitize_html_class( $profile['id'] );
	$classes[] = wp_theme_demo_commerce_enabled( $profile ) ? 'wp-theme-has-commerce' : 'wp-theme-business-site';

	return array_unique( $classes );
}
add_filter( 'body_class', 'wp_theme_demo_profile_body_class' );

function wp_theme_demo_cards_markup( $items, $class_name ) {
	$html = '<div class="wp-theme-sector-card-grid ' . esc_attr( $class_name ) . '">';
	foreach ( (array) $items as $item ) {
		$title = is_array( $item ) ? ( isset( $item[0] ) ? $item[0] : '' ) : $item;
		$text  = is_array( $item ) ? ( isset( $item[1] ) ? $item[1] : '' ) : __( 'A reusable starting point ready for your own content.', 'wp-theme' );
		$html .= '<article class="wp-theme-sector-card"><span class="wp-theme-sector-card-mark" aria-hidden="true"></span><h3>' . esc_html( $title ) . '</h3><p>' . esc_html( $text ) . '</p></article>';
	}
	$html .= '</div>';

	return $html;
}

function wp_theme_build_sector_homepage_content() {
	$p          = wp_theme_get_demo_profile();
	$services   = wp_theme_demo_cards_markup( $p['services'], 'wp-theme-sector-services' );
	$industries = wp_theme_demo_cards_markup( $p['industries'], 'wp-theme-sector-industries' );
	$commerce   = apply_filters( 'wp_theme_demo_commerce_home_sections', '', $p );
	$extra      = apply_filters( 'wp_theme_demo_extra_home_sections', '', $p );
	$after_hero = apply_filters( 'wp_theme_demo_after_hero_sections', '', $p );

	$content  = '<!-- wp:group {"className":"wp-theme-sector-home"} --><div class="wp-block-group wp-theme-sector-home">';
	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-5","gutterY":"gy-5","customClasses":"container wp-theme-sector-hero align-items-center","uniqueId":"wpbb-sector-hero"} -->';
	$content .= '<!-- wp:wpbb/column {"xs":12,"lg":6,"uniqueId":"wpbb-sector-hero-copy"} -->';
	$content .= '<!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">' . esc_html( $p['eyebrow'] ) . '</p><!-- /wp:paragraph -->';
	$content .= '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html( $p['hero_title'] ) . '</h1><!-- /wp:heading -->';
	$content .= '<!-- wp:paragraph {"className":"wp-theme-sector-lead"} --><p class="wp-theme-sector-lead">' . esc_html( $p['hero_text'] ) . '</p><!-- /wp:paragraph -->';
	$content .= '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $p['primary_url'] ) . '">' . esc_html( $p['primary_label'] ) . '</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $p['secondary_url'] ) . '">' . esc_html( $p['secondary_label'] ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
	$content .= '<!-- /wp:wpbb/column --><!-- wp:wpbb/column {"xs":12,"lg":6,"uniqueId":"wpbb-sector-hero-media"} -->';
	$content .= '<!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"wp-theme-sector-hero-image"} --><figure class="wp-block-image size-large wp-theme-sector-hero-image"><img src="' . esc_url( $p['hero_image'] ) . '" alt="' . esc_attr( $p['name'] ) . '"/></figure><!-- /wp:image -->';
	$content .= '<!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';
	$content .= $after_hero;

	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4","customClasses":"container wp-theme-sector-section","anchor":"industries","uniqueId":"wpbb-sector-industries"} --><!-- wp:wpbb/column {"xs":12,"uniqueId":"wpbb-sector-industries-column"} -->';
	$content .= '<!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">' . esc_html__( 'Industries and use cases', 'wp-theme' ) . '</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html__( 'A strong foundation for different kinds of work.', 'wp-theme' ) . '</h2><!-- /wp:heading -->';
	$content .= '<!-- wp:html -->' . $industries . '<!-- /wp:html --><!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';

	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-5","gutterY":"gy-5","customClasses":"container wp-theme-sector-section","uniqueId":"wpbb-sector-about"} --><!-- wp:wpbb/column {"xs":12,"uniqueId":"wpbb-sector-about-column"} -->';
	$content .= '<!-- wp:media-text {"mediaPosition":"left","mediaType":"image","mediaUrl":"' . esc_url( $p['about_image'] ) . '","verticalAlignment":"center","className":"wp-theme-sector-media-text"} --><div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center wp-theme-sector-media-text"><figure class="wp-block-media-text__media"><img src="' . esc_url( $p['about_image'] ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">' . esc_html__( 'Built with native blocks', 'wp-theme' ) . '</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html( $p['about_title'] ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html( $p['about_text'] ) . '</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text -->';
	$content .= '<!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';

	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4","customClasses":"container wp-theme-sector-section","anchor":"services","uniqueId":"wpbb-sector-services"} --><!-- wp:wpbb/column {"xs":12,"uniqueId":"wpbb-sector-services-column"} -->';
	$content .= '<!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">' . esc_html__( 'What we do', 'wp-theme' ) . '</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html__( 'Services designed around real outcomes.', 'wp-theme' ) . '</h2><!-- /wp:heading --><!-- wp:html -->' . $services . '<!-- /wp:html -->';
	$content .= '<!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';
	$content .= $commerce . $extra;

	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4","customClasses":"container wp-theme-sector-section","uniqueId":"wpbb-sector-latest"} --><!-- wp:wpbb/column {"xs":12,"uniqueId":"wpbb-sector-latest-column"} --><!-- wp:wpbb/blog-filter {"postsToShow":3,"title":"Latest insights"} /--><!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';
	$content .= '<!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4","customClasses":"container wp-theme-sector-section","uniqueId":"wpbb-sector-faq"} --><!-- wp:wpbb/column {"xs":12,"uniqueId":"wpbb-sector-faq-column"} --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html__( 'Common questions', 'wp-theme' ) . '</h2><!-- /wp:heading --><!-- wp:details --><details class="wp-block-details"><summary>' . esc_html__( 'Can I change every section?', 'wp-theme' ) . '</summary><p>' . esc_html__( 'Yes. The page is made from editable WordPress and WP BBuilder blocks.', 'wp-theme' ) . '</p></details><!-- /wp:details --><!-- wp:details --><details class="wp-block-details"><summary>' . esc_html__( 'Will this work without WooCommerce?', 'wp-theme' ) . '</summary><p>' . esc_html__( 'Yes. The default import becomes a complete business website when WooCommerce support is unavailable.', 'wp-theme' ) . '</p></details><!-- /wp:details --><!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';
	$content .= '<!-- wp:group {"className":"wp-theme-sector-cta","anchor":"contact"} --><div id="contact" class="wp-block-group wp-theme-sector-cta"><div class="container"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html( $p['cta_title'] ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html( $p['cta_text'] ) . '</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( wp_theme_demo_page_url( 'contact' ) ) . '">' . esc_html__( 'Start a conversation', 'wp-theme' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:group -->';
	$content .= '</div><!-- /wp:group -->';

	return apply_filters( 'wp_theme_demo_homepage_content', $content, $p );
}

function wp_theme_sector_page_content( $type, $profile ) {
	$titles = array(
		'about'      => isset( $profile['about_title'] ) ? $profile['about_title'] : __( 'About our work', 'wp-theme' ),
		'services'   => __( 'Services built for your next stage', 'wp-theme' ),
		'industries' => __( 'Industries and use cases', 'wp-theme' ),
		'contact'    => __( 'Let us talk about your project', 'wp-theme' ),
	);
	$title = isset( $titles[ $type ] ) ? $titles[ $type ] : ucfirst( $type );
	$intro = 'contact' === $type ? __( 'Tell us what you are planning and we will help you choose the right starting point.', 'wp-theme' ) : ( isset( $profile['about_text'] ) ? $profile['about_text'] : '' );

	return '<!-- wp:group {"className":"container wp-theme-sector-page"} --><div class="wp-block-group container wp-theme-sector-page"><!-- wp:paragraph {"className":"wp-theme-sector-eyebrow"} --><p class="wp-theme-sector-eyebrow">' . esc_html( $profile['name'] ) . '</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">' . esc_html( $title ) . '</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"wp-theme-sector-lead"} --><p class="wp-theme-sector-lead">' . esc_html( $intro ) . '</p><!-- /wp:paragraph --><!-- wp:media-text {"mediaType":"image","mediaUrl":"' . esc_url( $profile['about_image'] ) . '","verticalAlignment":"center"} --><div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center"><figure class="wp-block-media-text__media"><img src="' . esc_url( $profile['about_image'] ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html__( 'Clear content. Flexible structure.', 'wp-theme' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'Use this native image and text block as a starting point. Add your details, proof and calls to action in the editor.', 'wp-theme' ) . '</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text --></div><!-- /wp:group -->';
}

function wp_theme_seed_sector_pages( $profile = null ) {
	$profile = is_array( $profile ) ? $profile : wp_theme_get_demo_profile();
	$pages   = array(
		'about'      => __( 'About', 'wp-theme' ),
		'services'   => __( 'Services', 'wp-theme' ),
		'industries' => __( 'Industries', 'wp-theme' ),
		'contact'    => __( 'Contact', 'wp-theme' ),
	);

	foreach ( $pages as $slug => $title ) {
		$existing = get_page_by_path( $slug );
		$args     = array( 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => wp_theme_sector_page_content( $slug, $profile ) );
		if ( $existing instanceof WP_Post ) {
			$args['ID'] = $existing->ID;
			wp_update_post( $args );
		} else {
			wp_insert_post( $args );
		}
	}

	$blog = get_page_by_path( 'blog' );
	if ( ! $blog instanceof WP_Post ) {
		$blog_id = wp_insert_post( array( 'post_title' => __( 'Blog', 'wp-theme' ), 'post_name' => 'blog', 'post_status' => 'publish', 'post_type' => 'page' ) );
	} else {
		$blog_id = $blog->ID;
	}
	if ( $blog_id && ! is_wp_error( $blog_id ) ) {
		update_option( 'page_for_posts', (int) $blog_id );
	}

	do_action( 'wp_theme_seed_sector_pages', $profile );
}

function wp_theme_demo_navigation_items( $profile = null ) {
	$items = array(
		array( 'title' => __( 'Home', 'wp-theme' ), 'url' => home_url( '/' ), 'locations' => array( 'header', 'footer' ) ),
		array( 'title' => __( 'About', 'wp-theme' ), 'url' => wp_theme_demo_page_url( 'about' ), 'locations' => array( 'header', 'footer' ) ),
		array( 'title' => __( 'Services', 'wp-theme' ), 'url' => wp_theme_demo_page_url( 'services' ), 'locations' => array( 'header', 'footer' ) ),
		array( 'title' => __( 'Industries', 'wp-theme' ), 'url' => wp_theme_demo_page_url( 'industries' ), 'locations' => array( 'header', 'footer' ) ),
		array( 'title' => __( 'Blog', 'wp-theme' ), 'url' => wp_theme_demo_page_url( 'blog' ), 'locations' => array( 'header', 'footer' ) ),
		array( 'title' => __( 'Contact', 'wp-theme' ), 'url' => wp_theme_demo_page_url( 'contact' ), 'locations' => array( 'header', 'footer' ) ),
	);

	return apply_filters( 'wp_theme_demo_navigation_items', $items, is_array( $profile ) ? $profile : wp_theme_get_demo_profile() );
}

function wp_theme_create_demo_menus( $homepage_id, $profile = null ) {
	$profile   = is_array( $profile ) ? $profile : wp_theme_get_demo_profile();
	$items     = wp_theme_demo_navigation_items( $profile );
	$locations = array();

	// Remove menus created by earlier versions of the bundled importers. Never
	// touch arbitrary user-created menus.
	foreach ( array( 'WooCommerce Demo Menu', 'Parent Theme Demo Menu', 'Garilla Demo Header Menu', 'Garilla Demo Footer Menu', 'WP Header Menu' ) as $legacy_name ) {
		$legacy_menu = wp_get_nav_menu_object( $legacy_name );
		if ( $legacy_menu ) {
			wp_delete_nav_menu( $legacy_menu->term_id );
		}
	}

	foreach ( array( 'header' => 'Header Menu', 'footer' => 'Footer Menu' ) as $type => $name ) {
		$menu = wp_get_nav_menu_object( $name );
		$id   = $menu ? (int) $menu->term_id : wp_create_nav_menu( $name );
		if ( is_wp_error( $id ) ) {
			continue;
		}
		foreach ( wp_get_nav_menu_items( $id ) ?: array() as $item ) {
			wp_delete_post( $item->ID, true );
		}
		foreach ( $items as $item ) {
			if ( empty( $item['locations'] ) || ! in_array( $type, $item['locations'], true ) ) {
				continue;
			}
			wp_update_nav_menu_item( $id, 0, array( 'menu-item-title' => $item['title'], 'menu-item-url' => $item['url'], 'menu-item-status' => 'publish' ) );
		}
		$locations[ 'header' === $type ? 'wp-header-menu' : 'wp-footer-menu' ] = $id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
	update_option( 'wp_theme_demo_menu_profile', sanitize_key( $profile['id'] ) );
}

function wp_theme_demo_import_message( $profile = null ) {
	$profile = is_array( $profile ) ? $profile : wp_theme_get_demo_profile();
	$message = sprintf( __( '%s demo imported: homepage, sector pages, blog posts, Header Menu and Footer Menu.', 'wp-theme' ), $profile['name'] );

	return apply_filters( 'wp_theme_demo_import_message', $message, $profile );
}

function wp_theme_apply_demo_palette( $profile = null ) {
	$profile = is_array( $profile ) ? $profile : wp_theme_get_demo_profile();
	if ( empty( $profile['palette'] ) || ! is_array( $profile['palette'] ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	foreach ( $profile['palette'] as $field => $value ) {
		update_field( sanitize_key( $field ), $value, 'option' );
	}
}
