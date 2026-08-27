<?php
/**
 * Title: Header Default
 * Slug: header-default
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: true
 */
$site_name  = get_bloginfo('name') ?: __('Demo WP Theme', 'wp-theme');
$commerce   = function_exists('wp_theme_demo_commerce_enabled') && wp_theme_demo_commerce_enabled();
$account_url = $commerce && function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '';
$cart_url    = $commerce && function_exists('wc_get_cart_url') ? wc_get_cart_url() : '';
$cart_count  = $commerce && function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$search_block = class_exists('WP_Block_Type_Registry') && WP_Block_Type_Registry::get_instance()->is_registered('tfa/ajax-search');
?>
<!-- wp:group {"tagName":"header","className":"wp-theme-site-header","layout":{"type":"default"}} -->
<header class="wp-block-group wp-theme-site-header">
    <!-- wp:group {"className":"container wp-theme-header-shell","layout":{"type":"default"}} -->
    <div class="wp-block-group container wp-theme-header-shell<?php echo $commerce ? ' wp-theme-header-shell--commerce' : ''; ?>">
        <!-- wp:html --><a class="wp-theme-demo-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($site_name); ?>"><?php echo wp_theme_demo_logo('dark'); ?></a><!-- /wp:html -->
        <?php if ($commerce && $search_block) : ?>
        <!-- wp:tfa/ajax-search {"className":"wp-theme-header-search","placeholder":"Search products, SKU or pages ...","buttonText":"Search","postTypes":["product","post","page"],"resultsLimit":8,"showImage":true,"showExcerpt":false,"showPrice":true,"searchButton":true,"searchInSku":true,"searchScopeControl":"select"} /-->
        <?php elseif ($commerce) : ?>
        <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search products ...","buttonText":"Search","className":"wp-theme-header-search"} /-->
        <?php endif; ?>
        <!-- wp:html --><div class="wp-theme-demo-actions"><?php if ($account_url) : ?><a class="wp-theme-demo-action" href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('Account', 'wp-theme'); ?></a><?php else : ?><a class="wp-theme-demo-action" href="<?php echo esc_url(wp_theme_demo_page_url('contact')); ?>"><?php esc_html_e('Contact', 'wp-theme'); ?></a><?php endif; ?><?php if ($cart_url) : ?><a class="wp-theme-demo-action wp-theme-demo-cart" href="<?php echo esc_url($cart_url); ?>"><?php esc_html_e('Cart', 'wp-theme'); ?><?php if ($cart_count) : ?><span class="wp-theme-demo-cart-count"><?php echo absint($cart_count); ?></span><?php endif; ?></a><?php endif; ?><button class="wp-theme-demo-hamburger navbar-toggler-btn" type="button" aria-label="<?php esc_attr_e('Toggle menu', 'wp-theme'); ?>" aria-controls="wp-theme-primary-navigation" aria-expanded="false"><span></span><span></span><span></span></button></div><!-- /wp:html -->
    </div>
    <!-- /wp:group -->
    <!-- wp:html --><nav id="wp-theme-primary-navigation" class="wp-theme-primary-navigation" aria-label="<?php esc_attr_e('Main menu', 'wp-theme'); ?>"><div class="container"><?php echo wp_theme_demo_menu('wp-header-menu', 'wp-theme-primary-menu'); ?></div></nav><!-- /wp:html -->
</header>
<!-- /wp:group -->
