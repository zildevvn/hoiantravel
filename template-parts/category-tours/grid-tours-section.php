<?php
$heading = get_field('hd_list_tour_cate_tpl');
$sub_heading = get_field('sub_hd_list_tour_cate_tpl');
$category = get_field('select_category_tour_tpl');
$location = get_field('select_location_tour_tpl');
$terms = get_field('select_term_tour_cate_tpl');
$term = $terms == 'category'
    ? 'tour_cats'
    : ($terms == 'location' ? 'tour_locations' : '');

$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 12,
    'post_status' => 'publish',
    'paged' => $paged,
);


if ($term) {
    $selected_term = $terms == 'category' ? $category : $location;

    if ($selected_term) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $term,
                'field' => 'slug',
                'terms' => $selected_term->slug,
            ),
        );
    }
}

$query = new WP_Query($args);

?>
<?php if (!empty($category) && $query->have_posts()): ?>
    <section class="vm-section grid-tour-section">
        <div class="container">
            <?php vm_icon_heading() ?>
            <?php if (!empty($heading)): ?>
                <h2 class="vm-heading">
                    <?= $heading ?>
                </h2>
            <?php endif; ?>
            <?php if (!empty($sub_heading)): ?>
                <p class="vm-sub-heading">
                    <?= $sub_heading ?>
                </p>
            <?php endif; ?>

            <div class="grid-tour-section__grid" id="vm-grid-tours-results">
                <?php while ($query->have_posts()):
                    $query->the_post();
                    vm_item_tour();
                endwhile;
                ?>
            </div>

            <div class="mt-5" id="vm-grid-tours-pagination" data-ajax="true" data-action="vm_ajax_load_grid_tours"
                data-container="#vm-grid-tours-results" data-nonce="<?= esc_attr(wp_create_nonce('vm_load_grid_tours')) ?>"
                data-params="<?= esc_attr(wp_json_encode(['category' => $category->slug])) ?>">
                <?php vm_pagination($paged, $query->max_num_pages); ?>
            </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>