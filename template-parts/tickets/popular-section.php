<?php
$heading = get_field('hd_popular_ticket_tpl');
$sub_hd = get_field('sub_hd_popular_ticket_tpl');
$popular_options = get_field('popular_op_ticket_tpl');
$phone = get_field('phone', 'option');
?>
<?php if (!empty($popular_options)): ?>
    <section id="popular-tickets" class="vm-section popular-section">
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

            <div class="popular-section-tables">
                <?php
                // SVGs for tickets
                $svgs = [
                    'adult' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                    'child' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a3 3 0 0 0-3-3H10a3 3 0 0 0-3 3v2"></path><circle cx="12" cy="11" r="3"></circle></svg>',
                    'senior' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="10" cy="7" r="4"></circle><path d="M20 10a2 2 0 0 0-2-2h-1c-1.1 0-2 .9-2 2v11"></path></svg>',
                    'pin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                    'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
                ];

                // Helper function for formatting inline
                $format_price = function ($price) {
                    $val = trim($price);
                    if ($val === '')
                        return '<span class="price-contact">Contact</span>';
                    return '<span class="price-amount">$ ' . number_format((float) $val, 0) . '</span>';
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
                                                    <th class="col-area-name">Ticket type</th>
                                                    <th>
                                                        <div class="tickets-header">
                                                            <div class="tickets-icon">
                                                                <?= $svgs['adult'] ?>
                                                            </div>
                                                            <div class="tickets-label">Adult ticket</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="tickets-header">
                                                            <div class="tickets-icon">
                                                                <?= $svgs['child'] ?>
                                                            </div>
                                                            <div class="tickets-label">Child ticket</div>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="tickets-header">
                                                            <div class="tickets-icon">
                                                                <?= $svgs['senior'] ?>
                                                            </div>
                                                            <div class="tickets-label">Senior ticket</div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="popular-price-table__group">
                                                <?php if (!empty($services)): ?>
                                                    <?php foreach ($services as $service): ?>
                                                        <tr>
                                                            <td class="col-service-name">
                                                                <?= esc_html($service['service_name']) ?>
                                                            </td>
                                                            <td>
                                                                <?= $format_price($service['adult_ticket']) ?>
                                                            </td>
                                                            <td>
                                                                <?= $format_price($service['child_ticket']) ?>
                                                            </td>
                                                            <td>
                                                                <?= $format_price($service['_senior_ticket']) ?>
                                                            </td>
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
                                        <span class="footer-icon">
                                            <?= $svgs['info'] ?>
                                        </span>
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