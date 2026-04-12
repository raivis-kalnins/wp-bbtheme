# wp-theme

Optimized parent block theme for ACF Pro + WP BBuilder.


## SCSS + ACF Theme Style Options

This build removes the Bootstrap SCSS dependency and keeps only a lightweight internal SCSS base, grid helpers, breakpoint mixins, and utility classes. Theme style tokens are editable in **ACF Options -> Theme Styles** and are synced to:

- `assets/css/acf-theme-vars.css` for runtime
- `src/scss/_acf-variables.generated.scss` for development builds


## Demo import and Polylang menus

The first demo import supports these menu locations:

- `WP Header Top Menu`
- `WP Header Menu`
- `WP Footer Menu`

When Polylang is active, the theme also respects language-specific menu names using this pattern:

- `WP Header Top Menu English`
- `WP Header Top Menu Русский`
- `WP Header Top Menu Latviešu valoda`
- `WP Header Menu English`
- `WP Header Menu Русский`
- `WP Header Menu Latviešu valoda`
- `WP Footer Menu English`
- `WP Footer Menu Русский`
- `WP Footer Menu Latviešu valoda`

The `[wp_theme_menu]` shortcode resolves the current language menu first and falls back to the generic menu name if a language-specific menu is not found.

The header language switcher uses Polylang links automatically when the plugin is active.


## Final clean package notes

- Theme Settings are available in WordPress admin at `Settings -> Theme Settings` using the slug `wp-theme-settings`.
- This package was rebuilt from the last known-good Theme Settings version and repackaged as an install-ready theme zip.
- The bundled `screenshot.jpg` has been resized to the WordPress-recommended preview format (1200x900).
- If you use ACF Pro, keep it active so Theme Settings fields render correctly.


## Final polish pass
This package includes a final frontend cleanup pass focused on stability and presentation:

- no-jQuery header and menu behavior
- simplified megamenu behavior with one panel per top-level parent item
- cleaner demo spacing and more consistent vertical rhythm
- safer mobile menu toggle and submenu handling
- improved dark mode support for header, menu, demo cards, CTA, and footer
- install-ready structure with `style.css` at the theme root

Recommended install order:
1. Install and activate the parent theme
2. Install and activate the child theme
3. Confirm **Settings -> Theme Settings** still works
4. Test header, megamenu, dark mode, and footer
