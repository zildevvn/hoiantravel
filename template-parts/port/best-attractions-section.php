<?php
$heading = get_field('hd_att_port_tpl');
$sub_heading = get_field('sub_hd_att_port_tpl');

$terms = get_terms([
    'taxonomy' => 'tour_locations',
    'hide_empty' => true,
    'number' => 5,
]);
?>
<?php if (!empty($terms)): ?>
    <section class="vm-section best-attractions-section">
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

            <div class="best-attractions-section__list">
                <?php foreach ($terms as $key => $term): ?>
                    <?php
                    $image = get_field('image', $term);
                    $custom_link = get_field('custom_link_page', $term);

                    $term_link = !empty($custom_link)
                        ? $custom_link
                        : get_term_link($term);
                    ?>
                    <a href="<?= esc_url($term_link); ?>" id="location-<?= $term->slug ?>" class="attractions-item">
                        <div class="attractions-item__image">
                            <img src="<?= $image['url'] ?>" alt="image for location <?= $term->name ?> " />
                        </div>

                        <div class="attractions-item__overlay"></div>

                        <div class="attractions-item__content">
                            <h3 class="attractions-item__name h5 mb-0">
                                <?= $term->name ?>
                            </h3>
                            <?php if (!empty($term->description)): ?>
                                <p class="attractions-item__desc">
                                    <?= wp_trim_words($term->description, 10, '...') ?>
                                </p>
                            <?php endif; ?>

                            <div class="attractions-item__arrow">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>