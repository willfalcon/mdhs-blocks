<?php
/*
  Plugin Name: MDHS Blocks
  Description: Custom blocks for MDHS
  Version: 1.0.4
  Author: Creative Distillery
  Author URI: https://creativedistillery.com
*/


/**
 * Add Theme Block Category
 */
function cdhq_add_block_category( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug' => 'cdhq',
				'title' => __('MDHS Blocks', 'mdhs'),
			)
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'cdhq_add_block_category', 10, 2);

/**
 * Register all custom blocks for theme
 * Hooked into 'init'
 */

function cdhq_register_blocks() {
	$blocks = get_blocks();

	$ver = get_plugin_data( plugin_dir_path( __FILE__ ) . 'mdhs-blocks.php' )['Version'];
	$env = wp_get_environment_type();
	$base = plugin_dir_path( __FILE__ ) . 'blocks/';
	$is_dev = $env == 'development' || $env == 'local';

	wp_register_script('luxon', 'https://cdn.jsdelivr.net/npm/luxon@3.4.3/build/global/luxon.min.js', array(), null, true);
	wp_register_style('tabulator-style', 'https://unpkg.com/tabulator-tables/dist/css/tabulator_materialize.min.css');

	foreach ($blocks as $block) {
		$block_base = $base . $block;
		
		$dist_base = plugin_dir_url( __FILE__ ) . 'dist/' . $block . '/' . $block;
		
		if (file_exists($block_base . '/block.json')) {
			register_block_type($block_base);
			if (file_exists($block_base . '/' . $block . '.scss')) {
				wp_register_style($block . '-style', $is_dev ? $dist_base . '.css' : $dist_base . '.min.css', array(), $ver);
			}
			if (file_exists($block_base . '/' . $block . '-editor.scss')) {
				wp_register_style($block . '-editor-style', $is_dev ? $dist_base . '-editor.css' : $dist_base . '-editor.min.css', array(), $ver);
			}
			if (file_exists($block_base . '/' . $block . '.js')) {
				wp_register_script($block . '-script', $is_dev ? $dist_base . '.js' : $dist_base . '.min.js', array(), $ver, true);
			}
			if (file_exists($block_base . '/' . $block . '-editor.js')) {
				wp_register_script($block . '-editor-script', $is_dev ? $dist_base . '-editor.js' : $dist_base . '-editor.min.js', array(), $ver, true);
			}
		}
	}
}

function get_blocks() {
	$theme = wp_get_theme();
	$blocks = get_option('cdhq_blocks');
	$version = get_option('cdhq_blocks_version');
	if (empty($blocks) || version_compare($theme->get('Version'), $version) || (function_exists( 'wp_get_environment_type' ) && 'production' !== wp_get_environment_type())) {
		$blocks = scandir(plugin_dir_path( __FILE__ ) . 'blocks/' );
		$blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store', '_base-block', '_block-import.scss', 'counties.js', 'utils.js')));
		
		update_option('cdhq_blocks', $blocks);
		update_option('cdhq_blocks_version', $theme->get('Version'));
	}
	return $blocks;
}

add_action('init', 'cdhq_register_blocks', 5);

add_filter( 'should_load_separate_core_block_assets', '__return_true' );


include_once(plugin_dir_path( __FILE__ ) . 'inc/utils.php');
include_once(plugin_dir_path( __FILE__ ) . 'inc/smartsheet.php');
include_once(plugin_dir_path( __FILE__ ) . 'inc/sharepoint.php');
include_once(plugin_dir_path( __FILE__ ) . 'inc/api.php');