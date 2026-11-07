<?php
$badge = get_field('badge_banner_home');
$heading = get_field('heading_banner_home');
$sub_heading = get_field('sub_heading_banner_home');
$button = get_field('button_heading_banner_home');
$services = get_field('list_services_banner_home');
?>
<section class="ha-section banner-section" id="section-car-rental-banner">
    <div class="container">
        <div class="banner-section__card">
            <img alt="Hoi An Car Rental Chauffeur Service" class="banner-section__bg-img"
                src="http://localhost:10043/wp-content/uploads/2026/11/image-banner-ha.webp">
            <div class="banner-section__overlay"></div>
            <div class="banner-section__glow"></div>
            <div class="banner-section__content">
                <?php if (!empty($badge)): ?>
                    <div class="ha-badge">
                        <?php echo ha_svg_icon('badge'); ?>
                        <?= $badge ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="banner-section__title">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sub_heading)): ?>
                    <p class="banner-section__subtitle">
                        <?= $sub_heading ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($services)): ?>
                    <div class="banner-section__features">

                        <?php foreach ($services as $service): ?>
                            <span class="banner-section__feature-item">
                                <?php echo ha_svg_icon('check'); ?>
                                <?php echo $service['service_item'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="banner-section__actions">
                    <a class="ha-button" href="#transfers">
                        BOOK NOW
                        <?php echo ha_svg_icon('arrow_next'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>