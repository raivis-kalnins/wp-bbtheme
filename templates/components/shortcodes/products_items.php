<?php
// Add short code for Single Products [products_items]
function shortcode_products_items() {
	$home_url = get_site_url();
	$f = get_fields(); //print_r($f);
	$photos = $f['photos'] ?? '';
	
	$garums_txt = ''; if (get_locale() == 'lv') { $garums_txt = 'Kopējais garums (mm) '; } if (get_locale() == 'en_GB') { $garums_txt = 'Total length (mm) '; }
	$platums_txt = ''; if (get_locale() == 'lv') { $platums_txt = 'Kopējais platums (mm) '; } if (get_locale() == 'en_GB') { $platums_txt = 'Total width (mm) '; }
	$augstums_txt = ''; if (get_locale() == 'lv') { $augstums_txt = 'Kopējais augstums (mm) '; } if (get_locale() == 'en_GB') { $augstums_txt = 'Total height (mm) '; }
	$maksimalais_pacelsanas_augstums_mm_txt = ''; if (get_locale() == 'lv') { $maksimalais_pacelsanas_augstums_mm_txt = 'Maksimālais pacelšanas augstums (mm)  '; } if (get_locale() == 'en_GB') { $maksimalais_pacelsanas_augstums_mm_txt = 'Maximum lifting height (mm) '; }
	$maksimalais_pacelsanas_augstums_pie_tapas_mm_txt = ''; if (get_locale() == 'lv') { $maksimalais_pacelsanas_augstums_pie_tapas_mm_txt = 'Maksimālais pacelšanas augstums pie tapas (mm) '; } if (get_locale() == 'en_GB') { $maksimalais_pacelsanas_augstums_pie_tapas_mm_txt = 'Maximum lifting height at pin (mm)'; }
	$celtspeja_kg_txt = ''; if (get_locale() == 'lv') { $celtspeja_kg_txt = 'Celtspēja (kg) '; } if (get_locale() == 'en_GB') { $celtspeja_kg_txt = 'Load capacity (kg) '; }
	$sakabes_veids_txt = ''; if (get_locale() == 'lv') { $sakabes_veids_txt = 'Sakabes veids '; } if (get_locale() == 'en_GB') { $sakabes_veids_txt = 'Coupling type '; }
	$minimalais_apgriesanas_radiuss_mm_txt = ''; if (get_locale() == 'lv') { $minimalais_apgriesanas_radiuss_mm_txt = 'Minimālais apgriešanas radiuss (mm) '; } if (get_locale() == 'en_GB') { $minimalais_apgriesanas_radiuss_mm_txt = 'Minimum cutting radius (mm) '; }
	$dizeldzinejs_cilindru_skaits_gab_txt = ''; if (get_locale() == 'lv') { $dizeldzinejs_cilindru_skaits_gab_txt = 'Dīzeļdzinējs (cilindru skaits gab.) '; } if (get_locale() == 'en_GB') { $dizeldzinejs_cilindru_skaits_gab_txt = 'Diesel engine (number of cylinders) '; }
	$dzinejs_txt = ''; if (get_locale() == 'lv') { $dzinejs_txt = 'Dzinējs (mm) '; } if (get_locale() == 'en_GB') { $dzinejs_txt = 'Engine (mm) '; }
	$dzineja_jauda_kw_txt = ''; if (get_locale() == 'lv') { $dzineja_jauda_kw_txt = 'Dzinēja jauda (kW) '; } if (get_locale() == 'en_GB') { $dzineja_jauda_kw_txt = 'Engine power (kW) '; }
	$dzesesanas_veids_txt = ''; if (get_locale() == 'lv') { $dzesesanas_veids_txt = 'Dzesēšanas veids '; } if (get_locale() == 'en_GB') { $dzesesanas_veids_txt = 'Cooling type '; }
	$dzineja_nominalie_apgriezieni_txt = ''; if (get_locale() == 'lv') { $dzineja_nominalie_apgriezieni_txt = 'Dzinēja nominālie apgriezieni '; } if (get_locale() == 'en_GB') { $dzineja_nominalie_apgriezieni_txt = 'Rated engine speed '; }
	$dzineja_tuksgaitas_apgriezieni_txt = ''; if (get_locale() == 'lv') { $dzineja_tuksgaitas_apgriezieni_txt = 'Dzinēja tukšgaitas apgriezieni(mm) '; } if (get_locale() == 'en_GB') { $dzineja_tuksgaitas_apgriezieni_txt = 'Engine idle speed (mm) '; }
	$pasmassa_kg_txt = ''; if (get_locale() == 'lv') { $pasmassa_kg_txt = 'Pašmassa (kg) '; } if (get_locale() == 'en_GB') { $pasmassa_kg_txt = 'Curb weight (kg) '; }
	$degvielas_paterins_txt = ''; if (get_locale() == 'lv') { $degvielas_paterins_txt = 'Degvielas patēriņš '; } if (get_locale() == 'en_GB') { $degvielas_paterins_txt = 'Fuel consumption '; }
	$atrumu_skaits_gab_txt = ''; if (get_locale() == 'lv') { $atrumu_skaits_gab_txt = 'Ātrumu skaits (gab.) '; } if (get_locale() == 'en_GB') { $atrumu_skaits_gab_txt = 'Number of speeds (pcs.) '; }
	$maksimalais_atrums_kmh_txt = ''; if (get_locale() == 'lv') { $maksimalais_atrums_kmh_txt = 'Maksimālais ātrums km/h '; } if (get_locale() == 'en_GB') { $maksimalais_atrums_kmh_txt = 'Maximum speed km/h '; }
	$dzineja_tilpums__txt = ''; if (get_locale() == 'lv') { $dzineja_tilpums__txt = 'Dzinēja tilpums (l) '; } if (get_locale() == 'en_GB') { $dzineja_tilpums__txt = 'Engine displacement (l) '; }
	$maksimalais_griezes_moments_nm_txt = ''; if (get_locale() == 'lv') { $maksimalais_griezes_moments_nm_txt = 'Maksimālais griezes moments (N·m) '; } if (get_locale() == 'en_GB') { $maksimalais_griezes_moments_nm_txt = 'Maximum torque (N m) '; }
	$lad_kataloga_nr_txt = ''; if (get_locale() == 'lv') { $lad_kataloga_nr_txt = 'Lad kataloga Nr '; } if (get_locale() == 'en_GB') { $lad_kataloga_nr_txt = 'Lad catalog No. '; }
	$cena_txt = ''; if (get_locale() == 'lv') { $cena_txt = 'Cena Eur '; } if (get_locale() == 'en_GB') { $cena_txt = 'Price in Euros'; }

	$garums = $f['garums'] ?? ''; if ( $garums ) { $garums_item = '<tr><td><span>'.$garums_txt.'</span></td><td><b>'.$garums.'</b></td></tr>'; } else { $garums_item = ''; }
	$platums = $f['platums'] ?? ''; if ( $platums ) { $platums_item = '<tr><td><span>'.$platums_txt.'</span></td><td><b>'.$platums.'</b></td></tr>'; } else { $platums_item = ''; }
	$augstums = $f['augstums'] ?? ''; if ( $augstums ) { $augstums_item = '<tr><td><span>'.$augstums_txt.'</span></td><td><b>'.$augstums.'</b></td></tr>'; } else { $augstums_item = ''; }
	$maksimalais_pacelsanas_augstums_mm = $f['maksimalais_pacelsanas_augstums_mm'] ?? ''; if ( $maksimalais_pacelsanas_augstums_mm ) { $maksimalais_pacelsanas_augstums_mm_item = '<tr><td><span>'.$maksimalais_pacelsanas_augstums_mm_txt.'</span></td><td><b>'.$maksimalais_pacelsanas_augstums_mm.'</b></td></tr>'; } else { $maksimalais_pacelsanas_augstums_mm_item = ''; }
	$maksimalais_pacelsanas_augstums_pie_tapas_mm = $f['maksimalais_pacelsanas_augstums_pie_tapas_mm'] ?? ''; if ( $maksimalais_pacelsanas_augstums_pie_tapas_mm ) { $maksimalais_pacelsanas_augstums_pie_tapas_mm_item = '<tr><td><span>'.$maksimalais_pacelsanas_augstums_pie_tapas_mm_txt.'</span></td><td><b>'.$maksimalais_pacelsanas_augstums_pie_tapas_mm.'</b></td></tr>'; } else { $maksimalais_pacelsanas_augstums_pie_tapas_mm_item = ''; }
	$celtspeja_kg_ = $f['celtspeja_kg_'] ?? ''; if ( $celtspeja_kg_ ) { $celtspeja_kg_item = '<tr><td><span>'.$celtspeja_kg_txt.'</span></td><td><b>'.$celtspeja_kg_.'</b></td></tr>'; } else { $celtspeja_kg_item = ''; }
	$sakabes_veids = $f['sakabes_veids'] ?? ''; if ( $sakabes_veids ) { $sakabes_veids_item = '<tr><td><span>'.$sakabes_veids_txt.'</span></td><td><b>'.$sakabes_veids.'</b></td></tr>'; } else { $sakabes_veids_item = ''; }
	$minimalais_apgriesanas_radiuss_mm_ = $f['minimalais_apgriesanas_radiuss_mm_'] ?? ''; if ( $minimalais_apgriesanas_radiuss_mm_ ) { $minimalais_apgriesanas_radiuss_mm_item = '<tr><td><span>'.$minimalais_apgriesanas_radiuss_mm_txt.'</span></td><td><b>'.$minimalais_apgriesanas_radiuss_mm_.'</b></td></tr>'; } else { $minimalais_apgriesanas_radiuss_mm_item = ''; }
	$dizeldzinejs_cilindru_skaits_gab = $f['dizeldzinejs_cilindru_skaits_gab'] ?? ''; if ( $dizeldzinejs_cilindru_skaits_gab ) { $dizeldzinejs_cilindru_skaits_gab_item = '<tr><td><span>'.$dizeldzinejs_cilindru_skaits_gab_txt.'</span></td><td><b>'.$dizeldzinejs_cilindru_skaits_gab.'</b></td></tr>'; } else { $dizeldzinejs_cilindru_skaits_gab_item = ''; }
	$dzinejs = $f['dzinejs'] ?? ''; if ( $dzinejs ) { $dzinejs_item = '<tr><td><span>'.$dzinejs_txt.'</span></td><td><b>'.$dzinejs.'</b></td></tr>'; } else { $dzinejs_item = ''; }
	$dzineja_jauda_kw_ = $f['dzineja_jauda_kw_'] ?? ''; if ( $dzineja_jauda_kw_ ) { $dzineja_jauda_kw_item = '<tr><td><span>'.$dzineja_jauda_kw_txt.'</span></td><td><b>'.$dzineja_jauda_kw_.'</b></td></tr>'; } else { $dzineja_jauda_kw_item = ''; }
	$dzesesanas_veids = $f['dzesesanas_veids'] ?? ''; if ( $dzesesanas_veids ) { $dzesesanas_veids_item = '<tr><td><span>'.$dzesesanas_veids_txt.'</span></td><td><b>'.$dzesesanas_veids.'</b></td></tr>'; } else { $dzesesanas_veids_item = ''; }
	$dzineja_nominalie_apgriezieni = $f['dzineja_nominalie_apgriezieni'] ?? ''; if ( $dzineja_nominalie_apgriezieni ) { $dzineja_nominalie_apgriezieni_item = '<tr><td><span>'.$dzineja_nominalie_apgriezieni_txt.'</span></td><td><b>'.$dzineja_nominalie_apgriezieni.'</b></td></tr>'; } else { $dzineja_nominalie_apgriezieni_item = ''; }
	$dzineja_tuksgaitas_apgriezieni = $f['dzineja_tuksgaitas_apgriezieni'] ?? ''; if ( $dzineja_tuksgaitas_apgriezieni ) { $dzineja_tuksgaitas_apgriezieni_item = '<tr><td><span>'.$dzineja_tuksgaitas_apgriezieni_txt.'</span></td><td><b>'.$dzineja_tuksgaitas_apgriezieni.'</b></td></tr>'; } else { $dzineja_tuksgaitas_apgriezieni_item = ''; }
	$pasmassa_kg = $f['pasmassa_kg'] ?? ''; if ( $pasmassa_kg ) { $pasmassa_kg_item = '<tr><td><span>'.$pasmassa_kg_txt.'</span></td><td><b>'.$pasmassa_kg.'</b></td></tr>'; } else { $pasmassa_kg_item = ''; }
	$degvielas_paterins = $f['degvielas_paterins'] ?? ''; if ( $degvielas_paterins ) { $degvielas_paterins_item = '<tr><td><span>'.$degvielas_paterins_txt.'</span></td><td><b>'.$degvielas_paterins.'</b></td></tr>'; } else { $degvielas_paterins_item = ''; }
	$atrumu_skaits_gab = $f['atrumu_skaits_gab'] ?? ''; if ( $atrumu_skaits_gab ) { $atrumu_skaits_gab_item = '<tr><td><span>'.$atrumu_skaits_gab_txt.'</span></td><td><b>'.$atrumu_skaits_gab.'</b></td></tr>'; } else { $atrumu_skaits_gab_item = ''; }
	$maksimalais_atrums_kmh = $f['maksimalais_atrums_kmh'] ?? ''; if ( $maksimalais_atrums_kmh ) { $maksimalais_atrums_kmh_item = '<tr><td><span>'.$maksimalais_atrums_kmh_txt.'</span></td><td><b>'.$maksimalais_atrums_kmh.'</b></td></tr>'; } else { $maksimalais_atrums_kmh_item = ''; }
	$dzineja_tilpums_l = $f['dzineja_tilpums_l'] ?? ''; if ( $dzineja_tilpums_l ) { $dzineja_tilpums_l_item = '<tr><td><span>'.$dzineja_tilpums__txt.'</span></td><td><b>'.$dzineja_tilpums_l.'</b></td></tr>'; } else { $dzineja_tilpums_l_item = ''; }
	$maksimalais_griezes_moments_n·m_ = $f['maksimalais_griezes_moments_n·m_'] ?? ''; if ( $maksimalais_griezes_moments_n·m_ ) { $maksimalais_griezes_moments_n·m_item = '<tr><td><span>'.$maksimalais_griezes_moments_nm_txt.'</span></td><td><b>'.$maksimalais_griezes_moments_n·m_.'</b></td></tr>'; } else { $maksimalais_griezes_moments_n·m_item = ''; }
	$lad_kataloga_nr = $f['lad_kataloga_nr'] ?? ''; if ( $lad_kataloga_nr ) { $lad_kataloga_nr_item = '<tr><td><span>'.$lad_kataloga_nr_txt.'</span></td><td><b>'.$lad_kataloga_nr.'</b></td></tr>'; } else { $lad_kataloga_nr_item = ''; }
	$cena = $f['cena'] ?? ''; if ( $cena ) { $cena_item = '<tr><td><span>'.$cena_txt.'</span></td><td><b>'.$cena.'</b></td></tr>'; } else { $cena_item = ''; }
	
	$products_items__parametri = get_fields()['standarta_aprikojums_un_parametri'] ?? '';
	$thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
	$product_file = $f['product_file'] ?? ''; if ( $product_file ) { $product_file_item = '<hr style="display:inline-block;width:100%;margin:30px auto" /><br><a href="'.$product_file.'" target="_blank" class="prod-file" style="text-align:center;width:100px;display:block"><img src="'.get_template_directory_uri().'/assets/img/svg/ico-pdf.svg" alt="pdf" class="lazyloaded" data-ll-status="loaded" style="margin-bottom:10px"><p>Tehniskā <br>informācija</p></a>'; } else { $product_file_item = ''; }
	
	if ( !empty($photos) ) :
		foreach( $photos as $photo ) : 
			$url = $photo['url'];
			$loop .= '<a href="'.$url.'" class="fancybox" data-fancybox="gallery" rel="gallery"><img src="'.$url.'" alt="" width="100%" height="100%" style="float:left;margin-right:20px;width:120px;height:auto" /></a>';
		endforeach;
	endif;
	if (get_locale() == 'lv') { $btn_url = 'kontakti'; } if (get_locale() == 'en_GB') { $btn_url = 'contact'; }
	if (get_locale() == 'lv') { $btn_txt = 'Sagatavot piedāvājumu'; } if (get_locale() == 'en_GB') { $btn_txt = 'Quote'; }
	if (get_locale() == 'lv') { $h_txt = 'Standarta aprīkojums un parametri'; } if (get_locale() == 'en_GB') { $h_txt = 'Standard equipment and parameters'; }
	return '<div class="row">
				<div class="col col-12 col-lg-6">
					<!-- wp:post-content {"align":"full","layout":{"inherit":true}} /-->
					<table class="table table-bordered table-striped"><tbody>'.$garums_item.$platums_item.$augstums_item.$maksimalais_pacelsanas_augstums_mm_item.$maksimalais_pacelsanas_augstums_pie_tapas_mm_item.$celtspeja_kg_item.$sakabes_veids_item.$minimalais_apgriesanas_radiuss_mm_item.$dizeldzinejs_cilindru_skaits_gab_item.$dzinejs_item.$dzineja_jauda_kw_item.$dzesesanas_veids_item.$dzineja_nominalie_apgriezieni_item.$dzineja_tuksgaitas_apgriezieni_item.$pasmassa_kg_item.$degvielas_paterins_item.$atrumu_skaits_gab_item.$maksimalais_atrums_kmh_item.$dzineja_tilpums_l_item.$maksimalais_griezes_moments_n·m_item.$lad_kataloga_nr_item.$cena_item.'</tbody></table>
					<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
				</div>
				<div class="col col-12 col-lg-6">					
					<img src="'.$thumbnail_url.'" alt="" style="width:100%" />
					<div class="prod-gallery">'.$loop.'</div>
					'.$product_file_item.'
				</div>			
			</div>
			<div class="row">
				<div class="col col-12">
					<h2>'.$h_txt.':</h2><br><span>'.$products_items__parametri.'</span>
					<a href="'.$home_url.'/'.$btn_url.'" class="btn btn-primary quote_btn">'.$btn_txt.'</a>
				</div>
			</div>';
}
add_shortcode('products_items', 'shortcode_products_items');