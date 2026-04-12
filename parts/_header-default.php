<?php
/**
 * Title: Header Default
 * Slug: header-default
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: true
 */
?>
<!-- wp:wpbb/row {"containerClass":"container-fluid","customClasses":"px-3 px-xl-4 py-2 py-xl-3 bg-white border-bottom align-items-center wp-theme-site-header sticky-top wp-theme-site-header--default"} -->
<!-- wp:wpbb/column {"xs":12,"lg":2,"customClasses":"d-flex align-items-center justify-content-center justify-content-lg-start mb-2 mb-lg-0"} -->
<!-- wp:shortcode -->[wp_theme_site_logo variant="header"]<!-- /wp:shortcode -->
<!-- /wp:wpbb/column -->
<!-- wp:wpbb/column {"xs":12,"lg":3,"customClasses":"mb-2 mb-lg-0"} -->
<!-- wp:wpbb/ajax-search {"title":"","placeholder":"Search...","resultsLimit":6,"showExcerpt":true,"showPrice":false,"showButton":false,"className":"border-0 shadow-none wp-theme-header-search"} /-->
<!-- /wp:wpbb/column -->
<!-- wp:wpbb/column {"xs":12,"lg":4,"customClasses":"mb-2 mb-lg-0 header-nav"} -->
<!-- wp:shortcode -->[wp_theme_menu location="wp-header-menu" class="justify-content-center justify-content-lg-center align-items-center gap-3" menu_class="navbar-nav flex-row flex-wrap justify-content-center gap-3" fallback="Assign WP Header Menu in Appearance → Menus"]<!-- /wp:shortcode -->
<!-- /wp:wpbb/column -->
<!-- wp:wpbb/column {"xs":12,"lg":3,"customClasses":"text-center text-lg-end"} -->
<!-- wp:shortcode -->[wp_theme_header_extras]<!-- /wp:shortcode -->
<!-- /wp:wpbb/column -->
<!-- /wp:wpbb/row -->
