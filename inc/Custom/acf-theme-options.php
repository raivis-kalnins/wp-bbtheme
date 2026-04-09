<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('wp_theme_style_defaults')) {
    function wp_theme_style_defaults() {
        return [
            'theme_brand_color'          => '#d21629',
            'theme_accent_color'         => '#4a4549',
            'theme_text_color'           => '#333333',
            'theme_heading_color'        => '#000000',
            'theme_background_color'     => '#ffffff',
            'theme_surface_color'        => '#F2F3F3',
            'theme_surface_alt_color'    => '#EDEDED',
            'theme_border_color'         => '#d9dde3',
            'theme_grey_dark_color'      => '#4a4549',
            'theme_grey_light_color'     => '#EDEDED',
            'theme_success_color'        => '#48C52C',
            'theme_warning_color'        => '#f59e0b',
            'theme_danger_color'         => '#FF0000',
            'theme_info_color'           => '#2563eb',
            'theme_link_color'           => '#d21629',
            'theme_link_hover_color'     => '#a61222',
            'theme_container_width'      => '1200px',
            'theme_content_width'        => '840px',
            'theme_wide_width'           => '1280px',
            'theme_gutter_width'         => '1.5rem',
            'theme_section_spacing'      => 'clamp(2rem, 4vw, 5rem)',
            'theme_radius'               => '18px',

            'theme_font_provider'        => 'system',
            'theme_body_font'            => "'Albert Sans', sans-serif",
            'theme_heading_font'         => "'Albert Sans', sans-serif",
            'theme_ui_font'              => "'Albert Sans', sans-serif",
            'theme_google_body_family'   => 'Albert Sans',
            'theme_google_heading_family'=> 'Albert Sans',
            'theme_google_ui_family'     => 'Albert Sans',
            'theme_google_body_weights'  => '300;400;500;600;700',
            'theme_google_heading_weights'=> '400;500;600;700;800',
            'theme_google_ui_weights'    => '400;500;600;700',
            'theme_custom_font_import_url_1' => '',
            'theme_custom_font_import_url_2' => '',
            'theme_custom_font_import_url_3' => '',
            'theme_body_weight'          => '400',
            'theme_body_weight_medium'   => '500',
            'theme_heading_weight'       => '700',
            'theme_heading_weight_light' => '500',
            'theme_ui_weight'            => '500',
            'theme_ui_weight_bold'       => '700',
            'theme_font_variables'       => [],

            'theme_small_size'           => '14px',
            'theme_small_size_tablet'    => '13px',
            'theme_small_size_mobile'    => '12px',
            'theme_body_size'            => '16px',
            'theme_body_size_tablet'     => '15px',
            'theme_body_size_mobile'     => '14px',
            'theme_large_size'           => '20px',
            'theme_large_size_tablet'    => '18px',
            'theme_large_size_mobile'    => '16px',
            'theme_h1_size'              => '45px',
            'theme_h1_size_tablet'       => '38px',
            'theme_h1_size_mobile'       => '32px',
            'theme_h2_size'              => '30px',
            'theme_h2_size_tablet'       => '26px',
            'theme_h2_size_mobile'       => '22px',
            'theme_h3_size'              => '20px',
            'theme_h3_size_tablet'       => '18px',
            'theme_h3_size_mobile'       => '17px',
            'theme_h4_size'              => '16px',
            'theme_h4_size_tablet'       => '15px',
            'theme_h4_size_mobile'       => '15px',
            'theme_h5_size'              => '16px',
            'theme_h5_size_tablet'       => '15px',
            'theme_h5_size_mobile'       => '14px',
            'theme_h6_size'              => '12px',
            'theme_h6_size_tablet'       => '12px',
            'theme_h6_size_mobile'       => '11px',

            'theme_custom_colors'        => [],

            'theme_anim_enabled'         => 1,
            'theme_anim_default_class'   => 'animate__fadeInUp',
            'theme_anim_duration'        => '1s',
            'theme_anim_delay'           => '0s',
            'theme_anim_repeat'          => '1',
            'theme_anim_disable_mobile'  => 0,
            'theme_anim_reduce_motion'   => 1,
            'theme_anim_custom_class'    => '',
            'theme_anim_preview_text'    => 'Animation preview',

            'theme_motion_enable_lottie' => 0,
            'theme_motion_lottie_url'    => '',
            'theme_motion_lottie_width'  => '240px',
            'theme_motion_lottie_height' => '240px',
            'theme_motion_lottie_speed'  => '1',
            'theme_motion_lottie_loop'   => 1,
            'theme_motion_lottie_autoplay' => 1,
            'theme_motion_enable_svg_motion' => 0,
            'theme_motion_svg_class'     => 'is-animated-svg',

            'theme_media_convert_to_avif' => 1,
            'theme_media_max_width'      => '1920',
            'theme_media_quality'        => '82',
            'theme_media_size_sm'        => '480',
            'theme_media_size_md'        => '960',
            'theme_media_size_lg'        => '1600',
            'theme_media_size_xl'        => '1920',
        ];
    }
}

if (!function_exists('wp_theme_style_tokens')) {
    function wp_theme_style_tokens() {
        $defaults = wp_theme_style_defaults();
        $tokens = [];
        foreach ($defaults as $key => $default) {
            $value = function_exists('get_field') ? get_field($key, 'option') : null;
            $tokens[$key] = ($value !== null && $value !== false && $value !== '') ? $value : $default;
        }
        foreach (['theme_custom_colors', 'theme_font_variables'] as $array_key) {
            if (!is_array($tokens[$array_key])) {
                $tokens[$array_key] = [];
            }
        }
        return $tokens;
    }
}

if (!function_exists('wp_theme_register_style_options_page')) {
    function wp_theme_register_style_options_page() {
        if (!function_exists('acf_add_options_sub_page')) {
            return;
        }

        acf_add_options_sub_page([
            'page_title'  => __('Theme Settings', 'wp-theme'),
            'menu_title'  => __('Theme Settings', 'wp-theme'),
            'menu_slug'   => 'wp-theme-settings',
            'parent_slug' => 'options-general.php',
            'capability'  => 'edit_theme_options',
            'redirect'    => false,
        ]);
    }
}
add_action('acf/init', 'wp_theme_register_style_options_page', 5);

if (!function_exists('wp_theme_style_size_triplet_fields')) {
    function wp_theme_style_size_triplet_fields($slug_prefix, $label, $desktop, $tablet, $mobile) {
        $safe = sanitize_title($slug_prefix);
        return [
            [
                'key' => 'field_' . $safe . '_label',
                'label' => '',
                'name' => '',
                'type' => 'message',
                'message' => '<strong>' . esc_html($label) . '</strong>',
                'wrapper' => ['width' => '22', 'class' => 'wp-theme-size-row-label'],
                'esc_html' => 0,
            ],
            [
                'key' => 'field_' . $safe . '_desktop',
                'label' => '',
                'name' => $slug_prefix,
                'type' => 'text',
                'default_value' => $desktop,
                'placeholder' => 'Desktop',
                'wrapper' => ['width' => '26', 'class' => 'wp-theme-size-row wp-theme-size-desktop'],
                'prepend' => 'Desktop',
            ],
            [
                'key' => 'field_' . $safe . '_tablet',
                'label' => '',
                'name' => $slug_prefix . '_tablet',
                'type' => 'text',
                'default_value' => $tablet,
                'placeholder' => 'Tablet',
                'wrapper' => ['width' => '26', 'class' => 'wp-theme-size-row wp-theme-size-tablet'],
                'prepend' => 'Tablet',
            ],
            [
                'key' => 'field_' . $safe . '_mobile',
                'label' => '',
                'name' => $slug_prefix . '_mobile',
                'type' => 'text',
                'default_value' => $mobile,
                'placeholder' => 'Mobile',
                'wrapper' => ['width' => '26', 'class' => 'wp-theme-size-row wp-theme-size-mobile'],
                'prepend' => 'Mobile',
            ],
        ];
    }
}

