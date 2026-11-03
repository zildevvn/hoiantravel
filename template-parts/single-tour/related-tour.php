<?php

$current_post_id = get_the_ID();

$current_tour_cats = wp_get_post_terms(
    $current_post_id,
    'tour_cats',
    [
        'fields' => 'names',
    ]
);

$args = [
    'post_type' => 'tours',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => [$current_post_id],
    'orderby' => 'rand',
];

if (!is_wp_error($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cat_ids = wp_get_post_terms(
        $current_post_id,
        'tour_cats',
        [
            'fields' => 'ids',
        ]
    );

    $args['tax_query'] = [
        [
            'taxonomy' => 'tour_cats',
            'field' => 'term_id',
            'terms' => $current_tour_cat_ids,
        ],
    ];
}

$the_query = new WP_Query($args);

$heading = 'Other Tours';

if (is_array($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cats = array_filter(array_map('trim', $current_tour_cats));

    if (!empty($current_tour_cats)) {
        $heading = 'Other <span>' . implode(' & ', $current_tour_cats) . '</span>';
    }
}
?>

<?php if ($the_query->have_posts()): ?>
    <section class="vm-section related-tour-section">
        <div class="container">
            <?php vm_icon_heading() ?>

            <h2 class="vm-heading">
                <?php echo $heading; ?>
            </h2>

            <p class="vm-sub-heading">Explore our Phu Quoc Island Tours and discover the island's best beaches and
                attractions.</p>


            <div class="related-tour-section__list swiper">
                <div class="swiper-wrapper">
                    <?php
                    while ($the_query->have_posts()):
                        $the_query->the_post();
                        ?>
                        <div class="swiper-slide">
                            <?php vm_item_tour(); ?>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>