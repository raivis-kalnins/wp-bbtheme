<?php
/**
 * Title: Footer Default
 * Slug: footer-default
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: true
 */
$company = get_bloginfo('name') ?: __('Demo WP Theme', 'wp-theme');
$email = function_exists('wp_theme_acf_get') ? wp_theme_acf_get('email', 'option', '') : '';
$commerce = function_exists('wp_theme_demo_commerce_enabled') && wp_theme_demo_commerce_enabled();
?>
<!-- wp:group {"tagName":"footer","className":"wp-theme-site-footer","layout":{"type":"default"}} -->
<footer class="wp-block-group wp-theme-site-footer">
    <!-- wp:group {"className":"container py-5","layout":{"type":"default"}} -->
    <div class="wp-block-group container py-5">
        <!-- wp:columns {"className":"wp-theme-demo-footer-columns"} -->
        <div class="wp-block-columns wp-theme-demo-footer-columns">
            <!-- wp:column {"width":"40%"} -->
            <div class="wp-block-column" style="flex-basis:40%"><!-- wp:html --><a class="wp-theme-demo-footer-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($company); ?>"><?php echo wp_theme_demo_logo('light'); ?></a><!-- /wp:html --><!-- wp:paragraph --><p><?php esc_html_e('A clean, flexible WordPress foundation. Replace this starter copy with your own company message.', 'wp-theme'); ?></p><!-- /wp:paragraph --></div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:heading {"level":5} --><h5 class="wp-block-heading"><?php esc_html_e('Pages', 'wp-theme'); ?></h5><!-- /wp:heading --><!-- wp:html --><?php echo wp_theme_demo_menu('wp-footer-menu', 'wp-theme-demo-footer-menu'); ?><!-- /wp:html --></div>
            <!-- /wp:column -->
            <?php if ($commerce) : ?><!-- wp:column -->
            <div class="wp-block-column"><!-- wp:heading {"level":5} --><h5 class="wp-block-heading"><?php esc_html_e('Shop', 'wp-theme'); ?></h5><!-- /wp:heading --><!-- wp:paragraph --><p><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Products', 'wp-theme'); ?></a><br><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('My Account', 'wp-theme'); ?></a><br><a href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php esc_html_e('Cart', 'wp-theme'); ?></a></p><!-- /wp:paragraph --></div>
            <!-- /wp:column --><?php endif; ?>
            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:heading {"level":5} --><h5 class="wp-block-heading"><?php esc_html_e('Contact', 'wp-theme'); ?></h5><!-- /wp:heading --><!-- wp:paragraph --><p><?php echo $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : esc_html__('Add your contact details in Theme Settings.', 'wp-theme'); ?></p><!-- /wp:paragraph --></div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
        <!-- wp:group {"className":"wp-theme-demo-footer-bottom d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 mt-4 border-top","layout":{"type":"default"}} -->
        <div class="wp-block-group wp-theme-demo-footer-bottom d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 mt-4 border-top"><!-- wp:paragraph --><p>© <?php echo esc_html(date('Y')); ?> <?php echo esc_html($company); ?>. <?php esc_html_e('All rights reserved.', 'wp-theme'); ?></p><!-- /wp:paragraph --><!-- wp:paragraph --><p><?php echo $commerce ? esc_html__('WooCommerce ready.', 'wp-theme') : esc_html__('Built with WordPress blocks.', 'wp-theme'); ?></p><!-- /wp:paragraph --></div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</footer>
<!-- /wp:group -->
