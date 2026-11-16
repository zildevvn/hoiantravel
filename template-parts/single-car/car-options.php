<?php
/**
 * Single Car Options Grid Component
 *
 * Renders available vehicle options for the selected passenger size.
 *
 * Business Rules:
 * 1. Capacity condition: $total_passengers <= intval($option['type']).
 * 2. If passenger count exceeds capacity, the option is displayed as disabled/unfit.
 * 3. Price is charged strictly per vehicle, never per passenger.
 * 4. Only suitable options allow the customer to continue to checkout.
 *
 * @package hoiantravel
 */

$post_id = isset($args['post_id']) ? intval($args['post_id']) : get_the_ID();
$adults = isset($args['adults']) ? intval($args['adults']) : (isset($_POST['adults']) ? intval($_POST['adults']) : 2);
$children = isset($args['children']) ? intval($args['children']) : (isset($_POST['children']) ? intval($_POST['children']) : 0);
$total_passengers = isset($args['total_passengers']) ? intval($args['total_passengers']) : ($adults + $children);
$car_options = isset($args['options']) ? $args['options'] : get_field('option_car', $post_id);
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

if (empty($car_options) || !is_array($car_options)) {
    return;
}

// Vehicle specs metadata
$car_metadata = [
    4 => [
        'name' => 'Sedan 4-Seater',
        'desc' => 'Ideal for solo travelers, couples, or small families with light luggage.',
        'icon' => 'car-4-seat',
        'luggage' => '2 Standard Suitcases (24")',
        'features' => ['Air Conditioning', 'Free Wi-Fi', 'Bottled Water', 'Phone Charger'],
    ],
    7 => [
        'name' => 'SUV 7-Seater',
        'desc' => 'Spacious and comfortable with elevated seating, perfect for families and extra luggage.',
        'icon' => 'car-7-seat',
        'luggage' => '4-5 Standard Suitcases (24")',
        'features' => ['Spacious Legroom', 'Air Conditioning', 'Free Wi-Fi', 'Bottled Water'],
    ],
    16 => [
        'name' => 'Van 16-Seater',
        'desc' => 'High-roof transit passenger van for tour groups, large families, and ample baggage.',
        'icon' => 'car-16-seat',
        'luggage' => '10-12 Standard Suitcases (24")',
        'features' => ['High Ceiling', 'Individual AC Vents', 'Free Wi-Fi', 'Microphone Ready'],
    ],
];

// Check if any option is suitable
$suitable_count = 0;
foreach ($car_options as $option) {
    $capacity = isset($option['type']) ? intval($option['type']) : 0;
    if ($total_passengers <= $capacity) {
        $suitable_count++;
    }
}
?>

