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

if (!function_exists('ha_format_price')) {

	/**
	 * Format a price value with an optional currency symbol.
	 *
	 * - Accepts raw numbers ("52", 52, "1200000") or pre-formatted strings ("$52.00", "₫ 1,200,000").
	 * - Strips any currency symbol already present in $price to avoid duplicates.
	 * - If $currency is empty, a symbol found in $price is reused.
	 * - "₫" is formatted with 0 decimals, other currencies with 2 decimals.
	 * - 0 is treated as a valid price.
	 *
	 * Returns a plain (unescaped) string; escape on output, e.g. esc_html( ha_format_price( $price, $currency ) ).
	 *
	 * @param mixed       $price    Raw price value.
	 * @param mixed       $currency Currency symbol (e.g. "₫", "$", "€").
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
			$decimals = ($currency === '₫') ? 0 : 2;
			return $currency . number_format((float) $number, $decimals, '.', ',');
		}

		// Non-numeric value (e.g. "50-80", "On request"): keep text, prefix currency only if it starts with a digit.
		return preg_match('/^\d/', $clean) ? $currency . $clean : $clean;
	}
}

if (!function_exists('ha_pagination')) {
	function ha_pagination($current_page = null, $total_pages = null, $query_args = [])
	{
		global $wp_query, $wp_rewrite;

		$paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
		$current_page = $current_page ? max(1, intval($current_page)) : max(1, intval($paged));
		$total_pages = $total_pages ? intval($total_pages) : $wp_query->max_num_pages;

		if ($total_pages < 2) {
			return;
		}

		$pagenum_link = html_entity_decode(get_pagenum_link());
		$url_parts = explode('?', $pagenum_link);
		$existing_args = [];
		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $existing_args);
		}

		$merged_args = array_merge($existing_args, $query_args);
		$pagenum_link = remove_query_arg(array_keys($existing_args), $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

		$links = paginate_links([
			'base' => $pagenum_link,
			'format' => $format,
			'current' => $current_page,
			'total' => $total_pages,
			'type' => 'list',
			'prev_text' => ha_svg_icon('arrow_prev') ? ha_svg_icon('arrow_prev') : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
			'next_text' => ha_svg_icon('arrow_next') ? ha_svg_icon('arrow_next') : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
			'add_args' => $merged_args,
		]);

		if ($links) {
			echo '<nav class="ha-pagination" aria-label="Pagination">';
			echo $links;
			echo '</nav>';
		}
	}
}
