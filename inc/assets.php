<?php

add_action('wp_enqueue_scripts', function () {

	if (is_page_template('tours-template.php')) {
		wp_enqueue_style('vm-nouislider', get_template_directory_uri() . '/assets/lib/no-ui-slider/nouislider.min.css', array(), '15.7.1');
		wp_enqueue_script('vm-nouislider', get_template_directory_uri() . '/assets/lib/no-ui-slider/nouislider.min.js', array(), '15.7.1', true);
	}

	if (is_singular('tours')) {
		wp_enqueue_style('fancybox-css', get_template_directory_uri() . '/assets/lib/fancybox/fancybox.css', array(), '5.0');
		wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/assets/lib/fancybox/fancybox.umd.js', array(), '5.0', true);
	}
	$style_path = get_template_directory() . '/dist/css/style.css';
	$script_path = get_template_directory() . '/dist/js/main.bundle.js';

	wp_enqueue_style(
		'theme-styles',
		get_template_directory_uri() . '/dist/css/style.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : null
	);
	wp_enqueue_script(
		'theme-scripts',
		get_template_directory_uri() . '/dist/js/main.bundle.js',
		array('jquery'),
		file_exists($script_path) ? filemtime($script_path) : null,
		true
	);

	wp_localize_script('theme-scripts', 'php_data', [
		'admin_logged' => in_array('administrator', wp_get_current_user()->roles) ? 'yes' : 'no',
		'ajax_url' => admin_url('admin-ajax.php'),
		'site_url' => site_url(),
		'rest_url' => get_rest_url(),
	]);

	wp_localize_script('theme-scripts', 'themeData', [
		'templateUrl' => get_template_directory_uri()
	]);
});

