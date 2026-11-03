<?php
/**
 * Shared Review Section Component
 * Uses WordPress native comments system.
 * Rating (1-5) is stored in comment meta key: 'rating'
 */

$post_id = get_the_ID();
$post_type = get_post_type($post_id);
$reviews_per_page = 5;
$current_page = max(1, get_query_var('cpage', 1));

// Fetch approved comments
$approved_comments = get_comments([
    'post_id' => $post_id,
    'status' => 'approve',
    'type' => 'comment',
]);

$total_reviews = count($approved_comments);

// Compute average rating & breakdown
$rating_sum = 0;
$rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

foreach ($approved_comments as $rc) {
    $r = intval(get_comment_meta($rc->comment_ID, 'rating', true));
    if ($r >= 1 && $r <= 5) {
        $rating_sum += $r;
        $rating_counts[$r]++;
    }
}

$avg_rating = $total_reviews > 0 ? round($rating_sum / $total_reviews, 1) : 0;

// Fetch unapproved comment if just submitted
$unapproved_comment = null;
$unapproved_id = isset($_GET['unapproved']) ? intval($_GET['unapproved']) : 0;
if ($unapproved_id > 0) {
    $temp_comment = get_comment($unapproved_id);
    if ($temp_comment && intval($temp_comment->comment_post_ID) === $post_id && $temp_comment->comment_approved === '0') {
        // Verify moderation hash
        $expected_hash = isset($_GET['moderation-hash']) ? sanitize_text_field($_GET['moderation-hash']) : '';
        if ($expected_hash === wp_hash($temp_comment->comment_date_gmt)) {
            $unapproved_comment = $temp_comment;
        }
    }
}

// Paginated comments
$paginated = get_comments([
    'post_id' => $post_id,
    'status' => 'approve',
    'type' => 'comment',
    'number' => $reviews_per_page,
    'offset' => ($current_page - 1) * $reviews_per_page,
    'order' => 'DESC',
    'orderby' => 'comment_date',
]);

if ($unapproved_comment) {
    array_unshift($paginated, $unapproved_comment);
}

$total_pages = $total_reviews > 0 ? ceil($total_reviews / $reviews_per_page) : 1;

if (!function_exists('vm_render_stars')) {
    function vm_render_stars(float $rating, string $size = '18'): string
    {
        $out = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $fill = '#f59e0b'; // full star
            } elseif ($rating >= $i - 0.5) {
                $fill = '#f59e0b'; // simple amber representation
            } else {
                $fill = '#e5e7eb'; // empty star
            }
            $out .= '<svg width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" viewBox="0 0 24 24" fill="' . $fill . '" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>';
        }
        return $out;
    }
}

// Determine singular/plural labels and title icons
$post_type_label = ($post_type === 'cars') ? 'car' : 'tour';
$section_id = ($post_type === 'cars') ? 'car-review' : 'tour-review';
$empty_message = ($post_type === 'cars') ? 'Be the first to leave a review for this car!' : 'Be the first to leave a review for this tour!';
?>

