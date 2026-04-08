<?php
/**
 * Title: Location Pages Content
 * Slug: location-page
 * Categories: wp-patterns-main
 */
if (!function_exists('get_field') || get_field('loc_pages', 'option') !== 'true') { return; }
$loc_random_text = get_field('loc_random_text', 'option') ?: '';
$loc_random_images = get_field('loc_random_images', 'option') ?: [];
$city = get_field('city') ?: '';
$county = get_field('county') ?: '';
$image = '';
if (is_array($loc_random_images) && $loc_random_images) {
    $random = $loc_random_images[array_rand($loc_random_images)];
    $image = is_array($random) ? ($random['url'] ?? '') : $random;
}
$text = str_replace(['<span class="loc-city"></span>', '<span class="loc-county"></span>'], [esc_html($city), esc_html($county)], (string) $loc_random_text);
?>
<!-- wp:media-text {"mediaPosition":"right","mediaType":"image","verticalAlignment":"top","className":"wp-theme-section"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-top wp-theme-section">
	<div class="wp-block-media-text__content"><?php echo wp_kses_post($text); ?></div>
	<figure class="wp-block-media-text__media"><?php if ($image) : ?><img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($city ?: get_the_title()); ?>" loading="lazy" /><?php endif; ?></figure>
</div>
<!-- /wp:media-text -->
