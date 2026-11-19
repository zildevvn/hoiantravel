<?php
/**
 * Taxonomy Tour Locations - Plan Your Visit Section
 *
 * Editorial Travel Guide layout presenting Visitor Essentials:
 * 01. Tickets, 02. Food, and 03. Insider Tips.
 *
 * @package hoiantravel
 */

$term = get_queried_object();

$badge   = ($term instanceof WP_Term) ? trim((string) get_field('badge_plan_location', $term)) : '';
$heading = ($term instanceof WP_Term) ? trim((string) get_field('heading_plan_location', $term)) : '';
$desc    = ($term instanceof WP_Term) ? trim((string) get_field('desc_plan_location', $term)) : '';

$tickets       = ($term instanceof WP_Term) ? get_field('tickets_plan_location', $term) : [];
$tickets       = is_array($tickets) ? $tickets : [];
$tickets_hd    = isset($tickets['heading']) ? trim((string) $tickets['heading']) : '';
$tickets_desc  = isset($tickets['description']) ? trim((string) $tickets['description']) : '';
$tickets_items = (!empty($tickets['list_items']) && is_array($tickets['list_items'])) ? $tickets['list_items'] : [];

$food       = ($term instanceof WP_Term) ? get_field('food_plan_location', $term) : [];
$food       = is_array($food) ? $food : [];
$food_hd    = isset($food['heading']) ? trim((string) $food['heading']) : '';
$food_desc  = isset($food['description']) ? trim((string) $food['description']) : '';
$food_items = (!empty($food['list_items']) && is_array($food['list_items'])) ? $food['list_items'] : [];

$tips       = ($term instanceof WP_Term) ? get_field('tips_plan_location', $term) : [];
$tips       = is_array($tips) ? $tips : [];
$tips_hd    = isset($tips['heading']) ? trim((string) $tips['heading']) : '';
$tips_desc  = isset($tips['description']) ? trim((string) $tips['description']) : '';
$tips_items = (!empty($tips['list_items']) && is_array($tips['list_items'])) ? $tips['list_items'] : [];

$has_masthead = ($badge !== '' || $heading !== '' || $desc !== '');
$has_tickets  = ($tickets_hd !== '' || $tickets_desc !== '' || !empty($tickets_items));
$has_food     = ($food_hd !== '' || $food_desc !== '' || !empty($food_items));
$has_tips     = ($tips_hd !== '' || $tips_desc !== '' || !empty($tips_items));

if (!$has_masthead && !$has_tickets && !$has_food && !$has_tips) {
    return;
}

$tip_icons = ['clock-circle', 'check-circle', 'info-circle'];
?>

