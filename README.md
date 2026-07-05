# WC Sort by Stock

A simple WooCommerce plugin that orders the product catalog so in-stock
products are displayed first and out-of-stock products are pushed to the
end of the list.

## How it works

The plugin hooks into `woocommerce_product_query`, which WooCommerce runs
for the shop page, product category/tag archives, and product
shortcodes/widgets. It adds a `LEFT JOIN` on the `_stock_status` product
meta and sorts by it (`instock` → `onbackorder` → `outofstock`) as the
primary sort, while preserving whatever ordering was already selected
(price, popularity, date, menu order, etc.) as the secondary sort.

## Settings

Go to **WooCommerce → Settings → Stock Sorting** to enable or disable the
feature. It is enabled by default.

## OpenSpec

This repo uses [OpenSpec](https://github.com/arananet/openspec-template) for
spec-driven development. Every feature or bugfix has a spec file under
`.openspec/specs/`.

```bash
scripts/openspec scaffold "<name>" [--type bugfix]   # create a spec
scripts/openspec check                                # validate spec coverage
```

In Claude Code, use `/openspec-scaffold`, `/openspec-implement`, and
`/openspec-check` instead. See `.openspec/onboarding.yaml` to (re)run the
setup interview.

## Requirements

- WordPress
- WooCommerce 4.0+
- PHP 7.4+

## Author

Eduardo Arana
