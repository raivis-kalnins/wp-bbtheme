<?php
/**
 * Title: Delivery Info
 * Slug: delivery
 */
$home_url = get_home_url();
$f = get_fields('option');
$woo_delivery_info = $f['woo_delivery_info'] ?? '';
$woo_delivery_info_img = $f['woo_delivery_info_img']['url'] ?? '';
?>
<div class="delivery-info_content">
	<div class="row">
		<div class="col col-12 col-lg-6"><?=$woo_delivery_info?></div>
		<div class="col col-12 col-lg-6 text-sm-center text-md-right"><img src="<?=$woo_delivery_info_img?>" alt="map" /></div>
	</div>
</div>