<section class="ha-section plan-your-visit-section" id="location-plan-visit"<?php if ($heading !== ''): ?> aria-labelledby="plan-your-visit-heading"<?php endif; ?>>
    <div class="container">
        <?php if ($has_masthead): ?>
            <!-- Editorial Masthead -->
            <header class="plan-your-visit-section__masthead">
                <?php if ($badge !== '' || $heading !== ''): ?>
                    <div class="plan-your-visit-section__heading-group">
                        <?php if ($badge !== ''): ?>
                            <div class="plan-your-visit-section__badge-wrap">
                                <span class="ha-badge">
                                    <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                                    <span class="ha-badge__text"><?= esc_html($badge); ?></span>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($heading !== ''): ?>
                            <h2 id="plan-your-visit-heading" class="plan-your-visit-section__title">
                                <?= esc_html($heading); ?>
                            </h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($desc !== ''): ?>
                    <p class="plan-your-visit-section__intro">
                        <?= nl2br(esc_html($desc)); ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($has_tickets || $has_food || $has_tips): ?>
            <!-- Asymmetric Editorial Bento Grid -->
            <div class="plan-your-visit-section__layout">
                <?php if ($has_tickets): ?>
                    <!-- 01. TICKETS — Featured Horizontal Dossier -->
                    <article class="visit-block visit-block--tickets">
                        <div class="visit-block__top">
                            <div class="visit-block__meta">
                                <span class="visit-block__index" aria-hidden="true">01</span>
                                <span class="visit-block__kicker"><?php esc_html_e('TICKETS', 'hoiantravel'); ?></span>
                            </div>
                            <span class="visit-block__icon" aria-hidden="true"><?= ha_svg_icon('receipt'); ?></span>
                        </div>

                        <?php if ($tickets_hd !== '' || $tickets_desc !== ''): ?>
                            <div class="visit-block__body">
                                <?php if ($tickets_hd !== ''): ?>
                                    <h3 class="visit-block__title">
                                        <?= esc_html($tickets_hd); ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if ($tickets_desc !== ''): ?>
                                    <p class="visit-block__desc">
                                        <?= nl2br(esc_html($tickets_desc)); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tickets_items)): ?>
                            <ul class="visit-block__specs" aria-label="<?php esc_attr_e('Ticket essentials', 'hoiantravel'); ?>">
                                <?php foreach ($tickets_items as $item):
                                    $item_hd      = isset($item['heading']) ? trim((string) $item['heading']) : '';
                                    $item_content = isset($item['content']) ? trim((string) $item['content']) : '';
                                    if ($item_hd === '' && $item_content === '') {
                                        continue;
                                    }
                                ?>
                                    <li class="visit-block__spec-item">
                                        <?php if ($item_hd !== ''): ?>
                                            <span class="visit-block__spec-label"><?= esc_html($item_hd); ?></span>
                                        <?php endif; ?>
                                        <?php if ($item_content !== ''): ?>
                                            <span class="visit-block__spec-value"><?= nl2br(esc_html($item_content)); ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

                <?php if ($has_food): ?>
                    <!-- 02. FOOD — Tall Vertical Culinary Notebook -->
                    <article class="visit-block visit-block--food">
                        <div class="visit-block__top">
                            <div class="visit-block__meta">
                                <span class="visit-block__index" aria-hidden="true">02</span>
                                <span class="visit-block__kicker"><?php esc_html_e('FOOD', 'hoiantravel'); ?></span>
                            </div>
                            <span class="visit-block__icon" aria-hidden="true"><?= ha_svg_icon('coffee'); ?></span>
                        </div>

                        <?php if ($food_hd !== '' || $food_desc !== ''): ?>
                            <div class="visit-block__body">
                                <?php if ($food_hd !== ''): ?>
                                    <h3 class="visit-block__title">
                                        <?= esc_html($food_hd); ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if ($food_desc !== ''): ?>
                                    <p class="visit-block__desc">
                                        <?= nl2br(esc_html($food_desc)); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($food_items)): ?>
                            <ul class="visit-block__menu-list" aria-label="<?php esc_attr_e('Recommended dishes and dining spots', 'hoiantravel'); ?>">
                                <?php foreach ($food_items as $item):
                                    $item_hd   = isset($item['heading']) ? trim((string) $item['heading']) : '';
                                    $item_type = isset($item['type']) ? trim((string) $item['type']) : '';
                                    $item_desc = isset($item['description']) ? trim((string) $item['description']) : '';
                                    if ($item_hd === '' && $item_type === '' && $item_desc === '') {
                                        continue;
                                    }
                                ?>
                                    <li class="visit-block__menu-item">
                                        <?php if ($item_hd !== '' || $item_type !== ''): ?>
                                            <div class="visit-block__menu-head">
                                                <?php if ($item_hd !== ''): ?>
                                                    <span class="visit-block__menu-name"><?= esc_html($item_hd); ?></span>
                                                <?php endif; ?>
                                                <?php if ($item_type !== ''): ?>
                                                    <span class="visit-block__menu-tag"><?= esc_html($item_type); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($item_desc !== ''): ?>
                                            <p class="visit-block__menu-note">
                                                <?= nl2br(esc_html($item_desc)); ?>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

                <?php if ($has_tips): ?>
                    <!-- 03. INSIDER TIPS — Practical Field Notes -->
                    <article class="visit-block visit-block--tips">
                        <div class="visit-block__tips-lead">
                            <div class="visit-block__top">
                                <div class="visit-block__meta">
                                    <span class="visit-block__index" aria-hidden="true">03</span>
                                    <span class="visit-block__kicker"><?php esc_html_e('INSIDER TIPS', 'hoiantravel'); ?></span>
                                </div>
                                <span class="visit-block__icon" aria-hidden="true"><?= ha_svg_icon('feature-lantern'); ?></span>
                            </div>

                            <?php if ($tips_hd !== ''): ?>
                                <h3 class="visit-block__title">
                                    <?= esc_html($tips_hd); ?>
                                </h3>
                            <?php endif; ?>
                            <?php if ($tips_desc !== ''): ?>
                                <p class="visit-block__desc">
                                    <?= nl2br(esc_html($tips_desc)); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($tips_items)): ?>
                            <ul class="visit-block__tips-list" aria-label="<?php esc_attr_e('Practical insider tips', 'hoiantravel'); ?>">
                                <?php foreach ($tips_items as $index => $item):
                                    $item_hd   = isset($item['heading']) ? trim((string) $item['heading']) : '';
                                    $item_desc = isset($item['description']) ? trim((string) $item['description']) : '';
                                    if ($item_hd === '' && $item_desc === '') {
                                        continue;
                                    }
                                    $tip_icon = $tip_icons[$index % count($tip_icons)];
                                ?>
                                    <li class="visit-block__tip-item">
                                        <span class="visit-block__tip-icon" aria-hidden="true"><?= ha_svg_icon($tip_icon); ?></span>
                                        <div>
                                            <?php if ($item_hd !== ''): ?>
                                                <strong class="visit-block__tip-title"><?= esc_html($item_hd); ?></strong>
                                            <?php endif; ?>
                                            <?php if ($item_desc !== ''): ?>
                                                <p class="visit-block__tip-text">
                                                    <?= nl2br(esc_html($item_desc)); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
