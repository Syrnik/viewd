# Product View Counter — plugin for Shop-Script

[Русская версия](README.md)

A plugin that counts product page views and displays the counter in both the Shop-Script backend and the storefront.

## How it works

A view is recorded via an AJAX request sent from the product page after it finishes loading. This avoids false counts from server-side rendering. Views are not counted when the visitor has JavaScript or cookies disabled. Authenticated backend users are excluded from the count.

> The displayed value represents **previous** views; the current visit will be counted on the next page load.

## Features

- View counter in the Shop-Script backend — on the product editor page, "Prices, Availability, Publication" tab
- Inline editing of the view counter by an admin directly in the backend (UI 2.0 only)
- Optional frontend display of the counter in the product page sidebar
- Template override support from within a storefront theme

## Settings

The plugin has one setting — **"Display on product page"** — which controls where the view counter appears in the standard template locations:

| Value | Description |
|-------|-------------|
| **Sidebar** | Rendered via the `frontend_product.block_aux` hook (product page sidebar) |
| **Product menu area** | Rendered via the `frontend_product.menu` hook |
| **Disabled** | Counter is not rendered automatically; use the helper to place it manually in your template |

## Manual output

If the default display is disabled, add the helper call to your theme template.

For Shop-Script 8.17 and later:

```smarty
{$wa->shop->viewdPlugin->views($product)}
```

For older versions of Shop-Script:

```smarty
{shopViewdPluginViewHelper::productViews($product)}
```

## Template customization

To change the counter markup, add `plugin.viewd.product.html` to your theme folder. Available variables:

| Variable       | Description                      |
|----------------|----------------------------------|
| `$total_views` | View count (integer)             |
| `$product`     | Product object or array          |

## Requirements

- PHP ≥ 7.4
- Shop-Script (any current version)
- Webasyst Installer ≥ 3.0

## License

Webasyst license. See [LICENSE](LICENSE) for details.
