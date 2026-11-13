<?php

/**
 * Helpers
 */

function dump($data)
{
	print "<pre style=' background: rgba(0, 0, 0, 0.1); margin-bottom: 1.618em; padding: 1.618em; overflow: auto; max-width: 100%; '>==========================\n";
	if (is_array($data)) {
		print_r($data);
	} elseif (is_object($data)) {
		var_dump($data);
	} else {
		var_dump($data);
	}
	print "===========================</pre>";
}


if (!function_exists('ha_svg_icon')) {

	/**
	 * @param $icon
	 *
	 * @return mixed|string
	 */
	function ha_svg_icon($icon)
	{
		$icons = require(__DIR__ . '/svg.php');
		return isset($icons[$icon]) ? $icons[$icon] : '';
	}
}

if (!function_exists('ha_get_currency')) {

	/**
	 * Get the global currency symbol configured in Theme Options.
	 * Matches the Home Page source of truth: get_field('currency', 'option').
	 * Defaults to '₫' if unconfigured.
	 *
	 * @return string
	 */
	function ha_get_currency()
	{
		$currency = function_exists('get_field') ? (string) get_field('currency', 'option') : '';
		return $currency !== '' ? $currency : '₫';
	}
}

if (!function_exists('ha_format_price')) {

	/**
	 * Format a price value with an optional currency symbol.
	 *
	 * - Accepts raw numbers ("52", 52, "1200000") or pre-formatted strings ("$52.00", "₫ 1,200,000").
	 * - Strips any currency symbol already present in $price to avoid duplicates.
	 * - If $currency is empty, automatically falls back to the global option from ha_get_currency().
	 * - Whole numbers (e.g. 50, 100, 1000, "50.00") never display redundant trailing decimals (.00).
	 * - Real fractional values (e.g. 50.5, 50.25) preserve their decimal digits without trailing zeros.
	 * - Thousands separators (,) are applied consistently across all numeric values.
	 * - 0 is treated as a valid price.
	 *
	 * Returns a plain (unescaped) string; escape on output, e.g. esc_html( ha_format_price( $price, $currency ) ).
	 *
	 * @param mixed       $price    Raw price value.
	 * @param mixed       $currency Currency symbol (e.g. "₫", "$", "€"). If empty, uses global option.
	 * @param string|null $fallback Text returned when price is empty/invalid. Default "Contact".
	 *
	 * @return string
	 */
	function ha_format_price($price, $currency = '', $fallback = null)
	{
		$fallback = $fallback === null ? (function_exists('__') ? __('Contact', 'ha') : 'Contact') : (string) $fallback;

		if (!is_scalar($price) || is_bool($price)) {
			return $fallback;
		}

		$raw = trim((string) $price);
		$currency = is_scalar($currency) ? trim((string) $currency) : '';

		if ($raw === '') {
			return $fallback;
		}

		// Detect a symbol already present in the price when no currency is passed.
		if ($currency === '' && preg_match('/^\s*([\$₫€])|([\$₫€])\s*$/u', $raw, $m)) {
			$currency = !empty($m[1]) ? $m[1] : $m[2];
		}

		// Fall back to the global currency configuration from Home Page
		if ($currency === '') {
			$currency = ha_get_currency();
		}

		// Remove known symbols and the given currency from both ends.
		$symbols = '\$₫€';
		if ($currency !== '') {
			$symbols .= preg_quote($currency, '/');
		}
		$clean = trim(preg_replace('/^[' . $symbols . '\s]+|[' . $symbols . '\s]+$/u', '', $raw));

		if ($clean === '') {
			return $fallback;
		}

		$number = str_replace(',', '', $clean);
		if (is_numeric($number)) {
			$float = (float) $number;

			// If the value has no fractional part (integer or zero decimals, e.g. 50, 100, "50.00", 1000), format with 0 decimals.
			if (abs($float - round($float)) < 0.0000001) {
				return $currency . number_format(round($float), 0, '.', ',');
			}

			// Value has real decimal places (e.g. 50.5, 50.25).
			// Preserve decimal precision (up to 2 by default, or up to 4 if specified in string),
			// and remove any redundant trailing zeros or decimal points.
			$decimals = 2;
			if (strpos($number, '.') !== false) {
				$parts = explode('.', $number);
				$dec_str = rtrim($parts[1], '0');
				$decimals = max(1, min(4, strlen($dec_str)));
			}
			$formatted = number_format($float, $decimals, '.', ',');
			if (strpos($formatted, '.') !== false) {
				$formatted = rtrim(rtrim($formatted, '0'), '.');
			}
			return $currency . $formatted;
		}

		// Non-numeric value (e.g. "50-80", "On request"): keep text, prefix currency only if it starts with a digit.
		return preg_match('/^\d/', $clean) ? $currency . $clean : $clean;
	}
}

