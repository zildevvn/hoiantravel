<?php
/**
 * Taxonomy Tour Locations - In-Page Sticky Anchor Navigation Bar
 *
 * Reuses Single Tour's `.single-tour-nav` / `#ha-anchor-nav` component
 * and `initAnchorNav()` ScrollSpy + smooth-scroll behavior.
 *
 * @package hoiantravel
 */

$term = get_queried_object();

// 1. Overview (about-section always renders with fallback imagery if gallery is empty)
$has_about = true;

// 2. Tours (grid-tour-section renders when main query has posts)
$has_tours = have_posts();

// 3. Car Rentals (rent-car-section renders when valid car_category term has published cars)
$has_rent_car = false;
$car_category = ($term instanceof WP_Term && function_exists('get_field'))
    ? get_field('select_car_rental', $term)
    : null;

if (
    $car_category instanceof WP_Term
    && $car_category->taxonomy === 'car_category'
    && !empty($car_category->term_id)
) {
    $car_ids = get_posts([
        'post_type'              => 'cars',
        'post_status'            => 'publish',
        'posts_per_page'         => 1,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'tax_query'              => [
            [
                'taxonomy' => 'car_category',
                'field'    => 'term_id',
                'terms'    => (int) $car_category->term_id,
            ],
        ],
    ]);
    $has_rent_car = !empty($car_ids);
}

// 4. Best Time & Map
$best_time     = ($term instanceof WP_Term && function_exists('get_field')) ? get_field('best_time_visit_section', $term) : [];
$has_best_time = is_array($best_time) && (
    !empty(trim((string) ($best_time['description'] ?? '')))
    || !empty(trim((string) ($best_time['map'] ?? '')))
);

// 5. Plan Your Visit
$has_plan_visit = false;
if ($term instanceof WP_Term && function_exists('get_field')) {
    $pv_badge   = trim((string) get_field('badge_plan_location', $term));
    $pv_heading = trim((string) get_field('heading_plan_location', $term));
    $pv_desc    = trim((string) get_field('desc_plan_location', $term));

    $pv_has_group = static function ($group) {
        if (!is_array($group)) {
            return false;
        }
        return !empty(trim((string) ($group['heading'] ?? '')))
            || !empty(trim((string) ($group['description'] ?? '')))
            || !empty($group['list_items']);
    };

    $has_plan_visit = ($pv_badge !== '' || $pv_heading !== '' || $pv_desc !== '')
        || $pv_has_group(get_field('tickets_plan_location', $term))
        || $pv_has_group(get_field('food_plan_location', $term))
        || $pv_has_group(get_field('tips_plan_location', $term));
}

// 6. FAQs
$faqs     = ($term instanceof WP_Term && function_exists('get_field')) ? get_field('faqs_list_location', $term) : [];
$has_faqs = !empty($faqs) && is_array($faqs);

$nav_items = [
    'location-about'      => ['label' => __('Overview', 'hoiantravel'), 'show' => $has_about],
    'location-tours'      => ['label' => __('Tours', 'hoiantravel'), 'show' => $has_tours],
    'rent-car-section'    => ['label' => __('Car Rentals', 'hoiantravel'), 'show' => $has_rent_car],
    'location-best-time'  => ['label' => __('Best Time & Map', 'hoiantravel'), 'show' => $has_best_time],
    'location-plan-visit' => ['label' => __('Plan Your Visit', 'hoiantravel'), 'show' => $has_plan_visit],
    'faqs-section'        => ['label' => __('FAQs', 'hoiantravel'), 'show' => $has_faqs],
];

if (count(array_filter(array_column($nav_items, 'show'))) <= 1) {
    return;
}
?>

<nav class="single-tour-nav ha-anchor-nav ha-anchor-nav--location" id="ha-anchor-nav"
    aria-label="<?php esc_attr_e('Location section navigation', 'hoiantravel'); ?>">
    <div class="container">
        <div class="single-tour-nav__inner">
            <ul class="single-tour-nav__list">
                <?php foreach ($nav_items as $id => $item):
                    if (empty($item['show'])) {
                        continue;
                    }
                ?>
                    <li class="single-tour-nav__item">
                        <a href="#<?= esc_attr($id); ?>" class="single-tour-nav__link">
                            <?= esc_html($item['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

