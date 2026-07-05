# WC Sort by Stock

![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-21759B?logo=wordpress&logoColor=white)
![WooCommerce](https://img.shields.io/badge/WooCommerce-96588A?logo=woocommerce&logoColor=white)
![OpenSpec](https://img.shields.io/badge/OpenSpec-enforced-blueviolet)
![License: GPL v2+](https://img.shields.io/badge/License-GPLv2%2B-blue.svg)

> WooCommerce plugin that orders the product catalog so in-stock products
> are shown first and out-of-stock products are pushed to the end of the
> list.

---

## Quick start

```bash
# 1. Copy (or symlink) this plugin into your WordPress install
cp -r wc_sort_by_stock /path/to/wordpress/wp-content/plugins/wc-sort-by-stock

# 2. Activate it
wp plugin activate wc-sort-by-stock
# or: WP Admin → Plugins → activate "WC Sort by Stock"
```

Requires WordPress, WooCommerce 4.0+, and PHP 7.4+.

---

## How it works

The plugin hooks into `woocommerce_product_query`, which WooCommerce runs
for the shop page, product category/tag archives, and product
shortcodes/widgets. It adds a `LEFT JOIN` on the `_stock_status` product
meta and sorts by it (`instock` → `onbackorder` → `outofstock`) as the
primary sort, while preserving whatever ordering was already selected
(price, popularity, date, menu order, etc.) as the secondary sort.

---

## Settings

Go to **WooCommerce → Settings → Stock Sorting** to enable or disable the
feature. It is enabled by default.

---

## Contributing

This project uses **OpenSpec** for spec-driven development — every
feature or bugfix has a spec file under `.openspec/specs/`.

```bash
scripts/openspec scaffold "<name>" [--type bugfix]   # create a spec
scripts/openspec check                                # validate spec coverage
```

In Claude Code, use `/openspec-scaffold`, `/openspec-implement`, and
`/openspec-check` instead. See `.openspec/onboarding.yaml` to (re)run the
setup interview.

---

## License

[GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html)

## Author

Eduardo Arana
