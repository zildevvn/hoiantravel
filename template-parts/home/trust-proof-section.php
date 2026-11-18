<?php
$badge   = get_field('badge_trust_proof');
$heading = get_field('heading_trust_proof');
$desc    = get_field('desc_trust_proof');
$cards   = get_field('list_cards_trust_proof');
?>
<section class="trust-proof-section ha-section ha-section--bg" aria-label="<?php esc_attr_e('Traveler Reviews & Core Values', 'hoiantravel'); ?>">
    <div class="container">
        <!-- Editorial Section Header -->
        <div class="trust-proof-section__header mx-auto">
            <?php if (!empty($badge)): ?>
                <div class="trust-proof-section__badge-wrap">
                    <span class="ha-badge mb-0">
                        <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('shield-check'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($badge) ?></span>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($heading)): ?>
                <h2 class="trust-proof-section__title ha-heading">
                    <?= esc_html($heading) ?>
                </h2>
            <?php endif; ?>

            <div class="trust-proof-section__divider" aria-hidden="true"></div>

            <?php if (!empty($desc)): ?>
                <p class="trust-proof-section__subtitle mb-0">
                    <?= esc_html($desc) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- TripAdvisor Verified Reviews Stage -->
        <div class="trust-proof-section__reviews-stage">
            <div class="trust-proof-section__reviews-trip">
                <?php echo do_shortcode('[trustindex no-registration=tripadvisor]'); ?>
            </div>
        </div>

        <!-- 4 Heritage Value Pillars -->
        <?php if (!empty($cards) && is_array($cards)): ?>
            <div class="trust-proof-section__cards ha-list-cards">
                <?php foreach ($cards as $index => $card): ?>
                    <?php
                    $card_icon    = $card['icon'] ?? '';
                    $card_heading = $card['heading'] ?? '';
                    $card_desc    = $card['desc'] ?? '';
                    $step_num     = sprintf('%02d', $index + 1);
                    ?>
                    <div class="card-item trust-pillar-card">
                        <div class="card-item__top">
                            <?php if (!empty($card_icon)): ?>
                                <div class="card-item__icon">
                                    <img src="<?= esc_url($card_icon) ?>"
                                        alt="<?= esc_attr(!empty($card_heading) ? 'icon hoi an travel for ' . $card_heading : 'Hoi An Travel Service Value') ?>"
                                        loading="lazy" />
                                </div>
                            <?php endif; ?>
                            <span class="card-item__index" aria-hidden="true"><?= esc_html($step_num) ?></span>
                        </div>

                        <div class="card-item__body">
                            <?php if (!empty($card_heading)): ?>
                                <h3 class="card-item__title h5">
                                    <?= esc_html($card_heading) ?>
                                </h3>
                            <?php endif; ?>

                            <?php if (!empty($card_desc)): ?>
                                <p class="card-item__desc mb-0">
                                    <?= esc_html($card_desc) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
