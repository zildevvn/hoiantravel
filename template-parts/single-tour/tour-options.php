<?php

$tour_options = get_field('tour_options');
?>

<?php if (!empty($tour_options)): ?>
    <section class="vm-section vm-tour-options">
        <div class="container">
            <h2 class="h4"> Choose from
                <?php echo count($tour_options); ?> available options
            </h2>

            <div class="vm-tour-options__grid">
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

                    $pricing = vm_calculate_tour_price($option, $adults, $children, get_the_ID());
                    $price_per_person = $pricing['price_per_person'];
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                    </path>
                                </svg>
                                <span>Guide: English</span>
                            </li>

                            <?php if (!empty($maximum) || $private_tour): ?>
                                <li class="maximum">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <span>
                                        <?= !empty($maximum) ? 'Max: ' . esc_html($maximum) . ' paxs' : 'No Limited'; ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Book now, pay later</span>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
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
                                    <span class="h6 mb-0">₫</span>
                                    <?= number_format($total_price, 0, '.', ',') ?>
                                </h4>
                                <?php if (!empty($pricing['is_car_tour'])): ?>
                                    <div class="option-item__price-calc">
                                        <span>Vehicle Price</span>
                                        <span>
                                            <span class="mb-0">₫</span>
                                            <?= number_format($price_per_person, 0, '.', ',') ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <?php if ($adults > 0): ?>
                                        <div class="option-item__price-calc">
                                            <span>Adults × <?= esc_html($adults) ?></span>
                                            <span>
                                                <span class="mb-0">₫</span>
                                                <?= number_format($price_per_person, 0, '.', ',') ?> </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($children > 0): ?>
                                        <div class="option-item__price-calc">
                                            <span>Children × <?= esc_html($children) ?></span>
                                            <span>
                                                <span class="mb-0">₫</span>
                                                <?= number_format($pricing['child_price'], 0, '.', ',') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="option-item__price-tax">All taxes and fees included</div>
                            <?php endif; ?>
                        </div>

                        <!-- CTA -->
                        <div class="option-item__cta">
                            <button type="button" class="btn btn-select <?= ($key === 0) ? 'btn-select--active' : '' ?>">
                                <?= ($key === 0) ? 'Continue' : 'Select' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>