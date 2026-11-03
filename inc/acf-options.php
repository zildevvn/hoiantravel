<?php
add_action('acf/init', 'ha_acf_init');
function ha_acf_init()
{
	if (function_exists('acf_add_options_page')) {
		if (current_user_can('administrator')):
			acf_add_options_page(array(
				'page_title' => __('Theme Options', 'ha'),
				'menu_title' => __('Theme Options', 'ha'),
				'menu_slug' => 'theme-options',
			));

			// Add child page under the main options page
			acf_add_options_sub_page(array(
				'page_title' => 'General Settings',
				'menu_title' => 'General',
				'parent_slug' => 'theme-options',
			));

			// Add child page under the main options page
			acf_add_options_sub_page(array(
				'page_title' => 'Header Settings',
				'menu_title' => 'Header',
				'parent_slug' => 'theme-options',
			));

			// Add another child page
			acf_add_options_sub_page(array(
				'page_title' => 'Footer Settings',
				'menu_title' => 'Footer',
				'parent_slug' => 'theme-options',
			));
		endif;
	}
}


add_filter('acf/settings/save_json', 'ha_acf_json_save_point');
function ha_acf_json_save_point($path)
{
	// update path
	$path = get_stylesheet_directory() . '/inc/acf-options';

	// return
	return $path;
}

add_filter('acf/settings/load_json', 'ha_acf_json_load_point');
function ha_acf_json_load_point($paths)
{
	// remove original path (optional)
	unset($paths[0]);
	// append path
	$paths[] = get_stylesheet_directory() . '/inc/acf-options';

	// return
	return $paths;
}

function ha_acf_google_map_api($api)
{
	$api_key = get_field('google_map_api_key', 'option');
	if ($api_key) {
		$api['key'] = $api_key;
	}
	return $api;
}
add_filter('acf/fields/google_map/api', 'ha_acf_google_map_api');