<section class="ha-section ha-tour-options ha-car-options" id="ha-car-options-section">
    <div class="container">
        <div class="ha-car-options__header d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1 ha-car-options__title">
                    <?php if ($suitable_count > 0): ?>
                        <?= sprintf(
                            esc_html(_n('Choose from %d suitable vehicle for %d passengers', 'Choose from %d suitable vehicles for %d passengers', $suitable_count, 'hoiantravel')),
                            $suitable_count,
                            $total_passengers
                        ); ?>
                    <?php else: ?>
                        <?php esc_html_e('No Vehicles Available for Your Group Size', 'hoiantravel'); ?>
                    <?php endif; ?>
                </h2>
                <p class="text-muted mb-0 ha-car-options__subtitle">
                    <?= sprintf(
                        esc_html__('Selected party: %d Adults, %d Children (%d total seats required). Price is fixed per vehicle.', 'hoiantravel'),
                        $adults,
                        $children,
                        $total_passengers
                    ); ?>
                </p>
            </div>

            <div class="ha-car-options__badge" <?= ($suitable_count === 0) ? 'style="display: none;"' : ''; ?>>
                <span class="ha-badge">
                    <?= ha_svg_icon('check-circle'); ?>
                    <?php esc_html_e('Private Door-to-Door', 'hoiantravel'); ?>
                </span>
            </div>
        </div>

        <!-- Notification when previously selected option became unfit -->
        <div class="ha-car-selection-notice"
            style="display: none; margin-bottom: 20px; padding: 14px 18px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; color: #92400e; font-size: 14px; display: none;">
            <div class="d-flex align-items-center gap-2">
                <?= ha_svg_icon('alert-triangle'); ?>
                <span><?php esc_html_e('Your previously selected vehicle is too small for the updated passenger count. Please choose an available vehicle below.', 'hoiantravel'); ?></span>
            </div>
        </div>

        <!-- Empty state: No option fits the passenger group size -->
        <div class="ha-car-options-empty"
            style="<?= ($suitable_count === 0) ? '' : 'display: none;'; ?> padding: 48px 24px; text-align: center; background: #fff; border-radius: 16px; border: 1px solid #fed7aa; box-shadow: 0 4px 16px rgba(25, 35, 43, 0.04);">
            <div
                style="width: 56px; height: 56px; margin: 0 auto 16px; background: #fff7ed; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ea580c;">
                <?= ha_svg_icon('alert-triangle'); ?>
            </div>
            <h3 class="h4 ha-car-options-empty__title" style="color: #19232B; margin-bottom: 8px;">
                <?= sprintf(esc_html__('Group Size (%d Passengers) Exceeds Single Vehicle Capacity', 'hoiantravel'), $total_passengers); ?>
            </h3>
            <p style="color: #555; max-width: 620px; margin: 0 auto 20px; line-height: 1.6;">
                <?= esc_html__('Our largest single vehicle accommodates up to 16 passengers. For larger groups, our concierge team can seamlessly arrange multiple coordinated private vehicles with a customized group discount.', 'hoiantravel'); ?>
            </p>
            <div class="d-flex justify-content-center gap-3">
                <?php $phone = get_field('phone', 'option'); ?>
                <?php if (!empty($phone)): ?>
                    <a href="https://wa.me/<?= preg_replace('/\D/', '', $phone); ?>" class="ha-button" target="_blank"
                        rel="noopener noreferrer">
                        <span><?php esc_html_e('Contact Concierge via WhatsApp', 'hoiantravel'); ?></span>
                        <?= ha_svg_icon('arrow-up-right'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Car Options Grid -->
        <div class="ha-tour-options__grid ha-car-options__grid mt-4"
            style="<?= ($suitable_count === 0) ? 'display: none;' : ''; ?>">
            <?php
            $first_suitable_selected = false;
            foreach ($car_options as $key => $option):
                $capacity = isset($option['type']) ? intval($option['type']) : 0;
                $is_fit = ($total_passengers > 0 && $capacity > 0 && $total_passengers <= $capacity);
                $price_raw = !empty($option['price']) ? floatval(str_replace(',', '', (string) $option['price'])) : 0;

                $meta = isset($car_metadata[$capacity]) ? $car_metadata[$capacity] : [
                    'name' => !empty($option['name']) ? $option['name'] : sprintf(__('Vehicle %d Seats', 'hoiantravel'), $capacity),
                    'desc' => '',
                    'icon' => 'car',
                    'luggage' => 'Standard luggage',
                    'features' => ['Air Conditioning', 'Free Wi-Fi'],
                ];

                $title = !empty($option['name']) ? $option['name'] : $meta['name'];

                $is_active = false;
                if ($is_fit && !$first_suitable_selected) {
                    $is_active = true;
                    $first_suitable_selected = true;
                }

                $card_classes = ['option-item', 'car-option-item'];
                if ($is_active) {
                    $card_classes[] = 'is-selected';
                }
                if (!$is_fit) {
                    $card_classes[] = 'is-disabled';
                    $card_classes[] = 'is-unfit';
                }
                ?>
                <div class="<?= esc_attr(implode(' ', $card_classes)); ?>" data-key="<?= esc_attr($key); ?>"
                    data-capacity="<?= esc_attr($capacity); ?>" data-fit="<?= $is_fit ? '1' : '0'; ?>"
                    style="<?= !$is_fit ? 'display: none !important;' : ''; ?>">

                    <!-- Header -->
                    <div class="option-item__header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="car-option-item__icon" aria-hidden="true">
                                <?= ha_svg_icon($meta['icon']); ?>
                            </span>
                            <h3 class="h5 mb-0"><?= esc_html($title); ?></h3>
                        </div>
                        <div class="option-item__radio">
                            <div class="option-item__radio-inner"></div>
                        </div>
                    </div>

                    <!-- Capacity Status Badge -->
                    <div class="car-option-item__capacity mt-2">
                        <?php if ($is_fit): ?>
                            <span class="badge-capacity badge-capacity--ok">
                                <?= ha_svg_icon('check-simple'); ?>
                                <?= sprintf(esc_html__('Fits up to %d passengers (Your group: %d)', 'hoiantravel'), $capacity, $total_passengers); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge-capacity badge-capacity--unfit">
                                <?= ha_svg_icon('menu-close-icon'); ?>
                                <?= sprintf(esc_html__('Too small: Max %d passengers (Your group: %d)', 'hoiantravel'), $capacity, $total_passengers); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <?php if (!empty($meta['desc'])): ?>
                        <div class="option-item__desc mt-2">
                            <p><?= esc_html($meta['desc']); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Vehicle Specs & Benefits -->
                    <ul class="option-item__benefits mt-3" aria-label="Vehicle Features">
                        <li>
                            <?= ha_svg_icon('users'); ?>
                            <span><strong><?php esc_html_e('Max Capacity:', 'hoiantravel'); ?></strong>
                                <?= sprintf(esc_html__('%d Passengers', 'hoiantravel'), $capacity); ?></span>
                        </li>
                        <li>
                            <?= ha_svg_icon('car-simple'); ?>
                            <span><strong><?php esc_html_e('Luggage:', 'hoiantravel'); ?></strong>
                                <?= esc_html($meta['luggage']); ?></span>
                        </li>
                        <li>
                            <?= ha_svg_icon('door'); ?>
                            <span><?php esc_html_e('Private door-to-door transfer', 'hoiantravel'); ?></span>
                        </li>
                        <li>
                            <?= ha_svg_icon('check-simple'); ?>
                            <span><?php esc_html_e('Tolls, petrol & driver included', 'hoiantravel'); ?></span>
                        </li>
                    </ul>

                    <!-- Pricing Section -->
                    <div class="option-item__pricing mt-auto pt-3 border-top">
                        <?php if ($price_raw <= 0): ?>
                            <div class="option-item__price-total text-muted">
                                <?php esc_html_e('Contact for Price', 'hoiantravel'); ?>
                            </div>
                        <?php else: ?>
                            <div class="d-flex align-items-baseline gap-2">
                                <h4 class="option-item__price-total h4 mb-0">
                                    <?= esc_html(ha_format_price($price_raw, $currency)); ?>
                                </h4>
                                <span class="text-muted small">/ <?php esc_html_e('car', 'hoiantravel'); ?></span>
                            </div>
                            <div class="option-item__price-calc">
                                <span><?php esc_html_e('Fixed rate — NOT per passenger', 'hoiantravel'); ?></span>
                            </div>
                            <div class="option-item__price-tax">
                                <?php esc_html_e('All road tolls, airport fees & fuel included', 'hoiantravel'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Button -->
                    <div class="option-item__cta mt-3">
                        <?php if ($is_fit): ?>
                            <button type="button"
                                class="btn btn-select btn-select-car <?= $is_active ? 'btn-select--active' : ''; ?>"
                                data-option-id="<?= esc_attr($key); ?>" data-car-id="<?= esc_attr($post_id); ?>"
                                data-capacity="<?= esc_attr($capacity); ?>">
                                <?= $is_active ? esc_html__('Continue', 'hoiantravel') : esc_html__('Select', 'hoiantravel'); ?>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-select btn-select-car btn-select--disabled" disabled
                                title="<?= sprintf(esc_attr__('Exceeds capacity of %d passengers', 'hoiantravel'), $capacity); ?>">
                                <?= sprintf(esc_html__('Unavailable (Max %d)', 'hoiantravel'), $capacity); ?>
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>