if (!function_exists('ha_calculate_tour_price')) {

	/**
	 * Calculate tour option pricing breakdown based on passenger count and tour type.
	 * Preserves 100% of the pricing rules for group, private, and car tours.
	 *
	 * @param array $option
	 * @param int   $adults
	 * @param int   $children
	 * @param int   $tour_id
	 * @return array
	 */
	function ha_calculate_tour_price($option, $adults = 2, $children = 0, $tour_id = 0)
	{
		$adults = max(0, intval($adults));
		$children = max(0, intval($children));
		$total_pax = $adults + $children;
		if ($total_pax < 1) {
			$total_pax = 1;
			$adults = 1;
		}

		$is_car_tour = $tour_id ? has_term('Car Tours', 'tour_cats', $tour_id) : false;
		$private_tour = !empty($option['private_tour']);
		$price_group = !empty($option['price_group']) ? floatval(str_replace(',', '', (string) $option['price_group'])) : 0;
		$child_price_group = !empty($option['child_price_group']) ? floatval(str_replace(',', '', (string) $option['child_price_group'])) : 0;
		$price_private = !empty($option['price_private']) && is_array($option['price_private']) ? $option['price_private'] : [];

		$total_price = 0;
		$price_per_person = 0;
		$child_price = 0;
		$is_price_available = true;

		if ($is_car_tour) {
			$price_per_person = $price_group;
			$total_price = $price_group;
			$is_price_available = ($total_price > 0);
		} elseif ($private_tour) {
			$key = ($total_pax === 1) ? '1_person' : ($total_pax . '_persons');
			if (isset($price_private[$key]) && is_numeric(str_replace(',', '', (string) $price_private[$key]))) {
				$total_price = floatval(str_replace(',', '', (string) $price_private[$key]));
				$price_per_person = $total_pax > 0 ? ($total_price / $total_pax) : $total_price;
			} elseif (!empty($price_private)) {
				$valid_prices = array_filter(array_map(function ($v) {
					$clean = str_replace(',', '', (string) $v);
					return is_numeric($clean) ? floatval($clean) : 0;
				}, $price_private));
				if (!empty($valid_prices)) {
					$total_price = end($valid_prices);
					$price_per_person = $total_pax > 0 ? ($total_price / $total_pax) : $total_price;
				} else {
					$is_price_available = false;
				}
			} else {
				$is_price_available = false;
			}
		} else {
			// Group Tour
			$price_per_person = $price_group;
			$child_price = $child_price_group;
			$total_price = ($adults * $price_group) + ($children * $child_price_group);
			$is_price_available = ($price_group > 0 || $total_price > 0);
		}

		return [
			'is_car_tour' => $is_car_tour,
			'is_private_tour' => $private_tour,
			'price_per_person' => $price_per_person,
			'child_price' => $child_price,
			'total_price' => $total_price,
			'is_price_available' => $is_price_available,
		];
	}
}

if (!function_exists('vm_calculate_tour_price')) {
	function vm_calculate_tour_price($option, $adults = 2, $children = 0, $tour_id = 0)
	{
		return ha_calculate_tour_price($option, $adults, $children, $tour_id);
	}
}

