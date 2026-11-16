<?php
$terms = get_terms([
    'taxonomy' => 'tour_locations',
    'hide_empty' => true,
]);
?>

<?php if (!is_wp_error($terms)): ?>
    <section class="vm-section map-section">
        <div class="map-section__graphic">
            <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-002.png"
                alt="image graphic phu quoc island tour" width="324" height="184" />
        </div>
        <div class="container">
            <div class="map-section__map">
                <div class="map-locations">
                    <?php foreach ($terms as $term): ?>
                        <?php
                        $image = get_field('image', $term);
                        $custom_link = get_field('custom_link_page', $term);

                        $term_link = !empty($custom_link)
                            ? $custom_link
                            : get_term_link($term);
                        ?>
                        <a href="<?php echo esc_url($term_link); ?>" id="location-<?= $term->slug ?>" class="location">
                            <div class="location__image">
                                <img src="<?= $image['sizes']['large'] ?>" alt="image for location <?= $term->name ?> " />
                            </div>

                            <div class="location-content">
                                <h3 class="location__name h6 mb-0 d-flex align-items-center">
                                    <?= ha_svg_icon('map-pin-check'); ?>

                                    <?= $term->name ?>
                                </h3>

                                <div class="location__desc">
                                    <?= $term->description ?>
                                    <span class="btn"> Read More </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="map-images">
                    <img class="map-images__bg"
                        src="<?= get_template_directory_uri(); ?>/assets/images/img-map-phu-quoc.jpg"
                        alt="image map phu quoc" width="481" height="711" />

                    <div class="map-images__list">
                        <?php foreach ($terms as $key => $term): ?>
                            <?php $image = get_field('image', $term); ?>
                            <div id="map-<?= $term->slug ?>" data-location="#location-<?= $term->slug ?>" class="map-item"
                                aria-label="Go to <?= $term->name ?>" style="z-index:<?= $key + 1 ?>">
                                <img src="<?= $image['sizes']['thumbnail'] ?>" alt="image for map <?= $term->name ?> "
                                    width="150" height="150" />

                                <div class="map-tooltip">
                                    <span class="map-tooltip__name"><?= $term->name ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>