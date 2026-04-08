<?php
// Loop products [products-loop]
function products_loop_shortcode() {
	$args = array(
		'post_type' => 'products',
		'posts_per_page' => 9999,
		'orderby'	=> 'meta_value_num',
		'order'	=> 'ASC'
	);
	$loop_f = new WP_Query( $args );
	global $post;
	if ( $loop_f->have_posts() ) {
		while ( $loop_f->have_posts() ) : $loop_f->the_post();
			$machine_details = get_fields();					
			$url = get_permalink($post->ID);
			$img = get_the_post_thumbnail_url($post->ID, 'full');
			if ( !empty( $img ) ) { 
				$img;
			} else { 
				$img = get_template_directory_uri() .'/assets/img/bg/machine-placeholder.png';
			}
			$cat = wp_get_post_terms(get_the_ID(), 'category')[0]->name  ?? '';
			$order_nr = $machine_details['order_nr'] ?? '';
			$title = get_the_title();
			$garums = $machine_details['garums'];
			if ( !empty( $garums ) ) {
				$garums_txt = '';
				if (get_locale() == 'lv') { $garums_txt = 'Garums'; } if (get_locale() == 'en_GB') { $garums_txt = 'Length'; }
				$garums = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$garums_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$garums.'mm</span></div>';
			} else {
				$garums = '';
			}
			$platums = $machine_details['platums'];
			if ( !empty( $platums ) ) {
				$platums_txt = '';
				if (get_locale() == 'lv') { $platums_txt = 'Platums'; } if (get_locale() == 'en_GB') { $platums_txt = 'Width'; }
				$platums = '<div class="wpblocks-postacffield"><b>'.$platums_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$platums.'mm</span></div>';
			} else {
				$platums = '';
			}
			$augstums = $machine_details['augstums'];
			if ( !empty( $augstums ) ) {
				$augstums_txt = '';
				if (get_locale() == 'lv') { $augstums_txt = 'Augstums'; } if (get_locale() == 'en_GB') { $augstums_txt = 'Height'; }
				$augstums = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$augstums_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$augstums.'mm</span></div>';
			} else {
				$augstums = '';
			}
			$maksimalais_pacelsanas_augstums_mm = $machine_details['maksimalais_pacelsanas_augstums_mm'];
			if ( !empty( $maksimalais_pacelsanas_augstums_mm ) ) {
				$pacelsanas_txt = '';
				if (get_locale() == 'lv') { $pacelsanas_txt = 'Max augstums'; } if (get_locale() == 'en_GB') { $pacelsanas_txt = 'Max Height'; }
				$maksimalais_pacelsanas_augstums_mm = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$pacelsanas_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$maksimalais_pacelsanas_augstums_mm.'mm</span></div>';
			} else {
				$maksimalais_pacelsanas_augstums_mm = '';
			}
			$dzinejs = $machine_details['dzinejs'];
			if ( !empty( $dzinejs ) ) {
				$dzinejs_txt = '';
				if (get_locale() == 'lv') { $dzinejs_txt = 'Dzinējs'; } if (get_locale() == 'en_GB') { $dzinejs_txt = 'Engine'; }
				$dzinejs = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$dzinejs_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$dzinejs.'mm</span></div>';
			} else {
				$dzinejs = '';
			}			
			$pasmasa_kg = $machine_details['pasmassa_kg'];
			if ( !empty( $pasmasa_kg ) ) {
				$pasmasa_txt = '';
				if (get_locale() == 'lv') { $pasmasa_txt = 'Pašmasa'; } if (get_locale() == 'en_GB') { $pasmasa_txt = 'Curb weight'; }
				$pasmasa_kg = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$pasmasa_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$pasmasa_kg.'kg</span></div>';
			} else {
				$pasmasa_kg = '';
			}
			$celtspeja_kg = $machine_details['celtspeja_kg_'];
			if ( !empty( $celtspeja_kg ) ) { 
				$celtspeja_txt = '';
				if (get_locale() == 'lv') { $celtspeja_txt = 'Celtspēja'; } if (get_locale() == 'en_GB') { $celtspeja_txt = 'Lifting capacity'; }
				$celtspeja_kg = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$celtspeja_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$celtspeja_kg.'kg</span></div>';
			} else {
				$celtspeja_kg = '';
			}
			$sakabes_veids = $machine_details['sakabes_veids'];
			if ( !empty( $sakabes_veids ) ) { 
				$sakabe_txt = '';
				if (get_locale() == 'lv') { $sakabe_txt = 'Sakabe'; } if (get_locale() == 'en_GB') { $sakabe_txt = 'Coupling'; }
				$sakabes_veids = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$sakabe_txt.':</b></span> <span class="wpblocks-postacffield__value">'.$sakabes_veids.'</span></div>';
			} else {
				$sakabes_veids = '';
			}
			$cena = $machine_details['cena'];
			if ( !empty( $cena ) ) {
				$cena_txt = '';
				if (get_locale() == 'lv') { $cena_txt = 'Cena'; } if (get_locale() == 'en_GB') { $cena_txt = 'Price'; }
				$cena = '<div class="wpblocks-postacffield"><span class="wpblocks-postacffield__label"><b>'.$cena_txt.':</b></span> <span class="wpblocks-postacffield__value">€'.$cena.'</span></div>';
			} else {
				$cena = '';
			}

			$currentUrl2 = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$currentUrl2 = rtrim($currentUrl2,"/");
			$seo2 = get_fields('options')['wp_seo_list']  ?? '';
			$home_url2 = get_home_url();
			foreach( (array) $seo2 as $id ) {
				$seo_url2 = $seo2[$id]['wp_seo_url'] ?? '';
				$sUrl2 = $home_url2.'/resources/machine-selector'.$seo_url2.'';
				$seo_desc = $seo2[$id]['wp_seo_desc'] ?? '';
				if ( $sUrl2 === $currentUrl2 ) {
					$seo_desc2 = '<br><div class="seo-filter-wrap-description">'.$seo_desc.'</div>';
				}
			}
			//var_dump($machine_details);
			$loop_products =  $loop_products ?? null;
			$loop_products .='
				<div class="machine type-products col-12 col-lg-4" style="order:'.$order_nr.'">
					<a href="'.$url.'"><figure class="wp-block-post-featured-image">
						<img src="'.$img.'" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="'.$title.'" width="100%" />
					</figure></a>
					<div class="taxonomy-category">'.$cat.'</div>
					<a href="'.$url.'"><div class="wp-block-post-title"><strong>'.$title.'</strong></div></a>
					<hr />
					'.$garums.'
					'.$platums.'
					'.$augstums.'
					'.$maksimalais_pacelsanas_augstums_mm.'
					'.$dzinejs.'
					'.$pasmasa_kg.'					
					'.$celtspeja_kg.'
					'.$sakabes_veids.'
					'.$cena.'		
				</div>';
		endwhile;
		return '<div class="container-boxed products-loop wp-block-query--products"><div class="wp-block-wpblocks-row-section row">'.do_shortcode("[fe_chips]").$loop_products.'</div>'.$seo_desc2.'</div>';
	} else {
		echo '<div class="wp-block-wpblocks-row-section container-boxed" style="margin: 0 auto"><div class="col-12">';
		echo __( 'No products found' );
		echo '</div></div>';
	}
	wp_reset_postdata();
}
add_shortcode( 'products-loop', 'products_loop_shortcode' );