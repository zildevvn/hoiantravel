<?php

/**
 * Hooks.
 */

function imageTagForJs($response, $attachment)
{
	foreach ($response['sizes'] as $size => $datas) {
		$response['sizes'][$size]['tag'] = wp_get_attachment_image($attachment->ID, $size);
		$response['sizes'][$size]['srcset'] = wp_get_attachment_image_srcset($attachment->ID, $size);
	}
	return $response;
}
add_filter('wp_prepare_attachment_for_js', 'imageTagForJs', 10, 2);


/**
 * Allow upload json file
 */
add_filter('upload_mimes', function ($mime_types) {
	$mime_types['json'] = 'application/json'; // Adding .json extension
	$mime_types['svg'] = 'image/svg+xml';
	$mime_types['svgz'] = 'image/svg+xml';
	$mime_types['ttf'] = 'application/x-font-ttf';
	$mime_types['otf'] = 'application/x-font-opentype';
	$mime_types['woff'] = 'application/font-woff';
	$mime_types['woff2'] = 'application/font-woff2';
	return $mime_types;
}, 1);

/**
 * Header template
 * @return void
 */
add_action('ha_hook_header', 'ha_header_template');
function ha_header_template()
{
	load_template(get_template_directory() . '/template-parts/header.php', false);
}

/**
 * Footer template
 * @return void
 */
add_action('ha_hook_footer', 'ha_footer_template');
function ha_footer_template()
{
	load_template(get_template_directory() . '/template-parts/footer.php', false);
}

/**
 * Modal search template
 * @return void
 */
add_action('ha_hook_modal_search', 'ha_modal_search_template');
add_action('ha_hook_search', 'ha_modal_search_template');
function ha_modal_search_template()
{
	load_template(get_template_directory() . '/template-parts/modal-search.php', false);
}


/**
 * Post loop item template
 *
 * @param Int $post_id
 *
 * @return void
 */
add_action('ha_hook_post_loop_item', 'ha_post_loop_item_template', 20, 2);
function ha_post_loop_item_template($post_id, $index)
{
	set_query_var('post_id', $post_id);
	?>
	<article <?php post_class('col-md-4') ?>>
		<?php ha_post_item() ?>
	</article>
	<?php
}

/**
 * Force comments to be open for standard posts, tours, and cars.
 */
add_filter('comments_open', function ($open, $post_id) {
	if (in_array(get_post_type($post_id), ['post', 'tours', 'cars'])) {
		return true;
	}
	return $open;
}, 20, 2);

/**
 * Save review rating as comment meta after a comment is posted.
 */
add_action('comment_post', function ($comment_id, $comment_approved) {
	if (isset($_POST['ha_tour_rating'])) {
		$rating = intval($_POST['ha_tour_rating']);
		if ($rating >= 1 && $rating <= 5) {
			add_comment_meta($comment_id, 'rating', $rating, true);
		}
	}

	$categories = ['services', 'driver', 'experiences'];
	foreach ($categories as $cat) {
		$field = 'ha_tour_rating_' . $cat;
		if (isset($_POST[$field])) {
			$cat_rating = intval($_POST[$field]);
			if ($cat_rating >= 1 && $cat_rating <= 5) {
				add_comment_meta($comment_id, 'rating_' . $cat, $cat_rating, true);
			}
		}
	}
}, 10, 2);

/**
 * Validate that a rating is submitted for tour or car review comments.
 */
add_filter('preprocess_comment', function ($commentdata) {
	$post_id = isset($commentdata['comment_post_ID']) ? intval($commentdata['comment_post_ID']) : 0;
	if ($post_id && in_array(get_post_type($post_id), ['tours', 'cars'])) {

		// For tours, validate categories first and auto-calculate overall rating if missing
		if (get_post_type($post_id) === 'tours') {
			$categories = [
				'services' => __('Services', 'ha'),
				'driver' => __('Driver', 'ha'),
				'experiences' => __('Experiences', 'ha')
			];
			$sum = 0;
			foreach ($categories as $cat => $label) {
				$field = 'ha_tour_rating_' . $cat;
				if (empty($_POST[$field]) || intval($_POST[$field]) < 1) {
					wp_die(sprintf(__('Please select a star rating for %s before submitting your review.'), $label), __('Missing Rating'), ['back_link' => true, 'response' => 400]);
				}
				$sum += intval($_POST[$field]);
			}
			// Automatically calculate overall rating from categories if not provided directly
			if (empty($_POST['ha_tour_rating'])) {
				$_POST['ha_tour_rating'] = round($sum / count($categories));
			}
		}

		if (empty($_POST['ha_tour_rating']) || intval($_POST['ha_tour_rating']) < 1) {
			wp_die(__('Please select a star rating before submitting your review.'), __('Missing Rating'), ['back_link' => true, 'response' => 400]);
		}
	}
	return $commentdata;
});

/**
 * Handle redirect after tour or car review submission.
 */
add_filter('comment_post_redirect', function ($location, $comment) {
	$post_type = get_post_type($comment->comment_post_ID);
	if (in_array($post_type, ['tours', 'cars'])) {
		$location = remove_query_arg(['unapproved', 'approved', 'moderation-hash'], $location);
		// Strip any hash anchor first
		$parts = explode('#', $location);
		$base_url = $parts[0];

		if ($comment->comment_approved == '1') {
			$base_url = add_query_arg('approved', '1', $base_url);
		} else {
			$base_url = add_query_arg([
				'unapproved' => $comment->comment_ID,
				'moderation-hash' => wp_hash($comment->comment_date_gmt)
			], $base_url);
		}

		$anchor = ($post_type === 'cars') ? 'car-review' : 'tour-review';
		$location = $base_url . '#' . $anchor;
	}
	return $location;
}, 10, 2);



add_filter('get_post_metadata', 'override_kk_star_ratings_meta', 10, 4);
function override_kk_star_ratings_meta($value, $object_id, $meta_key, $single)
{
	// Các meta keys mà KK Star Ratings sử dụng để lấy dữ liệu
	$kksr_keys = ['_kksr_casts', '_kksr_avg', '_kksr_ratings'];

	if (in_array($meta_key, $kksr_keys)) {

		// Bê nguyên logic tạo số ảo cố định theo ID bài viết của bạn vào đây
		$count = 500 + (abs(crc32((string) $object_id)) % 501);
		$rating_values = [4.8, 4.9, 5.0];
		$rating_index = abs(crc32('rating-' . $object_id)) % count($rating_values);
		$rating = $rating_values[$rating_index];

		$override_value = '';

		// Trả về dữ liệu tương ứng với từng meta key
		if ($meta_key === '_kksr_casts') {
			$override_value = $count;
		} elseif ($meta_key === '_kksr_avg') {
			$override_value = $rating;
		} elseif ($meta_key === '_kksr_ratings') {
			$override_value = $count * $rating;
		}

		// Đảm bảo trả về đúng format dữ liệu mà WordPress yêu cầu
		return $single ? $override_value : [$override_value];
	}

	// Quan trọng: Trả về null để WordPress tiếp tục lấy dữ liệu từ DB cho các post meta khác
	return null;
}