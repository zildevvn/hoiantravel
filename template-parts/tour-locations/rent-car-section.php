<?php
/**
 * Taxonomy Tour Locations - Rent Car Section
 *
 * Queries all published `cars` posts belonging to the `car_category` term
 * selected in ACF field `select_car_rental` on the current `tour_locations` term,
 * and renders them using `ha_item_car()`.
 *
 * @package hoiantravel
 */

$term = get_queried_object();
$car_category = ($term instanceof WP_Term && function_exists('get_field'))
    ? get_field('select_car_rental', $term)
    : null;

if (
    !($car_category instanceof WP_Term)
    || $car_category->taxonomy !== 'car_category'
    || empty($car_category->term_id)
) {
    return;
}

$car_query = new WP_Query([
    'post_type' => 'cars',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_term_cache' => false,
    'tax_query' => [
        [
            'taxonomy' => 'car_category',
            'field' => 'term_id',
            'terms' => (int) $car_category->term_id,
        ],
    ],
]);

$location_name = ($term instanceof WP_Term && !empty($term->name)) ? $term->name : '';
$section_title = !empty($location_name)
    ? sprintf(__('Private Car Transfers in %s', 'hoiantravel'), $location_name)
    : esc_html($car_category->name);
?>

<?php if ($car_query->have_posts()): ?>
    <section class="ha-section rent-car-section" id="rent-car-section" aria-label="<?= esc_attr($section_title); ?>">
        <div class="container">
            <div class="rent-car-section__header">
                <div class="rent-car-section__header-content">
                    <div class="rent-car-section__badge-wrap">
                        <span class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('car-simple'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($car_category->name); ?></span>
                        </span>
                    </div>

                    <h2 class="rent-car-section__title ha-heading">
                        <?= esc_html($section_title); ?>
                    </h2>
                </div>
            </div>


            <div class="rent-car-section__grid">
                <?php
                while ($car_query->have_posts()):
                    $car_query->the_post();
                    if (function_exists('ha_item_car')) {
                        ha_item_car();
                    }
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

        </div>
    </section>
<?php endif; ?>