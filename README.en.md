# Product View Counter — plugin for Shop-Script

[Русская версия](README.md)

A plugin that counts product page views and displays the counter in both the Shop-Script backend and the storefront.

## How it works

A view is recorded via an AJAX request sent from the product page after it finishes loading. This avoids false counts from server-side rendering. Views are not counted when the visitor has JavaScript or cookies disabled. Authenticated backend users are excluded from the count.

> The displayed value represents **previous** views; the current visit will be counted on the next page load.

## Features

- View counter in the Shop-Script backend — on the product editor page, "Prices, Availability, Publication" tab
- Optional frontend display of the counter in the product page sidebar
- Template override support from within a storefront theme

## Settings

The plugin has one setting — **"Default display on product page"**.

When enabled, the counter is rendered via the `frontend_product.block_aux` hook (product page sidebar). Disable it if you want to place the counter somewhere else in the template manually.

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
