<?php
/**
 * Plugin Name:       WC Sort by Stock
 * Description:       Orders the WooCommerce product catalog so in-stock products are shown first and out-of-stock products are pushed to the end.
 * Version:           1.0.0
 * Author:            Eduardo Arana
 * Author URI:        https://arananet.net/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-sort-by-stock
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * WC requires at least: 4.0
 */

defined( 'ABSPATH' ) || exit;

final class WC_Sort_By_Stock {

	const OPTION_ENABLED = 'wcsbs_enabled';

	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'maybe_show_missing_woocommerce_notice' ) );
		add_action( 'woocommerce_product_query', array( __CLASS__, 'flag_query' ), 20 );
		add_filter( 'posts_clauses', array( __CLASS__, 'sort_by_stock_clauses' ), 20, 2 );

		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_wcsbs', array( __CLASS__, 'render_settings_tab' ) );
		add_action( 'woocommerce_update_options_wcsbs', array( __CLASS__, 'save_settings' ) );
	}

	public static function maybe_show_missing_woocommerce_notice() {
		if ( class_exists( 'WooCommerce' ) ) {
			return;
		}

		echo '<div class="notice notice-error"><p>' .
			esc_html__( 'WC Sort by Stock requires WooCommerce to be installed and active.', 'wc-sort-by-stock' ) .
			'</p></div>';
	}

	public static function is_enabled() {
		return 'yes' === get_option( self::OPTION_ENABLED, 'yes' );
	}

	/**
	 * Flag WooCommerce product queries so posts_clauses knows to sort them by stock.
	 * Covers shop/category/tag archives as well as product shortcodes and widgets,
	 * since they all run through WC_Query::product_query().
	 */
	public static function flag_query( $query ) {
		if ( ! self::is_enabled() ) {
			return;
		}

		$query->set( 'wcsbs_sort_by_stock', true );
	}

	public static function sort_by_stock_clauses( $clauses, $query ) {
		if ( ! $query->get( 'wcsbs_sort_by_stock' ) ) {
			return $clauses;
		}

		global $wpdb;

		if ( ! strpos( $clauses['join'], 'wcsbs_stock' ) ) {
			$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS wcsbs_stock ON ( {$wpdb->posts}.ID = wcsbs_stock.post_id AND wcsbs_stock.meta_key = '_stock_status' )";
		}

		$stock_order = "CASE wcsbs_stock.meta_value "
			. "WHEN 'instock' THEN 0 "
			. "WHEN 'onbackorder' THEN 1 "
			. "WHEN 'outofstock' THEN 2 "
			. 'ELSE 3 END';

		$clauses['orderby'] = empty( $clauses['orderby'] )
			? $stock_order
			: $stock_order . ', ' . $clauses['orderby'];

		return $clauses;
	}

	public static function add_settings_tab( $tabs ) {
		$tabs['wcsbs'] = __( 'Stock Sorting', 'wc-sort-by-stock' );
		return $tabs;
	}

	private static function get_settings_fields() {
		return array(
			array(
				'title' => __( 'Stock Sorting', 'wc-sort-by-stock' ),
				'type'  => 'title',
				'id'    => 'wcsbs_section_title',
			),
			array(
				'title'   => __( 'Enable sorting', 'wc-sort-by-stock' ),
				'desc'    => __( 'Show in-stock products first and move out-of-stock products to the end of the catalog', 'wc-sort-by-stock' ),
				'id'      => self::OPTION_ENABLED,
				'default' => 'yes',
				'type'    => 'checkbox',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'wcsbs_section_end',
			),
		);
	}

	public static function render_settings_tab() {
		woocommerce_admin_fields( self::get_settings_fields() );
	}

	public static function save_settings() {
		woocommerce_update_options( self::get_settings_fields() );
	}
}

WC_Sort_By_Stock::init();
