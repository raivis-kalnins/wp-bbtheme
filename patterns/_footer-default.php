<?php
/**
 * Title: Default Footer
 * Slug: footer-default
 * Categories: wp-patterns-main-core
 */
$company = function_exists('get_bloginfo') ? get_bloginfo('name') : 'Company';
$email = function_exists('get_field') ? (get_field('email', 'option') ?? '') : '';
$phone = function_exists('get_field') ? (get_field('tel', 'option')['title'] ?? '') : '';
$phone_url = function_exists('get_field') ? (get_field('tel', 'option')['url'] ?? '') : '';
$address = function_exists('get_field') ? (get_field('company_address', 'option') ?? '') : '';
?>
<!-- wp:group {"tagName":"footer","className":"wp-theme-footer wp-theme-section","layout":{"type":"constrained"}} -->
<footer class="wp-block-group wp-theme-footer wp-theme-section">
	<!-- wp:columns {"className":"wp-theme-footer-inner alignwide"} -->
	<div class="wp-block-columns wp-theme-footer-inner alignwide">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:site-logo {"width":140} /-->
			<!-- wp:site-title {"level":0} /-->
			<!-- wp:paragraph -->
			<p><?php echo esc_html($address ?: $company); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"fontSize":"medium"} --><h3 class="wp-block-heading has-medium-font-size">Navigation</h3><!-- /wp:heading -->
			<!-- wp:navigation {"layout":{"type":"flex","orientation":"vertical"}} /-->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":3,"fontSize":"medium"} --><h3 class="wp-block-heading has-medium-font-size">Contact</h3><!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php if ($email) : ?><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><br><?php endif; ?><?php if ($phone && $phone_url) : ?><a href="<?php echo esc_url($phone_url); ?>"><?php echo esc_html($phone); ?></a><?php endif; ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:separator {"backgroundColor":"surface"} --><hr class="wp-block-separator has-text-color has-surface-background-color has-background"/><!-- /wp:separator -->
	<!-- wp:paragraph {"align":"center","className":"wp-theme-meta"} -->
	<p class="has-text-align-center wp-theme-meta">© <?php echo esc_html(date('Y')); ?> <?php echo esc_html($company); ?></p>
	<!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->
