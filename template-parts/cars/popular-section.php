<?php
$heading = get_field('hd_popular_car_tpl');
$sub_hd = get_field('sub_hd_popular_car_tpl');
$popular_options = get_field('popular_options');
$phone = get_field('phone', 'option');
?>
<?php if (!empty($popular_options)): ?>
    <section class="vm-section popular-section">
        <div class="popular-section__graphic">
            <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
                alt="image graphic phu quoc island tours" />
        </div>
        <div class="container">
            <?php vm_icon_heading() ?>
            <?php if (!empty($heading)): ?>
                <h2 class="vm-heading">
                    <?= $heading ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($sub_hd)): ?>
                <p class="vm-sub-heading">
                    <?= $sub_hd ?>
                </p>
            <?php endif; ?>

            <div id="book-car-section" class="popular-section-tables">
                <?php
                // SVGs for cars
                $svgs = [
                    'sedan' => ha_svg_icon('car-4-seat'),
                    'suv' => ha_svg_icon('car-7-seat'),
                    'van' => ha_svg_icon('car-16-seat'),
                    '29_seats' => ha_svg_icon('car-29-seat'),
                    '35_seats' => ha_svg_icon('car-35-seat'),
                    '45_seats' => ha_svg_icon('car-45-seat'),
                    'pin' => ha_svg_icon('map-pin'),
                    'info' => ha_svg_icon('info-circle')
                ];

                $currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
                $currency_code = ($currency === '₫') ? 'VND' : (($currency === '€') ? 'EUR' : 'USD');

                // Helper function for formatting inline using project standard
                $format_price = function ($price) use ($currency) {
                    if (!is_scalar($price) || is_bool($price)) {
                        return '<span class="price-contact">Contact</span>';
                    }
                    $val = trim((string) $price);
                    if ($val === '' || $val === '0') {
                        return '<span class="price-contact">Contact</span>';
                    }
                    return '<span class="price-amount">' . esc_html(ha_format_price($val, $currency)) . '</span>';
                };
                ?>
                <div class="hotel-area-tabs-container">
                    <?php
                    // Generate a unique ID for this section instance to ensure unique tab/panel IDs
                    $section_uid = uniqid('ha-');
                    ?>

                    <?php if (count($popular_options) > 1): ?>
                        <div class="hotel-area-tabs" role="tablist" aria-label="Hotel Areas">
                            <?php foreach ($popular_options as $index => $popular_option): ?>
                                <?php
                                $hotel_area_name = $popular_option['hotel_area']['name'];
                                $tab_id = 'tab-' . $section_uid . '-' . $index;
                                $panel_id = 'panel-' . $section_uid . '-' . $index;
                                $is_active = $index === 0;
                                ?>
                                <button role="tab" aria-selected="<?= $is_active ? 'true' : 'false' ?>"
                                    aria-controls="<?= $panel_id ?>" id="<?= $tab_id ?>"
                                    class="hotel-area-tab <?= $is_active ? 'is-active' : '' ?>">
                                    <?= esc_html($hotel_area_name) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="hotel-area-panels">
                        <?php foreach ($popular_options as $index => $popular_option): ?>
                            <?php
                            $hotel_area = $popular_option['hotel_area'];
                            $hotel_area_name = $hotel_area['name'];
                            $services = $hotel_area['services'];
                            $tab_id = 'tab-' . $section_uid . '-' . $index;
                            $panel_id = 'panel-' . $section_uid . '-' . $index;
                            $is_active = $index === 0;
                            ?>
                            <div id="<?= $panel_id ?>" role="tabpanel" aria-labelledby="<?= $tab_id ?>" class="hotel-area-panel"
                                <?= $is_active ? '' : 'hidden' ?>>

                                <div class="popular-table-wrapper">
                                    <div class="table-responsive">
                                        <table class="vm-popular-table single-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-area-name">Area / Service</th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['sedan'] ?></div>
                                                            <div class="car-label">Sedan</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['suv'] ?></div>
                                                            <div class="car-label">SUV</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['van'] ?></div>
                                                            <div class="car-label">Van</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['29_seats'] ?></div>
                                                            <div class="car-label">29 Seats</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['35_seats'] ?></div>
                                                            <div class="car-label">35 Seats</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="car-header">
                                                            <div class="car-icon"><?= $svgs['45_seats'] ?></div>
                                                            <div class="car-label">45 Seats</div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="popular-price-table__group">
                                                <?php if (!empty($services)): ?>
                                                    <?php foreach ($services as $service): ?>
                                                        <tr>
                                                            <td class="col-service-name"><?= esc_html($service['service_name']) ?></td>
                                                            <td><?= $format_price($service['sedan']) ?></td>
                                                            <td><?= $format_price($service['suv']) ?></td>
                                                            <td><?= $format_price($service['van']) ?></td>
                                                            <td><?= $format_price($service['29_seats']) ?></td>
                                                            <td><?= $format_price($service['35_seats']) ?></td>
                                                            <td><?= $format_price($service['45_seats']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" style="text-align:center;">No services available</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="popular-table-footer">
                                        <span class="footer-icon"><?= $svgs['info'] ?></span>
                                        <span class="footer-text">All prices are in <?= esc_html($currency_code); ?> and for reference only. Please contact us for more details.</span>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php if (!empty($phone)): ?>
                <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $phone); ?>"
                    class="vm-button d-flex align-items-center justify-content-center" target="_blank"
                    rel="noopener noreferrer">
                    Contact Now

                    <?= ha_svg_icon('arrow-up-right'); ?>
                </a>
            <?php endif; ?>

        </div>
    </section>
<?php endif; ?>