<div id="<?php echo esc_attr($section_id); ?>" class="vm-reviews">

    <?php vm_icon_heading() ?>
    <h2 class="vm-heading vm-reviews__title">
        <?php esc_html_e('Guest Reviews', 'vm'); ?>
    </h2>

    <?php if ($total_reviews > 0 || $unapproved_comment): ?>
        <div class="vm-reviews__container">

            <!-- Left Column: Summary Info -->
            <div class="vm-reviews__summary">
                <div class="vm-reviews__score-box">
                    <span class="vm-reviews__avg-score">
                        <?php echo esc_html($avg_rating); ?>
                    </span>
                    <div class="vm-reviews__stars">
                        <?php echo vm_render_stars($avg_rating, '24'); ?>
                    </div>
                    <span class="vm-reviews__total-count">
                        <?php
                        printf(
                            _n('%s Review', '%s Reviews', $total_reviews, 'vm'),
                            number_format_i18n($total_reviews)
                        );
                        ?>
                    </span>
                </div>

                <div class="vm-reviews__breakdown">
                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <?php
                        $count = isset($rating_counts[$star]) ? $rating_counts[$star] : 0;
                        $pct = $total_reviews > 0 ? round(($count / $total_reviews) * 100) : 0;
                        ?>
                        <div class="vm-reviews__breakdown-row">
                            <span class="vm-reviews__breakdown-label">
                                <?php printf(esc_html__('%d star', 'vm'), $star); ?>
                            </span>
                            <div class="vm-reviews__progress">
                                <div class="vm-reviews__progress-bar" style="width: <?php echo esc_attr($pct); ?>%"></div>
                            </div>
                            <span class="vm-reviews__breakdown-count">
                                <?php echo esc_html($count); ?>
                            </span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Right Column: Reviews List & Pagination -->
            <div class="vm-reviews__list-wrapper">
                <div class="vm-reviews__list">
                    <?php foreach ($paginated as $review):
                        $reviewer_rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
                        $initials = strtoupper(mb_substr($review->comment_author, 0, 1));
                        ?>
                        <div class="vm-reviews__card" id="comment-<?php echo esc_attr($review->comment_ID); ?>">
                            <div class="vm-reviews__card-header">
                                <div class="vm-reviews__avatar">
                                    <?php echo esc_html($initials); ?>
                                </div>
                                <div class="vm-reviews__author-meta">
                                    <h4 class="vm-reviews__author-name">
                                        <?php echo esc_html($review->comment_author); ?>
                                    </h4>
                                    <span class="vm-reviews__date">
                                        <?php echo esc_html(date_i18n('F j, Y', strtotime($review->comment_date))); ?>
                                    </span>
                                </div>
                                <?php if ($reviewer_rating): ?>
                                    <div class="vm-reviews__card-rating">
                                        <?php echo vm_render_stars($reviewer_rating, '16'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="vm-reviews__card-content">
                                <?php echo wp_kses_post(wpautop($review->comment_content)); ?>
                            </div>
                            <?php if ($review->comment_approved == '0'): ?>
                                <div class="vm-reviews__card-pending">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    <em>
                                        <?php esc_html_e('Your review is awaiting moderation.', 'vm'); ?>
                                    </em>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="vm-reviews__pagination">
                        <?php
                        echo paginate_comments_links([
                            'base' => add_query_arg('cpage', '%#%'),
                            'format' => '',
                            'total' => $total_pages,
                            'current' => $current_page,
                            'prev_text' => '&laquo; Prev',
                            'next_text' => 'Next &raquo;',
                            'type' => 'list',
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php else: ?>
        <div class="vm-reviews__empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <p>
                <?php echo esc_html($empty_message); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Review Form -->
    <?php if (comments_open($post_id)): ?>
        <div class="vm-reviews__form-container">
            <h3 class="vm-reviews__form-title">
                <?php esc_html_e('Write a Review', 'vm'); ?>
            </h3>

            <?php if (isset($_GET['unapproved'])): ?>
                <div class="vm-reviews__notice vm-reviews__notice--pending">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <?php esc_html_e('Your review has been submitted and is awaiting moderation. Thank you!', 'vm'); ?>
                </div>
            <?php elseif (isset($_GET['approved'])): ?>
                <div class="vm-reviews__notice vm-reviews__notice--success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <?php esc_html_e('Your review has been published. Thank you!', 'vm'); ?>
                </div>
            <?php endif; ?>

            <form id="vm-review-form" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post"
                class="vm-reviews__form" novalidate>

                <!-- Star Rating Picker -->
                <div class="vm-reviews__field vm-reviews__field--rating">
                    <label class="vm-reviews__label">
                        <?php esc_html_e('Your Rating', 'vm'); ?> <span class="required">*</span>
                    </label>
                    <div class="star-picker" id="star-picker" role="radiogroup"
                        aria-label="<?php esc_attr_e('Rating', 'vm'); ?>">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                            <label class="star-picker__label" for="star-<?php echo esc_attr($s); ?>"
                                title="<?php printf(esc_attr__('%d star%s', 'vm'), $s, $s > 1 ? 's' : ''); ?>">
                                <input type="radio" class="star-picker__input" name="vm_tour_rating"
                                    id="star-<?php echo esc_attr($s); ?>" value="<?php echo esc_attr($s); ?>" required>
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            </label>
                        <?php endfor; ?>
                        <span class="star-picker__text" id="star-picker-text" aria-live="polite">
                            <?php esc_html_e('Select a rating', 'vm'); ?>
                        </span>
                    </div>
                </div>

                <?php if ($post_type === 'tours'): ?>
                <!-- Category Ratings -->
                <div class="vm-reviews__form-row vm-reviews__category-ratings">
                    <?php
                    $rating_categories = [
                        'services' => __('Services', 'vm'),
                        'driver' => __('Driver', 'vm'),
                        'experiences' => __('Experiences', 'vm'),
                    ];
                    foreach ($rating_categories as $key => $label):
                    ?>
                        <div class="vm-reviews__field vm-reviews__field--rating">
                            <label class="vm-reviews__label">
                                <?php echo esc_html($label); ?> <span class="required">*</span>
                            </label>
                            <div class="star-picker" id="star-picker-<?php echo esc_attr($key); ?>" role="radiogroup"
                                aria-label="<?php echo esc_attr($label); ?> Rating">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <label class="star-picker__label" for="star-<?php echo esc_attr($key . '-' . $s); ?>"
                                        title="<?php printf(esc_attr__('%d star%s', 'vm'), $s, $s > 1 ? 's' : ''); ?>">
                                        <input type="radio" class="star-picker__input" name="vm_tour_rating_<?php echo esc_attr($key); ?>"
                                            id="star-<?php echo esc_attr($key . '-' . $s); ?>" value="<?php echo esc_attr($s); ?>" required>
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                    </label>
                                <?php endfor; ?>
                                <span class="star-picker__text" aria-live="polite">
                                    <?php esc_html_e('Select a rating', 'vm'); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="vm-reviews__form-row">
                    <!-- Name -->
                    <div class="vm-reviews__field">
                        <label class="vm-reviews__label" for="vm-author">
                            <?php esc_html_e('Name', 'vm'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" id="vm-author" name="author" class="vm-reviews__input"
                            placeholder="<?php esc_attr_e('Your full name', 'vm'); ?>" required maxlength="245"
                            value="<?php echo esc_attr(isset($_POST['author']) ? $_POST['author'] : ''); ?>">
                    </div>
                    <!-- Email -->
                    <div class="vm-reviews__field">
                        <label class="vm-reviews__label" for="vm-email">
                            <?php esc_html_e('Email', 'vm'); ?> <span class="required">*</span>
                        </label>
                        <input type="email" id="vm-email" name="email" class="vm-reviews__input"
                            placeholder="<?php esc_attr_e('your@email.com', 'vm'); ?>" required maxlength="100"
                            value="<?php echo esc_attr(isset($_POST['email']) ? $_POST['email'] : ''); ?>">
                    </div>
                </div>

                <!-- Review Content -->
                <div class="vm-reviews__field">
                    <label class="vm-reviews__label" for="vm-comment">
                        <?php esc_html_e('Review', 'vm'); ?> <span class="required">*</span>
                    </label>
                    <textarea id="vm-comment" name="comment" class="vm-reviews__textarea"
                        placeholder="<?php printf(esc_attr__('Share your experience with this %s...', 'vm'), $post_type_label); ?>"
                        required rows="5" maxlength="65525"></textarea>
                </div>

                <!-- WP hidden fields -->
                <?php wp_nonce_field('comment_nonce_action', 'comment_nonce_field'); ?>
                <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($post_id); ?>">
                <input type="hidden" name="comment_parent" value="0">

                <button type="submit" class="vm-button vm-reviews__submit">
                    <?php esc_html_e('Submit Review', 'vm'); ?>
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="#000000">
                        <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </form>
        </div>
    <?php endif; ?>

</div>