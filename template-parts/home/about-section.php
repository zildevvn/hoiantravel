<?php
$heading = get_field('heading_ab_home');
$sub_heading = get_field('sub_heading_ab_home');
$desc = get_field('desc_ab_home');
$badge = get_field('badge_ab_home');
$cards = get_field('list_cards');
$image = get_field('image_ab_home');
$sub_hd_img = get_field('sub_hd_image_ab_home');
$hd_img = get_field('hd_image_ab_home');

?>

<section class="ha-section about-section" id="about">
    <!-- Background Heritage Graphic Watermark: Shophouse Rooftops & Wooden Architectural Arches in #DFA128 at 3.5% Opacity -->
    <div aria-hidden="true" class="about-section__watermark">
        <?php echo ha_svg_icon('about-watermark'); ?>
    </div>
    <!-- Content Container -->
    <div class="container">
        <div class="about-section__grid">
            <!-- LEFT COLUMN (6 Columns - Human Touch & Official Credentials) -->
            <div class="about-section__media">
                <div class="about-section__media-wrap">
                    <?php if (!empty($image)): ?>
                        <!-- Main Image -->
                        <div class="about-section__image-box">
                            <img alt="<?= $image['alt'] ?>" class="about-section__image"
                                src="<?= $image['sizes']['large'] ?>" />
                        </div>
                    <?php endif; ?>
                    <!-- Floating Inset Badge / Stamp Card overlapping bottom-right corner -->
                    <div class="about-section__badge-card">
                        <div class="about-section__badge-icon">
                            <?php echo ha_svg_icon('shield-check'); ?>
                        </div>
                        <div class="about-section__badge-content">
                            <?php if (!empty($hd_img)): ?>
                                <h4 class="about-section__badge-title h6">
                                    <?= $hd_img ?>
                                </h4>
                            <?php endif; ?>
                            <?php if (!empty($sub_hd_img)): ?>
                                <p class="about-section__badge-desc">
                                    <?= $sub_hd_img ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT COLUMN (6 Columns - Brand Narrative & Core Services) -->
            <div class="about-section__content">
                <!-- Pill Badge -->
                <?php if (!empty($badge)): ?>
                    <div class="ha-badge">
                        <?php echo ha_svg_icon('badge'); ?>
                        <?= $badge ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2>
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sub_heading)): ?>
                    <p class="about-section__subtitle">
                        <?= $sub_heading ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <!-- Story Excerpt -->
                    <p class="about-section__excerpt">
                        <?= $desc ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($cards)): ?>
                    <!-- 3 Core Specialization Cards -->
                    <div class="about-section__features">
                        <?php foreach ($cards as $key => $card): ?>
                            <div class="about-feature-card">
                                <div class="about-feature-card__icon">
                                    <img src="<?= $card['icon'] ?>"
                                        alt="hoi an travel icon about for <?= $card['heading'] ?>" />
                                </div>

                                <span class="about-feature-card__title">
                                    <?= $card['heading'] ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <!-- Founder's Personal Commitment Strip -->
                <div class="about-section__commitment">
                    <blockquote class="about-section__quote">
                        “Together with my team, we will make your time in Vietnam wonderful and unforgettable.”
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</section>