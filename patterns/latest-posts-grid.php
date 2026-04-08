<?php
/**
 * Title: Latest Posts Grid
 * Slug: wp-theme/latest-posts-grid
 * Categories: wp-patterns-main
 */
?>
<!-- wp:group {"className":"wp-theme-section wp-theme-latest-posts","layout":{"type":"constrained"}} --><div class="wp-block-group wp-theme-section wp-theme-latest-posts"><!-- wp:group {"align":"wide","layout":{"type":"constrained"}} --><div class="wp-block-group alignwide"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Latest blog posts</h2><!-- /wp:heading --><!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"displayLayout":{"type":"flex","columns":3}} --><div class="wp-block-query"><!-- wp:post-template --><!-- wp:group {"className":"wp-theme-card","layout":{"type":"constrained"}} --><div class="wp-block-group wp-theme-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10"} /--><!-- wp:post-title {"isLink":true,"level":3,"fontSize":"large"} /--><!-- wp:post-date {"fontSize":"small","textColor":"muted"} /--><!-- wp:post-excerpt {"moreText":"Read more"} /--></div><!-- /wp:group --><!-- /wp:post-template --></div><!-- /wp:query --></div><!-- /wp:group --></div><!-- /wp:group -->
