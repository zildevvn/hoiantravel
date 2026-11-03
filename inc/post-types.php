<?php

/**
 * Use this file to register any custom post types you wish to create.
 */
if (!function_exists('vm_create_custom_post_type')) {
	// Register Custom Post Type
	function vm_create_custom_post_type()
	{
		register_post_type('tours', array(
			'labels' => array(
				'name' => __('Tours'),
				'singular_name' => __('Tour'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Tour'),
				'edit_item' => __('Edit Tour'),
				'new_item' => __('New Tour'),
				'view_item' => __('View Tour'),
				'search_items' => __('Search Tours'),
				'not_found' => __('No tours found'),
				'not_found_in_trash' => __('No tours found in trash'),
				'all_items' => __('All Tours'),
				'menu_name' => __('Tours'),
			),
			'label' => __('Tours', 'vm'),

			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'page-attributes',
				'comments'
			),

			'menu_icon' => 'dashicons-admin-generic',
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => false,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_in_rest' => true,
			'rewrite' => array('slug' => 'phu-quoc-tour'),
		));


		// register_post_type('cars', array(
		// 	'labels' => array(
		// 		'name' => __('Cars'),
		// 		'singular_name' => __('Car'),
		// 		'add_new' => __('Add New'),
		// 		'add_new_item' => __('Add New Car'),
		// 		'edit_item' => __('Edit Car'),
		// 		'new_item' => __('New Car'),
		// 		'view_item' => __('View Car'),
		// 		'search_items' => __('Search Cars'),
		// 		'not_found' => __('No cars found'),
		// 		'not_found_in_trash' => __('No cars found in trash'),
		// 		'all_items' => __('All Cars'),
		// 		'menu_name' => __('Cars'),
		// 	),
		// 	'label' => __('Cars', 'vm'),
		// 	'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'page-attributes', 'comments'),
		// 	'menu_icon' => 'dashicons-car',
		// 	'hierarchical' => false,
		// 	'public' => true,
		// 	'show_ui' => true,
		// 	'show_in_menu' => true,
		// 	'menu_position' => 5,
		// 	'show_in_admin_bar' => true,
		// 	'show_in_nav_menus' => true,
		// 	'can_export' => true,
		// 	'has_archive' => false,
		// 	'exclude_from_search' => false,
		// 	'publicly_queryable' => true,
		// 	'show_in_rest' => true,
		// 	'rewrite' => array('slug' => 'hue-car-rental'),
		// ));
		register_post_type('tour_booking', array(
			'labels' => array(
				'name' => __('Bookings'),
				'singular_name' => __('Booking'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Booking'),
				'edit_item' => __('Edit Booking'),
				'new_item' => __('New Booking'),
				'view_item' => __('View Booking'),
				'search_items' => __('Search Bookings'),
				'not_found' => __('No bookings found'),
				'not_found_in_trash' => __('No bookings found in trash'),
				'all_items' => __('All Bookings'),
				'menu_name' => __('Bookings'),
			),
			'label' => __('Bookings', 'vm'),
			'supports' => array('title', 'custom-fields'),
			'menu_icon' => 'dashicons-book-alt',
			'hierarchical' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 6,
			'show_in_admin_bar' => false,
			'show_in_nav_menus' => false,
			'can_export' => true,
			'has_archive' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_in_rest' => false,
		));

	}

	add_action('init', 'vm_create_custom_post_type', 0);
}

if (!function_exists('vm_create_custom_taxonomy')) {
	function vm_create_custom_taxonomy()
	{
		register_taxonomy('tour_cats', array('tours'), array(
			'labels' => array(
				'name' => 'Categories',
				'singular_name' => 'Category',
				'search_items' => 'Search Categories',
				'all_items' => 'All Categories',
				'edit_item' => 'Edit Category',
				'update_item' => 'Update Category',
				'add_new_item' => 'Add New Category',
				'new_item_name' => 'New Category Name',
				'menu_name' => 'Categories',
			),
			'rewrite' => false,
			'hierarchical' => true,
			'public' => false,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'show_in_rest' => true,
		));

		register_taxonomy('tour_locations', array('tours'), array(
			'labels' => array(
				'name' => 'Locations',
				'singular_name' => 'Location',
				'search_items' => 'Search Locations',
				'all_items' => 'All Locations',
				'edit_item' => 'Edit Location',
				'update_item' => 'Update Location',
				'add_new_item' => 'Add New Location',
				'new_item_name' => 'New Location Name',
				'menu_name' => 'Locations',
			),

			'rewrite' => array(
				'slug' => 'locations',
				'with_front' => false,
				'hierarchical' => true,
			),

			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_in_rest' => true,
		));

		register_taxonomy('car_category', array('cars'), array(
			'labels' => array(
				'name' => 'Categories',
				'singular_name' => 'Category',
				'search_items' => 'Search Categories',
				'all_items' => 'All Categories',
				'edit_item' => 'Edit Category',
				'update_item' => 'Update Category',
				'add_new_item' => 'Add New Category',
				'new_item_name' => 'New Category Name',
				'menu_name' => 'Categories',
			),
			'rewrite' => false,
			'hierarchical' => true,
			'public' => false,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'show_in_rest' => true,
		));
	}
	add_action('init', 'vm_create_custom_taxonomy', 0);
}