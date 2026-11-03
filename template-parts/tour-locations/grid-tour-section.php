<?php
$term = get_queried_object();
$grid_tour = get_field('grid_tour_section', $term);
$heading = $grid_tour['heading'] ?? '';
$sub_heading = $grid_tour['sub_heading'] ?? '';
?>
<?php if (have_posts()): ?>
    <section class="vm-section grid-tour-section">
        <div class="container">

            <?php if (!empty($heading)): ?>
                <?php vm_icon_heading() ?>
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
                <?php
                /* Start the Loop */
                while (have_posts()):
                    the_post();
                    vm_item_tour();
                endwhile;
                ?>
            </div>

            <div class="mt-5" id="vm-grid-tours-pagination" data-ajax="true" data-action="vm_ajax_load_grid_tours"
                data-container="#vm-grid-tours-results" data-nonce="<?= esc_attr(wp_create_nonce('vm_load_grid_tours')) ?>"
                data-params="<?= esc_attr(wp_json_encode(['category' => $term->slug])) ?>">
                <?php
                global $wp_query;
                $current_paged = get_query_var('paged') ? get_query_var('paged') : 1;
                vm_pagination($current_paged, $wp_query->max_num_pages);
                ?>
            </div>
    </section>
<?php endif; ?>