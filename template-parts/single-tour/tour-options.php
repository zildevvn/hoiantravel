<?php
$tour_options = get_field('tour_options');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
?>

<?php if (!empty($tour_options)): ?>
    <section class="ha-section ha-tour-options">
        <div class="container">
            <h2 class="h4"> Choose from
                <?php echo count($tour_options); ?> available options
            </h2>

            <div class="ha-tour-options__grid">
                <?php foreach ($tour_options as $key => $option): ?>
                    <?php
                    $starting_time = $option['starting_time'] ?? '';
                    $private_tour = $option['private_tour'] ?? false;
                    $price_group = $option['price_group'] ?? 0;
                    $price_group_child = $option['child_price_group'] ?? $option['child_price_group'] ?? 0;
                    $price_private = $option['price_private'] ?? [];
                    $maximum = $option['maximum'] ?? '';
                    ?>
                    <?php
                    $is_selected_class = ($key === 0) ? 'is-selected' : '';
                    $description = isset($option['description']) ? $option['description'] : '';

                    // Pricing Logic
                    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 2;
                    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;
                    $total_pax = $adults + $children;

                    $pricing = ha_calculate_tour_price($option, $adults, $children, get_the_ID());
                    $price_per_person = $pricing['price_per_person'];
                    $children_price = $pricing['child_price'];
                    $total_price = $pricing['total_price'];
                    $is_price_available = $pricing['is_price_available'];
                    ?>
                    <div class="option-item <?= $is_selected_class ?>" data-key="<?= esc_attr($key) ?>">
                        <!-- Header -->
                        <div class="option-item__header">
                            <h3 class="h5"> <?= esc_html($option['name']) ?> </h3>
                            <div class="option-item__radio">
                                <div class="option-item__radio-inner"></div>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($description)): ?>
                            <div class="option-item__desc">
                                <p><?= wp_kses_post($description) ?></p>
                                <a href="#" class="option-item__read-more">Read more</a>
                            </div>
                        <?php endif; ?>

                        <!-- Benefits -->
                        <ul class="option-item__benefits" aria-label="Tour Benefits">
                            <li>
                                <?= ha_svg_icon('globe'); ?>
                                <span>Guide: English</span>
                            </li>

                            <?php if (!empty($maximum) || $private_tour): ?>
                                <li class="maximum">
                                    <?= ha_svg_icon('users'); ?>
                                    <span>
                                        <?= !empty($maximum) ? 'Max: ' . esc_html($maximum) . ' paxs' : 'No Limited'; ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <li>
                                <?= ha_svg_icon('calendar'); ?>
                                <span>Book now, pay later</span>
                            </li>
                            <li>
                                <?= ha_svg_icon('check-simple'); ?>
                                <span>Cancel for free</span>
                            </li>
                        </ul>

                        <!-- Starting Time -->
                        <?php if (!empty($starting_time)): ?>
                            <div class="option-item__time">
                                <span class="option-item__time-label">Starting time</span>
                                <div class="option-item__time-chip">
                                    <?= esc_html($starting_time) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Pricing -->
                        <div class="option-item__pricing">
                            <?php if (!$is_price_available): ?>
                                <div class="option-item__price-total">Liên hệ để biết giá</div>
                            <?php else: ?>
                                <h4 class="option-item__price-total h5">
                                    <?= esc_html(ha_format_price($total_price, $currency)); ?>
                                </h4>
                                <?php if (!empty($pricing['is_car_tour'])): ?>
                                    <div class="option-item__price-calc">
                                        <span>Vehicle Price</span>
                                        <span><?= esc_html(ha_format_price($price_per_person, $currency)); ?></span>
                                    </div>
                                <?php else: ?>
                                    <?php if ($adults > 0): ?>
                                        <div class="option-item__price-calc">
                                            <span>Adults × <?= esc_html($adults) ?></span>
                                            <span><?= esc_html(ha_format_price($price_per_person, $currency)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($children > 0): ?>
                                        <div class="option-item__price-calc">
                                            <span>Children × <?= esc_html($children) ?></span>
                                            <span><?= esc_html(ha_format_price($children_price, $currency)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="option-item__price-tax">All taxes and fees included</div>
                            <?php endif; ?>
                        </div>

                        <!-- CTA -->
                        <div class="option-item__cta">
                            <button type="button"
                                class="btn btn-select <?= ($key === 0) ? 'btn-select--active' : '' ?>"
                                data-option-id="<?= esc_attr($key) ?>"
                                data-tour-id="<?= get_the_ID(); ?>">
                                <?= ($key === 0) ? 'Continue' : 'Select' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>