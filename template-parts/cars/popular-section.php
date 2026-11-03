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
                    'sedan' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 22h48c2 0 4-1 4-3l-2-6h-8l-8-5H22l-8 5H6l-2 6c0 2 2 3 4 3z"/><circle cx="18" cy="22" r="4"/><circle cx="46" cy="22" r="4"/><path d="M20 8h24v5H14l6-5z"/><path d="M32 8v5"/></svg>',
                    'suv' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22h52c2 0 3-1 3-3l-2-7h-12l-6-4H18l-6 4H4l-2 7c0 2 1 3 4 3z"/><circle cx="16" cy="22" r="4"/><circle cx="48" cy="22" r="4"/><path d="M16 8h32v4H10l6-4z"/><path d="M32 8v4"/><path d="M48 12v10"/></svg>',
                    'van' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22h52c1 0 2-1 2-2V10c0-1-1-2-2-2H20L6 16v6z"/><circle cx="18" cy="22" r="4"/><circle cx="46" cy="22" r="4"/><path d="M22 8v14 M34 8v14 M46 8v14"/><path d="M6 16h14v-8"/></svg>',
                    '29_seats' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="6" width="56" height="16" rx="2"/><circle cx="16" cy="22" r="4"/><circle cx="48" cy="22" r="4"/><path d="M12 6v10 M24 6v10 M36 6v10 M48 6v10 M4 16h56"/></svg>',
                    '35_seats' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="60" height="18" rx="2"/><circle cx="18" cy="22" r="4"/><circle cx="46" cy="22" r="4"/><path d="M10 4v10 M20 4v10 M30 4v10 M40 4v10 M50 4v10 M2 14h60"/></svg>',
                    '45_seats' => '<svg viewBox="0 0 64 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="60" height="20" rx="3"/><circle cx="16" cy="22" r="4"/><circle cx="32" cy="22" r="4"/><circle cx="48" cy="22" r="4"/><path d="M10 2v11 M18 2v11 M26 2v11 M34 2v11 M42 2v11 M50 2v11 M56 2v11 M2 13h60"/></svg>',
                    'pin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                    'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
                ];

                // Helper function for formatting inline
                $format_price = function ($price) {
                    $val = trim($price);
                    if ($val === '')
                        return '<span class="price-contact">Contact</span>';
                    return '<span class="price-amount">₫ ' . number_format((float) $val, 0) . '</span>';
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
                                        <span class="footer-text">All prices are in USD and for reference only. Please contact
                                            us for more
                                            details.</span>
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

                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="#000000">
                        <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            <?php endif; ?>

        </div>
    </section>
<?php endif; ?>