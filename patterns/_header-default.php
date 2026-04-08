<?php
/**
 * Title: Default Header
 * Slug: header-default
 * Categories: wp-patterns-main-core
 */
$phone = function_exists('get_field') ? (get_field('tel', 'option')['title'] ?? '') : '';
$phone_url = function_exists('get_field') ? (get_field('tel', 'option')['url'] ?? '') : '';
$email = function_exists('get_field') ? (get_field('email', 'option') ?? '') : '';
$cta_text = function_exists('get_field') ? (get_field('header_cta_text', 'option') ?? '') : '';
$cta_link = function_exists('get_field') ? (get_field('header_cta_link', 'option') ?? '') : '';
?>
<!-- wp:group {"tagName":"header","className":"wp-theme-site-header","layout":{"type":"constrained"}} -->
<header class="wp-block-group wp-theme-site-header">
	<!-- wp:group {"className":"wp-theme-header-inner wp-theme-header-top","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group wp-theme-header-inner wp-theme-header-top">
		<!-- wp:paragraph -->
		<p><?php if ($email) : ?><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><?php endif; ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:paragraph -->
		<p><?php if ($phone && $phone_url) : ?><a href="<?php echo esc_url($phone_url); ?>"><?php echo esc_html($phone); ?></a><?php endif; ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"className":"wp-theme-header-inner wp-theme-header-main","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group wp-theme-header-inner wp-theme-header-main">
		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:site-logo {"width":180} /-->
			<!-- wp:site-title {"level":0} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"wp-theme-header-actions","layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} -->
		<div class="wp-block-group wp-theme-header-actions">
			<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"center"}} /-->
			<?php if ($cta_text && $cta_link) : ?>
			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} --><div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="<?php echo esc_url($cta_link); ?>"><?php echo esc_html($cta_text); ?></a></div><!-- /wp:button --></div>
			<!-- /wp:buttons -->
			<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</header>
<!-- /wp:group -->
