<?php
$badge = get_field('badge_trust_proof');
$heading = get_field('heading_trust_proof');
$desc = get_field('desc_trust_proof');
$cards = get_field('list_cards_trust_proof');

?>
<section class="trust-proof-section ha-section ha-section--bg">
    <div class="container">
        <?php if (!empty($badge)): ?>
            <p class="ha-badge mb-0">
                <?php echo ha_svg_icon('badge'); ?>
                <?= $badge ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($heading)): ?>
            <h2> <?= $heading ?> </h2>
        <?php endif; ?>

        <?php if (!empty($desc)): ?>
            <p class="mb-0">
                <?= $desc ?>
            </p>
        <?php endif; ?>

        <div class="trust-proof-section__reviews-trip">
            <?php echo do_shortcode('[trustindex no-registration=tripadvisor]'); ?>
        </div>

        <?php if (!empty($cards)): ?>
            <div class="trust-proof-section__cards">
                <?php foreach ($cards as $card): ?>
                    <div class="card-item">
                        <?php if (!empty($card['icon'])): ?>
                            <div class="card-item__icon">
                                <img src="<?= $card['icon'] ?>" alt="icon hoi an travel for <?= $card['heading'] ?>" />
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($card['heading'])): ?>
                            <h3 class="h5">
                                <?= $card['heading'] ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($card['desc'])): ?>
                            <p class="mb-0"> <?= $card['desc'] ?> </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>