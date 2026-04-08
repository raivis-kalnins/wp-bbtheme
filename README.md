# wp-theme

Optimized parent block theme for ACF Pro + WP BBuilder.


## SCSS + ACF Theme Style Options

This build removes the Bootstrap SCSS dependency and keeps only a lightweight internal SCSS base, grid helpers, breakpoint mixins, and utility classes. Theme style tokens are editable in **ACF Options -> Theme Styles** and are synced to:

- `assets/css/acf-theme-vars.css` for runtime
- `src/scss/_acf-variables.generated.scss` for development builds
