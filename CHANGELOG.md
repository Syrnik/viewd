# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2026-05-30

### Added

- `shop_support.json` config file declaring Shop-Script Premium support

## [2.0.0] - 2026-05-30

### Added

- LICENSE file in English and Russian
- GitHub Actions workflows: PHP version compatibility check and automated release packaging
- Admin can now edit the view counter inline on the product page (UI2 backend only)
- SVG eye icon in the default frontend product view count display template
- Extended translation strings for the view counter display settings

### Changed

- Minimum required PHP version raised from 7.2 to 7.4
- Webasyst Installer 3.0 or later is now required
- Setting `frontend_product` changed from a checkbox to a radio group with three options:
  "Sidebar (frontend_product.block_aux)", "Product menu area (frontend_product.menu)",
  and "Disabled". Existing saved value `1` is treated as `block_aux` (no migration needed).

## [1.1.0] - 2021-06-07

### Added

- View count is now shown in the new Shop-Script backend product editor, on the
  "Prices, Availability, Publication" tab under the Reports section

### Changed

- Plugin helper refactored to extend `waPluginViewHelper`, enabling safe template
  call via `{$viewd->views($product)}` in themes for Shop-Script 8.17 and later
- Legacy static method `shopViewdPluginViewHelper::productViews()` retained for
  backward compatibility with older themes
- Minimum required PHP version raised to 7.2

## [1.0.0] - 2019-09-30

### Added

- Initial release
- Product page view tracking via lightweight AJAX ping
- Cookie availability check — views are not counted when cookies are disabled in the browser
- Optional frontend display of product view count, configurable in plugin settings
- Backend display of total view count in the product editor

[2.0.1]: https://github.com/Syrnik/viewd/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/Syrnik/viewd/compare/1.1.0...v2.0.0
[1.1.0]: https://github.com/Syrnik/viewd/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/Syrnik/viewd/releases/tag/1.0.0