if (!function_exists('wp_theme_google_font_browse_markup')) {
    function wp_theme_google_font_browse_markup() {
        return '<div class="wp-theme-helper-links">'
            . '<a class="button button-secondary" href="https://fonts.google.com/" target="_blank" rel="noopener">Browse Google Fonts</a> '
            . '<a class="button button-secondary" href="https://fontsource.org/fonts" target="_blank" rel="noopener">Browse Fontsource</a> '
            . '<a class="button button-secondary" href="https://fonts.bunny.net/" target="_blank" rel="noopener">Browse Bunny Fonts</a>'
            . '</div>';
    }
}

if (!function_exists('wp_theme_animation_settings_markup')) {
    function wp_theme_animation_settings_markup() {
        $settings = function_exists('bbtheme_get_animation_settings')
            ? bbtheme_get_animation_settings()
            : [
                'default_class' => 'animate__fadeInUp',
                'default_duration' => '1s',
                'default_delay' => '0s',
                'default_repeat' => '1',
                'preview_text' => 'Animation preview',
            ];

        $grouped = function_exists('bbtheme_get_grouped_animation_registry')
            ? bbtheme_get_grouped_animation_registry()
            : [];

        ob_start();
        ?>
        <div class="bbtheme-animation-admin bbtheme-animation-admin--embedded">
            <h2 class="nav-tab-wrapper bbtheme-animation-tabs">
                <a href="#bbtheme-tab-general" class="nav-tab nav-tab-active"><?php esc_html_e('General', 'wp-theme'); ?></a>
                <a href="#bbtheme-tab-preview" class="nav-tab"><?php esc_html_e('Preview', 'wp-theme'); ?></a>
                <a href="#bbtheme-tab-library" class="nav-tab"><?php esc_html_e('Library', 'wp-theme'); ?></a>
                <a href="#bbtheme-tab-integration" class="nav-tab"><?php esc_html_e('Integration', 'wp-theme'); ?></a>
            </h2>

            <div id="bbtheme-tab-general" class="bbtheme-tab-panel is-active">
                <div class="bbtheme-grid bbtheme-grid--narrow">
                    <div class="bbtheme-card">
                        <h3><?php esc_html_e('Theme animation defaults', 'wp-theme'); ?></h3>
                        <p><?php esc_html_e('Use the fields above to set global animation defaults. The preview and library below read those same fields live.', 'wp-theme'); ?></p>
                    </div>
                    <div class="bbtheme-card">
                        <h3><?php esc_html_e('Optional motion formats', 'wp-theme'); ?></h3>
                        <p><?php esc_html_e('Lottie and SVG motion stay optional. Keep them off and the theme will not load their support files.', 'wp-theme'); ?></p>
                        <div class="wp-theme-helper-links">
                            <a class="button button-secondary" href="https://lottiefiles.com/free-animations" target="_blank" rel="noopener"><?php esc_html_e('Browse free Lottie', 'wp-theme'); ?></a>
                            <a class="button button-secondary" href="https://lottiefiles.com/tools/web-player" target="_blank" rel="noopener"><?php esc_html_e('Open Lottie player docs', 'wp-theme'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bbtheme-tab-preview" class="bbtheme-tab-panel">
                <div class="bbtheme-grid">
                    <div class="bbtheme-card">
                        <div class="bbtheme-preview-toolbar">
                            <strong><?php esc_html_e('Live preview', 'wp-theme'); ?></strong>
                            <button id="bbtheme-preview-trigger" type="button" class="button button-primary"><?php esc_html_e('Replay animation', 'wp-theme'); ?></button>
                        </div>
                        <div class="bbtheme-preview-stage">
                            <div id="bbtheme-preview-box" class="animate__animated <?php echo esc_attr($settings['default_class']); ?>">
                                <strong><?php echo esc_html($settings['preview_text'] ?? __('Animation preview', 'wp-theme')); ?></strong>
                                <span><?php echo esc_html($settings['default_class']); ?></span>
                            </div>
                        </div>
                        <div class="bbtheme-code-snippet">
                            <code id="bbtheme-animation-code-snippet">&lt;div class="animate__animated <?php echo esc_html($settings['default_class'] ?? 'animate__fadeInUp'); ?>"&gt;...&lt;/div&gt;</code>
                        </div>
                    </div>

                    <div class="bbtheme-card">
                        <h3><?php esc_html_e('Preview animation class', 'wp-theme'); ?></h3>
                        <select id="bbtheme-preview-select">
                            <?php foreach ($grouped as $group_name => $items) : ?>
                                <optgroup label="<?php echo esc_attr($group_name); ?>">
                                    <?php foreach ($items as $item) : ?>
                                        <option value="<?php echo esc_attr($item['class']); ?>" <?php selected(($settings['default_class'] ?? ''), $item['class']); ?>>
                                            <?php echo esc_html($item['label']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php esc_html_e('Use this selector to preview a different animation without changing the saved default animation field.', 'wp-theme'); ?></p>
                    </div>
                </div>
            </div>

            <div id="bbtheme-tab-library" class="bbtheme-tab-panel">
                <div class="bbtheme-card">
                    <div class="bbtheme-catalog-toolbar">
                        <strong><?php esc_html_e('Available animation classes', 'wp-theme'); ?></strong>
                        <input type="search" id="bbtheme-animation-search" class="regular-text" placeholder="<?php esc_attr_e('Search animation name or group…', 'wp-theme'); ?>">
                    </div>
                    <table class="widefat striped bbtheme-animation-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Group', 'wp-theme'); ?></th>
                                <th><?php esc_html_e('Animation', 'wp-theme'); ?></th>
                                <th><?php esc_html_e('Class', 'wp-theme'); ?></th>
                                <th><?php esc_html_e('Description', 'wp-theme'); ?></th>
                                <th><?php esc_html_e('Actions', 'wp-theme'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($grouped as $group_name => $items) : ?>
                            <?php foreach ($items as $item) : ?>
                                <tr data-search="<?php echo esc_attr(strtolower($group_name . ' ' . ($item['label'] ?? '') . ' ' . ($item['class'] ?? '') . ' ' . ($item['description'] ?? ''))); ?>">
                                    <td><?php echo esc_html($group_name); ?></td>
                                    <td><strong><?php echo esc_html($item['label'] ?? $item['class']); ?></strong></td>
                                    <td><code><?php echo esc_html($item['class'] ?? ''); ?></code></td>
                                    <td><?php echo esc_html($item['description'] ?? ''); ?></td>
                                    <td>
                                        <button type="button" class="button button-secondary bbtheme-preview-row" data-animation="<?php echo esc_attr($item['class'] ?? ''); ?>"><?php esc_html_e('Preview', 'wp-theme'); ?></button>
                                        <button type="button" class="button button-link bbtheme-copy-class" data-class="<?php echo esc_attr($item['class'] ?? ''); ?>"><?php esc_html_e('Copy class', 'wp-theme'); ?></button>
                                        <button type="button" class="button button-link bbtheme-use-animation" data-class="<?php echo esc_attr($item['class'] ?? ''); ?>"><?php esc_html_e('Use as default', 'wp-theme'); ?></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="bbtheme-tab-integration" class="bbtheme-tab-panel">
                <div class="bbtheme-grid bbtheme-grid--narrow">
                    <div class="bbtheme-card">
                        <h3><?php esc_html_e('Animate.css helper', 'wp-theme'); ?></h3>
<pre>&lt;div &lt;?php echo bbtheme_get_animation_attributes([
    'animation' =&gt; 'animate__fadeInUp',
    'duration'  =&gt; '1.2s',
    'delay'     =&gt; '150ms',
    'repeat'    =&gt; '1',
]); ?&gt;&gt;
    Content here
&lt;/div&gt;</pre>
                    </div>
                    <div class="bbtheme-card">
                        <h3><?php esc_html_e('Lottie helper', 'wp-theme'); ?></h3>
<pre>&lt;?php
echo bbtheme_render_lottie([
    'src' =&gt; 'https://example.com/animation.lottie',
    'width' =&gt; '240px',
    'height' =&gt; '240px',
    'loop' =&gt; true,
    'autoplay' =&gt; true,
]);
?&gt;</pre>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('wp_theme_register_style_fields')) {
    function wp_theme_register_style_fields() {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        $animation_choices = function_exists('bbtheme_get_animation_choices')
            ? bbtheme_get_animation_choices()
            : ['animate__fadeInUp' => 'animate__fadeInUp'];

        $fields = [];

        $fields[] = ['key' => 'tab_theme_colors', 'label' => 'Colors', 'type' => 'tab'];
        $fields[] = ['key' => 'msg_theme_colors_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Palette</strong><br><span>Theme colors sync to CSS variables automatically. Add more custom colors below if needed.</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields = array_merge($fields, [
            ['key'=>'field_theme_brand_color','label'=>'Brand','name'=>'theme_brand_color','type'=>'color_picker','default_value'=>'#d21629','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_accent_color','label'=>'Accent','name'=>'theme_accent_color','type'=>'color_picker','default_value'=>'#4a4549','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_text_color','label'=>'Text','name'=>'theme_text_color','type'=>'color_picker','default_value'=>'#333333','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_heading_color','label'=>'Heading','name'=>'theme_heading_color','type'=>'color_picker','default_value'=>'#000000','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_background_color','label'=>'Background','name'=>'theme_background_color','type'=>'color_picker','default_value'=>'#ffffff','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_surface_color','label'=>'Surface','name'=>'theme_surface_color','type'=>'color_picker','default_value'=>'#F2F3F3','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_surface_alt_color','label'=>'Surface Alt','name'=>'theme_surface_alt_color','type'=>'color_picker','default_value'=>'#EDEDED','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_border_color','label'=>'Border','name'=>'theme_border_color','type'=>'color_picker','default_value'=>'#d9dde3','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_grey_dark_color','label'=>'Grey Dark','name'=>'theme_grey_dark_color','type'=>'color_picker','default_value'=>'#4a4549','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_grey_light_color','label'=>'Grey Light','name'=>'theme_grey_light_color','type'=>'color_picker','default_value'=>'#EDEDED','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_success_color','label'=>'Success','name'=>'theme_success_color','type'=>'color_picker','default_value'=>'#48C52C','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_warning_color','label'=>'Warning','name'=>'theme_warning_color','type'=>'color_picker','default_value'=>'#f59e0b','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_danger_color','label'=>'Danger','name'=>'theme_danger_color','type'=>'color_picker','default_value'=>'#FF0000','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_info_color','label'=>'Info','name'=>'theme_info_color','type'=>'color_picker','default_value'=>'#2563eb','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_link_color','label'=>'Link','name'=>'theme_link_color','type'=>'color_picker','default_value'=>'#d21629','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            ['key'=>'field_theme_link_hover_color','label'=>'Link Hover','name'=>'theme_link_hover_color','type'=>'color_picker','default_value'=>'#a61222','wrapper'=>['width'=>'25','class'=>'wp-theme-color-item']],
            [
                'key' => 'field_theme_custom_colors',
                'label' => 'Custom Theme Colors',
                'name' => 'theme_custom_colors',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add color',
                'sub_fields' => [
                    ['key' => 'field_theme_custom_color_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => ['width' => '30']],
                    ['key' => 'field_theme_custom_color_slug', 'label' => 'Slug', 'name' => 'slug', 'type' => 'text', 'instructions' => 'Used for CSS variable names like --wp-custom-your-slug', 'wrapper' => ['width' => '30']],
                    ['key' => 'field_theme_custom_color_value', 'label' => 'Color', 'name' => 'value', 'type' => 'color_picker', 'wrapper' => ['width' => '20']],
                    ['key' => 'field_theme_custom_color_usage', 'label' => 'Usage note', 'name' => 'usage', 'type' => 'text', 'wrapper' => ['width' => '20']],
                ],
            ],
        ]);

        
        $fields[] = ['key'=>'tab_theme_general','label'=>'General','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_general_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>WP Options General</strong><br><span>Centralized general theme options kept on the ACF options store for easy use with get_field(\'option\').</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields[] = ['key'=>'field_wp_general_group_note','label'=>'','name'=>'','type'=>'message','message'=>'<div class="wp-theme-mini-note">Use this tab for generic site-wide values you want available in patterns and templates.</div>','esc_html'=>0,'wrapper'=>['class'=>'wp-theme-settings-intro']];
        $fields[] = ['key'=>'field_theme_general_cta_text','label'=>'Primary CTA Text','name'=>'theme_general_cta_text','type'=>'text','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_general_cta_url','label'=>'Primary CTA URL','name'=>'theme_general_cta_url','type'=>'url','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_general_notice','label'=>'Global Notice','name'=>'theme_general_notice','type'=>'text','wrapper'=>['width'=>'34']];

        $fields[] = ['key'=>'tab_theme_acf_hero','label'=>'ACF Hero','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_hero_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Hero defaults</strong><br><span>Default hero values for ACF hero sections and patterns.</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields[] = ['key'=>'field_theme_hero_eyebrow','label'=>'Hero Eyebrow','name'=>'theme_hero_eyebrow','type'=>'text','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_hero_title','label'=>'Hero Title','name'=>'theme_hero_title','type'=>'text','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_hero_text','label'=>'Hero Text','name'=>'theme_hero_text','type'=>'textarea','rows'=>4,'wrapper'=>['width'=>'34']];
        $fields[] = ['key'=>'field_theme_hero_button_text','label'=>'Hero Button Text','name'=>'theme_hero_button_text','type'=>'text','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_hero_button_url','label'=>'Hero Button URL','name'=>'theme_hero_button_url','type'=>'url','wrapper'=>['width'=>'33']];
        $fields[] = ['key'=>'field_theme_hero_secondary_text','label'=>'Hero Secondary Text','name'=>'theme_hero_secondary_text','type'=>'text','wrapper'=>['width'=>'34']];


$fields[] = ['key'=>'tab_theme_layout','label'=>'Layout','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_layout_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Spacing & Containers</strong>', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields = array_merge($fields, [
            ['key'=>'field_theme_container_width','label'=>'Container Width','name'=>'theme_container_width','type'=>'text','default_value'=>'1200px','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_content_width','label'=>'Content Width','name'=>'theme_content_width','type'=>'text','default_value'=>'840px','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_wide_width','label'=>'Wide Width','name'=>'theme_wide_width','type'=>'text','default_value'=>'1280px','wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_gutter_width','label'=>'Gutter','name'=>'theme_gutter_width','type'=>'text','default_value'=>'1.5rem','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_section_spacing','label'=>'Section Spacing','name'=>'theme_section_spacing','type'=>'text','default_value'=>'clamp(2rem, 4vw, 5rem)','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_radius','label'=>'Radius','name'=>'theme_radius','type'=>'text','default_value'=>'18px','wrapper'=>['width'=>'34']],
        ]);

        $fields[] = ['key'=>'tab_theme_typography','label'=>'Typography','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_typography_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Typography</strong><br><span>Font providers, import URLs, repeatable font variables, weight variables, and all responsive font sizes live here.</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields = array_merge($fields, [
            ['key'=>'field_theme_font_provider','label'=>'Font Provider','name'=>'theme_font_provider','type'=>'select','choices'=>['system'=>'System / local only','google'=>'Google Fonts','custom'=>'Custom import URL(s)'],'default_value'=>'system','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_google_font_browse','label'=>'','name'=>'','type'=>'message','message'=>wp_theme_google_font_browse_markup(),'esc_html'=>0,'wrapper'=>['width'=>'67','class'=>'wp-theme-settings-intro wp-theme-font-browse']],
            ['key'=>'field_theme_body_font','label'=>'Body Font Stack','name'=>'theme_body_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif",'wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_heading_font','label'=>'Heading Font Stack','name'=>'theme_heading_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif",'wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_ui_font','label'=>'UI Font Stack','name'=>'theme_ui_font','type'=>'text','default_value'=>"'Albert Sans', sans-serif",'wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_google_body_family','label'=>'Google Body Family','name'=>'theme_google_body_family','type'=>'text','default_value'=>'Albert Sans','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_google_heading_family','label'=>'Google Heading Family','name'=>'theme_google_heading_family','type'=>'text','default_value'=>'Albert Sans','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_google_ui_family','label'=>'Google UI Family','name'=>'theme_google_ui_family','type'=>'text','default_value'=>'Albert Sans','wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_google_body_weights','label'=>'Body Weights','name'=>'theme_google_body_weights','type'=>'text','default_value'=>'300;400;500;600;700','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_google_heading_weights','label'=>'Heading Weights','name'=>'theme_google_heading_weights','type'=>'text','default_value'=>'400;500;600;700;800','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_google_ui_weights','label'=>'UI Weights','name'=>'theme_google_ui_weights','type'=>'text','default_value'=>'400;500;600;700','wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_custom_font_import_url_1','label'=>'Custom Font CSS URL 1','name'=>'theme_custom_font_import_url_1','type'=>'url','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_custom_font_import_url_2','label'=>'Custom Font CSS URL 2','name'=>'theme_custom_font_import_url_2','type'=>'url','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_custom_font_import_url_3','label'=>'Custom Font CSS URL 3','name'=>'theme_custom_font_import_url_3','type'=>'url','wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_body_weight','label'=>'Body Weight','name'=>'theme_body_weight','type'=>'text','default_value'=>'400','wrapper'=>['width'=>'16']],
            ['key'=>'field_theme_body_weight_medium','label'=>'Body Medium','name'=>'theme_body_weight_medium','type'=>'text','default_value'=>'500','wrapper'=>['width'=>'16']],
            ['key'=>'field_theme_heading_weight','label'=>'Heading Weight','name'=>'theme_heading_weight','type'=>'text','default_value'=>'700','wrapper'=>['width'=>'17']],
            ['key'=>'field_theme_heading_weight_light','label'=>'Heading Medium','name'=>'theme_heading_weight_light','type'=>'text','default_value'=>'500','wrapper'=>['width'=>'17']],
            ['key'=>'field_theme_ui_weight','label'=>'UI Weight','name'=>'theme_ui_weight','type'=>'text','default_value'=>'500','wrapper'=>['width'=>'17']],
            ['key'=>'field_theme_ui_weight_bold','label'=>'UI Bold','name'=>'theme_ui_weight_bold','type'=>'text','default_value'=>'700','wrapper'=>['width'=>'17']],
            [
                'key' => 'field_theme_font_variables',
                'label' => 'Additional Font Variables',
                'name' => 'theme_font_variables',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Add font variable',
                'sub_fields' => [
                    ['key' => 'field_theme_font_var_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => ['width' => '18']],
                    ['key' => 'field_theme_font_var_slug', 'label' => 'Variable Slug', 'name' => 'slug', 'type' => 'text', 'instructions' => 'Creates CSS variable --wp-font-{slug}', 'wrapper' => ['width' => '16']],
                    ['key' => 'field_theme_font_var_stack', 'label' => 'Font Stack', 'name' => 'font_stack', 'type' => 'text', 'wrapper' => ['width' => '26']],
                    ['key' => 'field_theme_font_var_weights', 'label' => 'Weights', 'name' => 'weights', 'type' => 'text', 'wrapper' => ['width' => '14']],
                    ['key' => 'field_theme_font_var_import_url', 'label' => 'Import URL', 'name' => 'import_url', 'type' => 'url', 'wrapper' => ['width' => '26']],
                ],
            ],
            ['key' => 'msg_theme_typography_sizes', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<div class="wp-theme-size-header"><span>Style</span><span>Desktop</span><span>Tablet</span><span>Mobile</span></div>', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-size-header-wrap']],
        ]);
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_small_size', 'Small Text', '14px', '13px', '12px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_body_size', 'Body Text', '16px', '15px', '14px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_large_size', 'Large Text', '20px', '18px', '16px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h1_size', 'H1', '45px', '38px', '32px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h2_size', 'H2', '30px', '26px', '22px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h3_size', 'H3', '20px', '18px', '17px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h4_size', 'H4', '16px', '15px', '15px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h5_size', 'H5', '16px', '15px', '14px'));
        $fields = array_merge($fields, wp_theme_style_size_triplet_fields('theme_h6_size', 'H6', '12px', '12px', '11px'));

        $fields[] = ['key'=>'tab_theme_animations','label'=>'Animations','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_animations_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Animation controls</strong><br><span>Full animation UI with preview, library search, integration examples, plus optional Lottie and SVG motion support.</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields = array_merge($fields, [
            ['key'=>'field_theme_anim_enabled','label'=>'Enable Animations','name'=>'theme_anim_enabled','type'=>'true_false','ui'=>1,'default_value'=>1,'wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_anim_disable_mobile','label'=>'Disable on Mobile','name'=>'theme_anim_disable_mobile','type'=>'true_false','ui'=>1,'default_value'=>0,'wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_anim_reduce_motion','label'=>'Respect Reduced Motion','name'=>'theme_anim_reduce_motion','type'=>'true_false','ui'=>1,'default_value'=>1,'wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_anim_repeat','label'=>'Repeat Count','name'=>'theme_anim_repeat','type'=>'select','choices'=>['1'=>'1','2'=>'2','3'=>'3','infinite'=>'infinite'],'default_value'=>'1','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_anim_default_class','label'=>'Default Animation','name'=>'theme_anim_default_class','type'=>'select','choices'=>$animation_choices,'ui'=>1,'allow_null'=>1,'default_value'=>'animate__fadeInUp','wrapper'=>['width'=>'50']],
            ['key'=>'field_theme_anim_custom_class','label'=>'Extra Class','name'=>'theme_anim_custom_class','type'=>'text','default_value'=>'','wrapper'=>['width'=>'50']],
            ['key'=>'field_theme_anim_duration','label'=>'Duration','name'=>'theme_anim_duration','type'=>'text','default_value'=>'1s','placeholder'=>'1s or 800ms','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_anim_delay','label'=>'Delay','name'=>'theme_anim_delay','type'=>'text','default_value'=>'0s','placeholder'=>'0s or 150ms','wrapper'=>['width'=>'33']],
            ['key'=>'field_theme_anim_preview_text','label'=>'Preview Text','name'=>'theme_anim_preview_text','type'=>'text','default_value'=>'Animation preview','wrapper'=>['width'=>'34']],
            ['key'=>'field_theme_motion_enable_lottie','label'=>'Enable Lottie Support','name'=>'theme_motion_enable_lottie','type'=>'true_false','ui'=>1,'default_value'=>0,'wrapper'=>['width'=>'20']],
            ['key'=>'field_theme_motion_lottie_url','label'=>'Default Lottie URL','name'=>'theme_motion_lottie_url','type'=>'url','wrapper'=>['width'=>'40']],
            ['key'=>'field_theme_motion_lottie_speed','label'=>'Lottie Speed','name'=>'theme_motion_lottie_speed','type'=>'text','default_value'=>'1','wrapper'=>['width'=>'10']],
            ['key'=>'field_theme_motion_lottie_loop','label'=>'Loop','name'=>'theme_motion_lottie_loop','type'=>'true_false','ui'=>1,'default_value'=>1,'wrapper'=>['width'=>'10']],
            ['key'=>'field_theme_motion_lottie_autoplay','label'=>'Autoplay','name'=>'theme_motion_lottie_autoplay','type'=>'true_false','ui'=>1,'default_value'=>1,'wrapper'=>['width'=>'10']],
            ['key'=>'field_theme_motion_lottie_width','label'=>'Lottie Width','name'=>'theme_motion_lottie_width','type'=>'text','default_value'=>'240px','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_motion_lottie_height','label'=>'Lottie Height','name'=>'theme_motion_lottie_height','type'=>'text','default_value'=>'240px','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_motion_enable_svg_motion','label'=>'Enable SVG Motion Helpers','name'=>'theme_motion_enable_svg_motion','type'=>'true_false','ui'=>1,'default_value'=>0,'wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_motion_svg_class','label'=>'SVG Motion Class','name'=>'theme_motion_svg_class','type'=>'text','default_value'=>'is-animated-svg','wrapper'=>['width'=>'25']],
            ['key' => 'msg_theme_animation_full_ui', 'label' => '', 'name' => '', 'type' => 'message', 'message' => wp_theme_animation_settings_markup(), 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-animation-ui-wrap']],
        ]);

        $fields[] = ['key'=>'tab_theme_media','label'=>'Media','type'=>'tab'];
        $fields[] = ['key' => 'msg_theme_media_intro', 'label' => '', 'name' => '', 'type' => 'message', 'message' => '<strong>Import optimization</strong><br><span>Compact defaults for imported and uploaded images.</span>', 'new_lines' => 'br', 'esc_html' => 0, 'wrapper' => ['class' => 'wp-theme-settings-intro']];
        $fields = array_merge($fields, [
            ['key'=>'field_theme_media_convert_to_avif','label'=>'Convert imports to AVIF when possible','name'=>'theme_media_convert_to_avif','type'=>'true_false','ui'=>1,'default_value'=>1,'wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_max_width','label'=>'Maximum import width','name'=>'theme_media_max_width','type'=>'text','default_value'=>'1920','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_quality','label'=>'Image quality','name'=>'theme_media_quality','type'=>'text','default_value'=>'82','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_size_xl','label'=>'XL size','name'=>'theme_media_size_xl','type'=>'text','default_value'=>'1920','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_size_lg','label'=>'Large size','name'=>'theme_media_size_lg','type'=>'text','default_value'=>'1600','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_size_md','label'=>'Medium size','name'=>'theme_media_size_md','type'=>'text','default_value'=>'960','wrapper'=>['width'=>'25']],
            ['key'=>'field_theme_media_size_sm','label'=>'Small size','name'=>'theme_media_size_sm','type'=>'text','default_value'=>'480','wrapper'=>['width'=>'25']],
        ]);

        acf_add_local_field_group([
            'key'    => 'group_wp_theme_style_options',
            'title'  => __('Theme Settings', 'wp-theme'),
            'fields' => $fields,
            'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'wp-theme-settings']]],
            'style'    => 'seamless',
            'active'   => true,
        ]);
    }
}
add_action('acf/init', 'wp_theme_register_style_fields', 20);

if (!function_exists('wp_theme_collect_google_font_families')) {
    function wp_theme_collect_google_font_families($tokens) {
        $items = [];
        $families = [
            [$tokens['theme_google_body_family'] ?? '', $tokens['theme_google_body_weights'] ?? '400;500;700'],
            [$tokens['theme_google_heading_family'] ?? '', $tokens['theme_google_heading_weights'] ?? '400;500;700'],
            [$tokens['theme_google_ui_family'] ?? '', $tokens['theme_google_ui_weights'] ?? '400;500;700'],
        ];
        foreach ($families as $entry) {
            [$family, $weights] = $entry;
            $family = trim((string) $family);
            if ($family === '') {
                continue;
            }
            $weight_list = preg_replace('/[^0-9;]+/', '', (string) $weights);
            $weight_list = trim($weight_list, ';');
            $weight_list = $weight_list !== '' ? $weight_list : '400;500;700';
            $items[] = 'family=' . rawurlencode(str_replace(' ', '+', $family) . ':wght@' . $weight_list);
        }
        return array_values(array_unique($items));
    }
}

if (!function_exists('wp_theme_enqueue_font_imports')) {
    function wp_theme_enqueue_font_imports() {
        $tokens = wp_theme_style_tokens();

        if (($tokens['theme_font_provider'] ?? 'system') === 'google') {
            $families = wp_theme_collect_google_font_families($tokens);
            if (!empty($families)) {
                $url = 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
                wp_enqueue_style('wp-theme-google-fonts', $url, [], null);
            }
        }

        $custom_urls = [
            $tokens['theme_custom_font_import_url_1'] ?? '',
            $tokens['theme_custom_font_import_url_2'] ?? '',
            $tokens['theme_custom_font_import_url_3'] ?? '',
        ];

        if (($tokens['theme_font_provider'] ?? 'system') === 'custom') {
            foreach ($custom_urls as $index => $url) {
                $url = esc_url_raw((string) $url);
                if ($url !== '') {
                    wp_enqueue_style('wp-theme-custom-font-' . $index, $url, [], null);
                }
            }
        }

        if (!empty($tokens['theme_font_variables']) && is_array($tokens['theme_font_variables'])) {
            foreach ($tokens['theme_font_variables'] as $index => $row) {
                $url = esc_url_raw((string) ($row['import_url'] ?? ''));
                if ($url !== '') {
                    wp_enqueue_style('wp-theme-repeat-font-' . $index, $url, [], null);
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_font_imports', 12);
add_action('admin_enqueue_scripts', 'wp_theme_enqueue_font_imports', 12);

if (!function_exists('wp_theme_style_css_from_tokens')) {
    function wp_theme_style_css_from_tokens($tokens) {
        $map = [
            '--wp-brand-color'         => $tokens['theme_brand_color'],
            '--wp-accent-color'        => $tokens['theme_accent_color'],
            '--wp-text-color'          => $tokens['theme_text_color'],
            '--wp-heading-color'       => $tokens['theme_heading_color'],
            '--wp-background-color'    => $tokens['theme_background_color'],
            '--wp-light-bg-color'      => $tokens['theme_surface_color'],
            '--wp-surface-alt-color'   => $tokens['theme_surface_alt_color'],
            '--wp-border-color'        => $tokens['theme_border_color'],
            '--wp-grey-dark-color'     => $tokens['theme_grey_dark_color'],
            '--wp-grey-light-color'    => $tokens['theme_grey_light_color'],
            '--wp-green-color'         => $tokens['theme_success_color'],
            '--wp-warning-color'       => $tokens['theme_warning_color'],
            '--wp-red-color'           => $tokens['theme_danger_color'],
            '--wp-info-color'          => $tokens['theme_info_color'],
            '--wp-link-color'          => $tokens['theme_link_color'],
            '--wp-link-hover-color'    => $tokens['theme_link_hover_color'],
            '--wp-container-width'     => $tokens['theme_container_width'],
            '--wp-content-width'       => $tokens['theme_content_width'],
            '--wp-wide-width'          => $tokens['theme_wide_width'],
            '--wp-gutter-width'        => $tokens['theme_gutter_width'],
            '--wp-section-spacing'     => $tokens['theme_section_spacing'],
            '--wp-theme-radius'        => $tokens['theme_radius'],
            '--wp-body-font'           => $tokens['theme_body_font'],
            '--wp-headings-font'       => $tokens['theme_heading_font'],
            '--wp-ui-font'             => $tokens['theme_ui_font'],
            '--wp-body-weight'         => $tokens['theme_body_weight'],
            '--wp-body-weight-medium'  => $tokens['theme_body_weight_medium'],
            '--wp-heading-weight'      => $tokens['theme_heading_weight'],
            '--wp-heading-weight-light'=> $tokens['theme_heading_weight_light'],
            '--wp-ui-weight'           => $tokens['theme_ui_weight'],
            '--wp-ui-weight-bold'      => $tokens['theme_ui_weight_bold'],
            '--wp-font-size-small'     => $tokens['theme_small_size'],
            '--wp-body-size'           => $tokens['theme_body_size'],
            '--wp-font-size-medium'    => $tokens['theme_large_size'],
            '--wp-h1-font-size'        => $tokens['theme_h1_size'],
            '--wp-h2-font-size'        => $tokens['theme_h2_size'],
            '--wp-h3-font-size'        => $tokens['theme_h3_size'],
            '--wp-h4-font-size'        => $tokens['theme_h4_size'],
            '--wp-h5-font-size'        => $tokens['theme_h5_size'],
            '--wp-h6-font-size'        => $tokens['theme_h6_size'],
        ];

        if (!empty($tokens['theme_custom_colors']) && is_array($tokens['theme_custom_colors'])) {
            foreach ($tokens['theme_custom_colors'] as $row) {
                $slug = sanitize_title($row['slug'] ?? '');
                $value = trim((string) ($row['value'] ?? ''));
                if ($slug !== '' && $value !== '') {
                    $map['--wp-custom-' . $slug] = $value;
                }
            }
        }

        if (!empty($tokens['theme_font_variables']) && is_array($tokens['theme_font_variables'])) {
            foreach ($tokens['theme_font_variables'] as $row) {
                $slug = sanitize_title($row['slug'] ?? '');
                $stack = trim((string) ($row['font_stack'] ?? ''));
                $weights = trim((string) ($row['weights'] ?? ''));
                if ($slug !== '' && $stack !== '') {
                    $map['--wp-font-' . $slug] = $stack;
                }
                if ($slug !== '' && $weights !== '') {
                    $map['--wp-font-' . $slug . '-weights'] = $weights;
                }
            }
        }

        $css = ':root{';
        foreach ($map as $name => $value) {
            $css .= sprintf('%s:%s;', esc_html($name), trim((string) $value));
        }
        $css .= '}';

        $css .= '@media (max-width: 1024px){:root{';
        $css .= sprintf('--wp-font-size-small:%s;--wp-body-size:%s;--wp-font-size-medium:%s;--wp-h1-font-size:%s;--wp-h2-font-size:%s;--wp-h3-font-size:%s;--wp-h4-font-size:%s;--wp-h5-font-size:%s;--wp-h6-font-size:%s;',
            trim((string) $tokens['theme_small_size_tablet']),
            trim((string) $tokens['theme_body_size_tablet']),
            trim((string) $tokens['theme_large_size_tablet']),
            trim((string) $tokens['theme_h1_size_tablet']),
            trim((string) $tokens['theme_h2_size_tablet']),
            trim((string) $tokens['theme_h3_size_tablet']),
            trim((string) $tokens['theme_h4_size_tablet']),
            trim((string) $tokens['theme_h5_size_tablet']),
            trim((string) $tokens['theme_h6_size_tablet'])
        );
        $css .= '}}';

        $css .= '@media (max-width: 767px){:root{';
        $css .= sprintf('--wp-font-size-small:%s;--wp-body-size:%s;--wp-font-size-medium:%s;--wp-h1-font-size:%s;--wp-h2-font-size:%s;--wp-h3-font-size:%s;--wp-h4-font-size:%s;--wp-h5-font-size:%s;--wp-h6-font-size:%s;',
            trim((string) $tokens['theme_small_size_mobile']),
            trim((string) $tokens['theme_body_size_mobile']),
            trim((string) $tokens['theme_large_size_mobile']),
            trim((string) $tokens['theme_h1_size_mobile']),
            trim((string) $tokens['theme_h2_size_mobile']),
            trim((string) $tokens['theme_h3_size_mobile']),
            trim((string) $tokens['theme_h4_size_mobile']),
            trim((string) $tokens['theme_h5_size_mobile']),
            trim((string) $tokens['theme_h6_size_mobile'])
        );
        $css .= '}}';

        return $css;
    }
}

if (!function_exists('wp_theme_output_style_tokens')) {
    function wp_theme_output_style_tokens() {
        echo '<style id="wp-theme-style-tokens">' . wp_theme_style_css_from_tokens(wp_theme_style_tokens()) . '</style>';
    }
}
add_action('wp_head', 'wp_theme_output_style_tokens', 8);
add_action('admin_head', 'wp_theme_output_style_tokens', 8);

if (!function_exists('wp_theme_enqueue_generated_style_tokens')) {
    function wp_theme_enqueue_generated_style_tokens() {
        $css = wp_theme_style_css_from_tokens(wp_theme_style_tokens());
        if (wp_style_is('wp-theme-style', 'enqueued')) {
            wp_add_inline_style('wp-theme-style', $css);
        }
        if (wp_style_is('wp-theme-dist', 'enqueued')) {
            wp_add_inline_style('wp-theme-dist', $css);
        }
    }
}
add_action('wp_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);
add_action('admin_enqueue_scripts', 'wp_theme_enqueue_generated_style_tokens', 99);

if (!function_exists('wp_theme_write_generated_style_files')) {
    function wp_theme_write_generated_style_files($post_id) {
        if ($post_id !== 'options') {
            return;
        }
        $tokens = wp_theme_style_tokens();
        $scss = "/* Auto-generated from ACF Theme Settings. */\n";
        $scss .= '$acf-body-font: ' . $tokens['theme_body_font'] . " !default;\n";
        $scss .= '$acf-headings-font: ' . $tokens['theme_heading_font'] . " !default;\n";
        $scss .= '$acf-ui-font: ' . $tokens['theme_ui_font'] . " !default;\n";
        $css = "/* Auto-generated from ACF Theme Settings. */\n" . wp_theme_style_css_from_tokens($tokens);

        $scss_file = trailingslashit(get_template_directory()) . 'src/scss/_acf-variables.generated.scss';
        $css_file  = trailingslashit(get_template_directory()) . 'assets/css/acf-theme-vars.css';

        if (wp_is_writable(dirname($scss_file))) {
            file_put_contents($scss_file, $scss);
        }
        if (wp_is_writable(dirname($css_file))) {
            file_put_contents($css_file, $css);
        }
    }
}
add_action('acf/save_post', 'wp_theme_write_generated_style_files', 20);

if (!function_exists('wp_theme_sync_animation_settings_from_acf')) {
    function wp_theme_sync_animation_settings_from_acf($post_id) {
        if ($post_id !== 'options' || !function_exists('bbtheme_get_animation_choices')) {
            return;
        }

        $choices = bbtheme_get_animation_choices();
        $default_class = get_field('theme_anim_default_class', 'option');
        if (!is_string($default_class) || !isset($choices[$default_class])) {
            $default_class = 'animate__fadeInUp';
        }

        $repeat = (string) get_field('theme_anim_repeat', 'option');
        if (!in_array($repeat, ['1', '2', '3', 'infinite'], true)) {
            $repeat = '1';
        }

        update_option('bbtheme_animation_settings', [
            'enabled' => get_field('theme_anim_enabled', 'option') ? '1' : '',
            'default_class' => $default_class,
            'default_duration' => (string) get_field('theme_anim_duration', 'option'),
            'default_delay' => (string) get_field('theme_anim_delay', 'option'),
            'default_repeat' => $repeat,
            'disable_on_mobile' => get_field('theme_anim_disable_mobile', 'option') ? '1' : '',
            'respect_reduced_motion' => get_field('theme_anim_reduce_motion', 'option') ? '1' : '',
            'custom_class' => (string) get_field('theme_anim_custom_class', 'option'),
            'preview_text' => (string) get_field('theme_anim_preview_text', 'option'),
        ]);
    }
}
add_action('acf/save_post', 'wp_theme_sync_animation_settings_from_acf', 30);

if (!function_exists('wp_theme_render_media_free_images_panel')) {
    function wp_theme_render_media_free_images_panel() {
        if (!current_user_can('upload_files')) {
            return;
        }
        $tokens = wp_theme_style_tokens();
        ?>
        <div class="wrap wp-theme-media-tools-wrap">
            <div class="wp-theme-media-tools">
                <button type="button" class="button button-primary button-hero" id="wp-theme-toggle-free-images"><?php esc_html_e('Free Images Import', 'wp-theme'); ?></button>
                <p class="description"><?php echo esc_html(sprintf(__('Compact import mode · max %spx · AVIF %s · quality %s.', 'wp-theme'), $tokens['theme_media_max_width'], !empty($tokens['theme_media_convert_to_avif']) ? __('on', 'wp-theme') : __('off', 'wp-theme'), $tokens['theme_media_quality'])); ?></p>
            </div>

            <div id="wp-theme-free-images-panel" class="wp-theme-free-images-panel" hidden>
                <div class="wp-theme-media-toolbar">
                    <input type="search" id="wp-theme-media-query" class="regular-text" placeholder="<?php esc_attr_e('Search free images…', 'wp-theme'); ?>">
                    <button type="button" class="button button-primary" id="wp-theme-media-search"><?php esc_html_e('Search selected providers', 'wp-theme'); ?></button>
                </div>
                <div class="wp-theme-provider-grid">
                    <?php foreach ([
                        'openverse' => 'Openverse',
                        'wikimedia' => 'Wikimedia Commons',
                    ] as $provider_key => $provider_label) : ?>
                        <label class="wp-theme-provider-pill"><input type="checkbox" class="wp-theme-media-provider" value="<?php echo esc_attr($provider_key); ?>" checked> <?php echo esc_html($provider_label); ?></label>
                    <?php endforeach; ?>
                </div>
                <div class="wp-theme-helper-links wp-theme-media-quick-links">
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://unsplash.com/s/photos/" id="wp-theme-quick-unsplash"><?php esc_html_e('Unsplash', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://www.pexels.com/search/" id="wp-theme-quick-pexels"><?php esc_html_e('Pexels', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://pixabay.com/images/search/" id="wp-theme-quick-pixabay"><?php esc_html_e('Pixabay', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://giphy.com/search/" id="wp-theme-quick-giphy"><?php esc_html_e('Giphy', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://burst.shopify.com/photos/search?utf8=%E2%9C%93&q=" id="wp-theme-quick-burst"><?php esc_html_e('Burst', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://stocksnap.io/search/" id="wp-theme-quick-stocksnap"><?php esc_html_e('StockSnap.io', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://kaboompics.com/gallery?search=" id="wp-theme-quick-kaboom"><?php esc_html_e('Kaboompics', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://gratisography.com/?s=" id="wp-theme-quick-gratisography"><?php esc_html_e('Gratisography', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://picjumbo.com/?s=" id="wp-theme-quick-picjumbo"><?php esc_html_e('Picjumbo', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://www.lifeofpix.com/?s=" id="wp-theme-quick-lifeofpix"><?php esc_html_e('Life of Pix', 'wp-theme'); ?></a>
                    <a class="button button-secondary" target="_blank" rel="noopener" href="https://www.freepik.com/search?format=search&query=" id="wp-theme-quick-freepik"><?php esc_html_e('Freepik', 'wp-theme'); ?></a>
                </div>
                <div class="wp-theme-media-optimize-tools">
                    <h3><?php esc_html_e('Optimize existing upload', 'wp-theme'); ?></h3>
                    <div class="wp-theme-media-toolbar">
                        <input type="number" min="1" id="wp-theme-optimize-attachment-id" class="small-text" placeholder="<?php esc_attr_e('Attachment ID', 'wp-theme'); ?>">
                        <button type="button" class="button button-secondary" id="wp-theme-optimize-attachment"><?php esc_html_e('Optimize attachment', 'wp-theme'); ?></button>
                    </div>
                    <p class="description"><?php esc_html_e('Use this for any image already uploaded from your computer. It will resize and try AVIF conversion using the same defaults.', 'wp-theme'); ?></p>
                </div>
                <p id="wp-theme-media-status" class="wp-theme-media-status"></p>
                <div id="wp-theme-media-results" class="wp-theme-media-results"></div>
            </div>
        </div>
        <?php
    }
}
add_action('all_admin_notices', function () {
    global $pagenow;
    if ($pagenow === 'upload.php') {
        wp_theme_render_media_free_images_panel();
    }
});

if (!function_exists('wp_theme_free_image_search')) {
    function wp_theme_free_image_search() {
        check_ajax_referer('wp_theme_settings_nonce', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => __('You do not have permission to search images.', 'wp-theme')], 403);
        }

        $query = sanitize_text_field(wp_unslash($_POST['query'] ?? ''));
        $providers = array_map('sanitize_key', (array) ($_POST['providers'] ?? ['openverse', 'wikimedia']));
        $page  = max(1, absint($_POST['page'] ?? 1));

        if ($query === '') {
            wp_send_json_error(['message' => __('Enter a search term first.', 'wp-theme')], 400);
        }

        $results = [];
        foreach ($providers as $provider) {
            if ($provider === 'openverse') {
                $url = add_query_arg([
                    'q' => $query,
                    'page' => $page,
                    'page_size' => 12,
                    'license_type' => 'commercial',
                    'extension' => 'jpg,jpeg,png',
                ], 'https://api.openverse.org/v1/images/');
                $response = wp_remote_get($url, ['timeout' => 20, 'headers' => ['User-Agent' => 'WP-BBTheme Media Browser']]);
                if (!is_wp_error($response)) {
                    $body = json_decode(wp_remote_retrieve_body($response), true);
                    foreach (($body['results'] ?? []) as $item) {
                        $results[] = [
                            'title' => sanitize_text_field($item['title'] ?? __('Untitled image', 'wp-theme')),
                            'creator' => sanitize_text_field($item['creator'] ?? ''),
                            'license' => trim(sanitize_text_field(($item['license'] ?? '') . ' ' . ($item['license_version'] ?? ''))),
                            'thumbnail' => esc_url_raw($item['thumbnail'] ?? ''),
                            'url' => esc_url_raw($item['url'] ?? ''),
                            'foreign_landing_url' => esc_url_raw($item['foreign_landing_url'] ?? ''),
                            'provider' => 'Openverse',
                        ];
                    }
                }
            }

            if ($provider === 'wikimedia') {
                $url = add_query_arg([
                    'action' => 'query',
                    'generator' => 'search',
                    'gsrsearch' => $query,
                    'gsrnamespace' => '6',
                    'gsrlimit' => '12',
                    'prop' => 'imageinfo|info',
                    'inprop' => 'url',
                    'iiprop' => 'url|extmetadata',
                    'iiurlwidth' => '480',
                    'format' => 'json',
                    'origin' => '*',
                ], 'https://commons.wikimedia.org/w/api.php');
                $response = wp_remote_get($url, ['timeout' => 20, 'headers' => ['User-Agent' => 'WP-BBTheme Media Browser']]);
                if (!is_wp_error($response)) {
                    $body = json_decode(wp_remote_retrieve_body($response), true);
                    foreach (($body['query']['pages'] ?? []) as $page_item) {
                        $image = $page_item['imageinfo'][0] ?? [];
                        $meta = $image['extmetadata'] ?? [];
                        $results[] = [
                            'title' => sanitize_text_field($page_item['title'] ?? __('Untitled image', 'wp-theme')),
                            'creator' => wp_strip_all_tags($meta['Artist']['value'] ?? ''),
                            'license' => wp_strip_all_tags($meta['LicenseShortName']['value'] ?? 'Wikimedia Commons'),
                            'thumbnail' => esc_url_raw($image['thumburl'] ?? $image['url'] ?? ''),
                            'url' => esc_url_raw($image['url'] ?? ''),
                            'foreign_landing_url' => esc_url_raw($page_item['fullurl'] ?? $image['descriptionurl'] ?? ''),
                            'provider' => 'Wikimedia Commons',
                        ];
                    }
                }
            }
        }

        wp_send_json_success(['results' => $results]);
    }
}
add_action('wp_ajax_wp_theme_free_image_search', 'wp_theme_free_image_search');

if (!function_exists('wp_theme_optimize_attachment_image')) {
    function wp_theme_optimize_attachment_image($attachment_id) {
        $tokens = wp_theme_style_tokens();
        $max_width = max(320, absint($tokens['theme_media_max_width'] ?? 1920));
        $quality = max(40, min(100, absint($tokens['theme_media_quality'] ?? 82)));
        $convert_to_avif = !empty($tokens['theme_media_convert_to_avif']);

        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return ['converted' => false, 'message' => __('Imported image saved without optimization.', 'wp-theme')];
        }

        $editor = wp_get_image_editor($file);
        if (is_wp_error($editor)) {
            return ['converted' => false, 'message' => __('Image editor unavailable. Imported original file only.', 'wp-theme')];
        }

        $size = $editor->get_size();
        if (!empty($size['width']) && $size['width'] > $max_width) {
            $editor->resize($max_width, null, false);
        }

        if (method_exists($editor, 'set_quality')) {
            $editor->set_quality($quality);
        }

        $message = __('Image optimized after import.', 'wp-theme');
        $saved = null;

        if ($convert_to_avif) {
            $avif_file = preg_replace('/\.[^.]+$/', '.avif', $file);
            $saved = $editor->save($avif_file, 'image/avif');
            if (!is_wp_error($saved) && !empty($saved['path'])) {
                update_attached_file($attachment_id, $saved['path']);
                wp_update_post(['ID' => $attachment_id, 'post_mime_type' => 'image/avif']);
                $message = __('Image imported and converted to AVIF.', 'wp-theme');
            } else {
                $saved = null;
                $message = __('AVIF conversion not supported here, but image was resized/optimized.', 'wp-theme');
            }
        }

        if (!$saved) {
            $saved = $editor->save($file);
        }

        if (!is_wp_error($saved)) {
            $metadata = wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id));
            if (!is_wp_error($metadata) && !empty($metadata)) {
                wp_update_attachment_metadata($attachment_id, $metadata);
            }
        }

        return ['converted' => !empty($saved['path']) && str_ends_with((string) $saved['path'], '.avif'), 'message' => $message];
    }
}

if (!function_exists('wp_theme_free_image_import')) {
    function wp_theme_free_image_import() {
        check_ajax_referer('wp_theme_settings_nonce', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => __('You do not have permission to import images.', 'wp-theme')], 403);
        }

        $image_url = esc_url_raw(wp_unslash($_POST['image_url'] ?? ''));
        $title = sanitize_text_field(wp_unslash($_POST['title'] ?? ''));
        $creator = sanitize_text_field(wp_unslash($_POST['creator'] ?? ''));
        $license = sanitize_text_field(wp_unslash($_POST['license'] ?? ''));
        $source_url = esc_url_raw(wp_unslash($_POST['source_url'] ?? ''));
        $provider = sanitize_text_field(wp_unslash($_POST['provider'] ?? ''));

        if ($image_url === '') {
            wp_send_json_error(['message' => __('Image URL is missing.', 'wp-theme')], 400);
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_sideload_image($image_url, 0, $title, 'id');
        if (is_wp_error($attachment_id)) {
            wp_send_json_error(['message' => $attachment_id->get_error_message()], 500);
        }

        wp_update_post([
            'ID' => $attachment_id,
            'post_title' => $title !== '' ? $title : __('Imported image', 'wp-theme'),
            'post_excerpt' => $creator !== '' ? sprintf(__('Photo by %s', 'wp-theme'), $creator) : '',
        ]);

        if ($title !== '') {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $title);
        }
        if ($license !== '') {
            update_post_meta($attachment_id, '_bbtheme_image_license', $license);
        }
        if ($source_url !== '') {
            update_post_meta($attachment_id, '_bbtheme_image_source_url', $source_url);
        }
        if ($provider !== '') {
            update_post_meta($attachment_id, '_bbtheme_image_provider', $provider);
        }

        $opt_result = wp_theme_optimize_attachment_image($attachment_id);

        wp_send_json_success([
            'attachment_id' => $attachment_id,
            'edit_url' => get_edit_post_link($attachment_id, 'raw'),
            'message' => $opt_result['message'] ?? __('Image imported into Media Library.', 'wp-theme'),
        ]);
    }
}
add_action('wp_ajax_wp_theme_free_image_import', 'wp_theme_free_image_import');

if (!function_exists('wp_theme_enqueue_admin_assets')) {
    function wp_theme_enqueue_admin_assets($hook) {
        $is_theme_settings = strpos((string) $hook, 'wp-theme-settings') !== false;
        $is_media_library = $hook === 'upload.php';
        if (!$is_theme_settings && !$is_media_library) {
            return;
        }

        foreach ([
            get_template_directory() . '/assets/css/admin-theme-settings.css',
            get_template_directory() . '/assets/css/admin-animations.css',
        ] as $file) {
            if (file_exists($file)) {
                $relative = str_replace(wp_normalize_path(get_template_directory()), '', wp_normalize_path($file));
                wp_enqueue_style('wp-theme-admin-' . md5($file), get_template_directory_uri() . $relative, [], filemtime($file));
            }
        }

        $js_file = get_template_directory() . '/assets/js/admin-theme-settings.js';
        if (file_exists($js_file)) {
            wp_enqueue_style('bbtheme-animate-admin', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', [], '4.1.1');
            wp_enqueue_script('wp-theme-settings-admin', get_template_directory_uri() . '/assets/js/admin-theme-settings.js', [], filemtime($js_file), true);
            wp_localize_script('wp-theme-settings-admin', 'BBThemeAdminSettings', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_theme_settings_nonce'),
                'animationRegistry' => function_exists('bbtheme_get_animation_registry') ? bbtheme_get_animation_registry() : [],
                'strings' => [
                    'searching' => __('Searching images…', 'wp-theme'),
                    'importing' => __('Importing image…', 'wp-theme'),
                    'noResults' => __('No images found for that search.', 'wp-theme'),
                    'searchError' => __('Could not search images right now.', 'wp-theme'),
                    'importError' => __('Could not import image right now.', 'wp-theme'),
                    'imported' => __('Imported to Media Library.', 'wp-theme'),
                    'copyLabel' => __('Copy class', 'wp-theme'),
                    'copiedLabel' => __('Copied', 'wp-theme'),
                    'defaultPreviewText' => __('Animation preview', 'wp-theme'),
                ],
            ]);
        }
    }
}
add_action('admin_enqueue_scripts', 'wp_theme_enqueue_admin_assets', 20);


if (!function_exists('wp_theme_register_dynamic_media_sizes')) {
    function wp_theme_register_dynamic_media_sizes() {
        $tokens = wp_theme_style_tokens();
        add_image_size('wp-theme-sm', max(200, absint($tokens['theme_media_size_sm'] ?? 480)), 0, false);
        add_image_size('wp-theme-md', max(320, absint($tokens['theme_media_size_md'] ?? 960)), 0, false);
        add_image_size('wp-theme-lg', max(480, absint($tokens['theme_media_size_lg'] ?? 1600)), 0, false);
        add_image_size('wp-theme-xl', max(640, absint($tokens['theme_media_size_xl'] ?? 1920)), 0, false);
    }
}
add_action('after_setup_theme', 'wp_theme_register_dynamic_media_sizes', 30);

if (!function_exists('wp_theme_image_sizes_choose')) {
    function wp_theme_image_sizes_choose($sizes) {
        return array_merge($sizes, [
            'wp-theme-sm' => __('Theme Small', 'wp-theme'),
            'wp-theme-md' => __('Theme Medium', 'wp-theme'),
            'wp-theme-lg' => __('Theme Large', 'wp-theme'),
            'wp-theme-xl' => __('Theme XL', 'wp-theme'),
        ]);
    }
}
add_filter('image_size_names_choose', 'wp_theme_image_sizes_choose');

if (!function_exists('wp_theme_optimize_any_uploaded_image')) {
    function wp_theme_optimize_any_uploaded_image($attachment_id) {
        if (!wp_attachment_is_image($attachment_id) || get_post_meta($attachment_id, '_wp_theme_optimized', true)) {
            return;
        }
        $result = wp_theme_optimize_attachment_image($attachment_id);
        update_post_meta($attachment_id, '_wp_theme_optimized', 1);
        if (!empty($result['message'])) {
            update_post_meta($attachment_id, '_wp_theme_optimization_message', sanitize_text_field($result['message']));
        }
    }
}
add_action('add_attachment', 'wp_theme_optimize_any_uploaded_image', 20);


if (!function_exists('wp_theme_optimize_existing_attachment_ajax')) {
    function wp_theme_optimize_existing_attachment_ajax() {
        check_ajax_referer('wp_theme_settings_nonce', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => __('You do not have permission to optimize images.', 'wp-theme')], 403);
        }

        $attachment_id = absint($_POST['attachment_id'] ?? 0);
        if ($attachment_id < 1 || !wp_attachment_is_image($attachment_id)) {
            wp_send_json_error(['message' => __('Please enter a valid image attachment ID.', 'wp-theme')], 400);
        }

        $result = wp_theme_optimize_attachment_image($attachment_id);
        update_post_meta($attachment_id, '_wp_theme_optimized', 1);
        if (!empty($result['message'])) {
            update_post_meta($attachment_id, '_wp_theme_optimization_message', sanitize_text_field($result['message']));
        }

        wp_send_json_success([
            'attachment_id' => $attachment_id,
            'message' => $result['message'] ?? __('Attachment optimized.', 'wp-theme'),
            'edit_url' => get_edit_post_link($attachment_id, 'raw'),
        ]);
    }
}
add_action('wp_ajax_wp_theme_optimize_existing_attachment', 'wp_theme_optimize_existing_attachment_ajax');

if (!function_exists('wp_theme_remove_legacy_options_menus')) {
    function wp_theme_remove_legacy_options_menus() {
        foreach ([
            'acf-options',
            'acf-options-general-settings',
            'theme-general-settings',
            'wp-options',
            'theme-options',
            'site-options',
            'general-settings',
        ] as $slug) {
            remove_menu_page($slug);
        }
    }
}
add_action('admin_menu', 'wp_theme_remove_legacy_options_menus', 999);