if (!function_exists('ha_pagination')) {

	/**
	 * Render or return reusable global pagination for WordPress queries.
	 *
	 * Flexible API supports:
	 * 1. ha_pagination() - uses main global $wp_query
	 * 2. ha_pagination( $wp_query_instance ) - uses a custom WP_Query instance directly
	 * 3. ha_pagination( $current_page, $total_pages, $query_args ) - explicit page numbers
	 * 4. ha_pagination( [ 'query' => $q, 'current' => 1, 'total' => 5, 'echo' => false ] ) - options array
	 *
	 * @param WP_Query|int|array|null $query_or_paged Custom WP_Query, current page integer, or options array.
	 * @param int|array|null          $total_pages    Total pages count, or query_args array if first param is WP_Query.
	 * @param array                   $query_args     Extra query parameters to append to pagination links.
	 * @param bool                    $echo           Whether to echo or return the markup. Default true.
	 * @return string|void
	 */
	function ha_pagination($query_or_paged = null, $total_pages = null, $query_args = [], $echo = true)
	{
		global $wp_query, $wp_rewrite;

		$current_page = 1;
		$max_pages = 1;

		// Option A: Options array passed as single argument
		if (is_array($query_or_paged) && !isset($query_or_paged[0])) {
			$opts = $query_or_paged;
			$target_query = isset($opts['query']) && ($opts['query'] instanceof WP_Query) ? $opts['query'] : $wp_query;
			$current_page = isset($opts['current']) ? intval($opts['current']) : 0;
			$max_pages = isset($opts['total']) ? intval($opts['total']) : intval($target_query->max_num_pages ?? 1);
			$query_args = isset($opts['query_args']) ? (array) $opts['query_args'] : [];
			$echo = isset($opts['echo']) ? (bool) $opts['echo'] : true;

			if ($current_page < 1) {
				$current_page = max(1, intval($target_query->get('paged') ?: ($target_query->get('page') ?: (get_query_var('paged') ?: (get_query_var('page') ?: 1)))));
			}
		}
		// Option B: WP_Query instance passed directly
		elseif ($query_or_paged instanceof WP_Query) {
			$max_pages = intval($query_or_paged->max_num_pages);
			$current_page = max(1, intval($query_or_paged->get('paged') ?: ($query_or_paged->get('page') ?: (get_query_var('paged') ?: (get_query_var('page') ?: 1)))));

			// If 2nd parameter is an array, treat it as query_args
			if (is_array($total_pages)) {
				$query_args = $total_pages;
			} elseif (is_bool($total_pages)) {
				$echo = $total_pages;
			}
		}
		// Option C: Explicit page numbers (e.g. $paged, $query->max_num_pages)
		elseif (is_numeric($query_or_paged)) {
			$current_page = max(1, intval($query_or_paged));
			$max_pages = $total_pages !== null ? intval($total_pages) : intval($wp_query->max_num_pages ?? 1);
		}
		// Option D: Default - use main global $wp_query
		else {
			$current_page = max(1, intval(get_query_var('paged') ?: (get_query_var('page') ?: 1)));
			$max_pages = intval($wp_query->max_num_pages ?? 1);
		}

		// Do not render pagination if 1 or 0 pages exist
		if ($max_pages < 2) {
			return $echo ? null : '';
		}

		// Determine base URL (handles AJAX requests gracefully)
		if (function_exists('wp_doing_ajax') && wp_doing_ajax() && wp_get_referer()) {
			$pagenum_link = html_entity_decode(wp_get_referer());
		} else {
			$pagenum_link = html_entity_decode(get_pagenum_link(1, false));
		}

		$url_parts = explode('?', $pagenum_link);
		$existing_args = [];
		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $existing_args);
			unset($existing_args['paged'], $existing_args['page']);
		}

		$merged_args = array_filter(array_merge($existing_args, (array) $query_args), function ($val) {
			return $val !== '' && $val !== null;
		});
		unset($merged_args['paged'], $merged_args['page']);

		$args_to_remove = array_merge(array_keys($existing_args), ['paged', 'page']);
		$pagenum_link = remove_query_arg($args_to_remove, $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$is_singular_or_page = is_singular() || is_page();
		$format = ($wp_rewrite && $wp_rewrite->using_permalinks() && !$is_singular_or_page) ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

		// Accessible previous & next buttons with theme SVGs
		$prev_icon = function_exists('ha_svg_icon') ? ha_svg_icon('arrow_prev') : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
		$next_icon = function_exists('ha_svg_icon') ? ha_svg_icon('arrow_next') : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';

		$prev_text = '<span class="ha-pagination__icon" aria-hidden="true">' . $prev_icon . '</span><span class="visually-hidden">' . esc_html__('Previous', 'ha') . '</span>';
		$next_text = '<span class="ha-pagination__icon" aria-hidden="true">' . $next_icon . '</span><span class="visually-hidden">' . esc_html__('Next', 'ha') . '</span>';

		$links = paginate_links([
			'base' => $pagenum_link,
			'format' => $format,
			'current' => $current_page,
			'total' => $max_pages,
			'type' => 'list',
			'prev_text' => $prev_text,
			'next_text' => $next_text,
			'add_args' => $merged_args,
			'end_size' => 1,
			'mid_size' => 2,
		]);

		if (!$links) {
			return $echo ? null : '';
		}

		$output = '<nav class="ha-pagination" aria-label="' . esc_attr__('Pagination Navigation', 'ha') . '">' . $links . '</nav>';

		if ($echo) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if (!function_exists('vm_pagination')) {
	function vm_pagination($query_or_paged = null, $total_pages = null, $query_args = [], $echo = true)
	{
		return ha_pagination($query_or_paged, $total_pages, $query_args, $echo);
	}
}


// Helper function to safely extract image data
if (!function_exists('ha_extract_gallery_image_data')) {
	function ha_extract_gallery_image_data($image)
	{
		$data = [
			'url' => '',
			'thumb' => '',
			'alt' => get_the_title() . ' - Gallery',
			'title' => ''
		];

		if (is_array($image)) {
			$data['url'] = !empty($image['url']) ? $image['url'] : '';
			$data['thumb'] = isset($image['sizes']['large']) ? $image['sizes']['large'] : $data['url'];
			if (!empty($image['alt'])) {
				$data['alt'] = $image['alt'];
			} elseif (!empty($image['title'])) {
				$data['alt'] = $image['title'];
			}
			$data['title'] = !empty($image['title']) ? $image['title'] : '';
		} elseif (is_numeric($image)) {
			$data['url'] = wp_get_attachment_url($image);
			$img_src_large = wp_get_attachment_image_src($image, 'large');
			$data['thumb'] = $img_src_large ? $img_src_large[0] : $data['url'];
			$alt = get_post_meta($image, '_wp_attachment_image_alt', true);
			if (!empty($alt)) {
				$data['alt'] = $alt;
			} else {
				$data['alt'] = get_the_title($image);
			}
			$data['title'] = get_the_title($image);
		} elseif (is_string($image)) {
			$data['url'] = $image;
			$data['thumb'] = $image;
		}

		return $data;
	}
}
