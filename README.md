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

## Requirements

- WordPress
- WooCommerce 4.0+
- PHP 7.4+

## Author

Eduardo Arana
