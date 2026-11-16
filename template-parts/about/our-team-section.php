<?php

$heading = get_field('hd_our_team');
$description = get_field('sub_hd_our_team');
$teams = get_field('our_team_list');
?>
<?php if (!empty($teams)): ?>
    <section class="ha-section our-team-section">
        <div class="container">
            <?php ha_icon_heading() ?>
            <?php if (!empty($heading)): ?>
                <h2 class="ha-heading">
                    <?php echo $heading; ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($description)): ?>
                <div class="ha-sub-heading">
                    <?php echo $description; ?>
                </div>
            <?php endif; ?>


            <div class="our-team-carousel swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($teams as $key => $team): ?>
                        <?php
                        $avtar = $team['avatar'];
                        $name = $team['name'];
                        $position = $team['position'];
                        $facebook = $team['facebook'];
                        $whatsapp = $team['whatsapp'];
                        $instagram = $team['instagram'];

                        $avatar_url = is_array($avtar) ? $avtar['url'] : $avtar;
                        ?>
                        <div class="team-item swiper-slide">
                            <div class="team-item__image">
                                <?php if (!empty($avatar_url)): ?>
                                    <img src="<?= esc_url($avatar_url) ?>" alt="<?= esc_attr($name) ?>" loading="lazy" />
                                <?php else: ?>
                                    <div class="team-item__image-placeholder">
                                        <?= ha_svg_icon('user'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="team-item__content">
                                <?php if (!empty($name)): ?>
                                    <h3 class="team-item__name h5"><?= esc_html($name) ?></h3>
                                <?php endif; ?>

                                <?php if (!empty($position)): ?>
                                    <span class="team-item__position"><?= esc_html($position) ?></span>
                                <?php endif; ?>

                                <?php if (!empty($facebook) || !empty($whatsapp) || !empty($instagram)): ?>
                                    <div class="team-item__social">
                                        <?php if (!empty($facebook)): ?>
                                            <a href="<?= esc_url($facebook) ?>" target="_blank" rel="noopener noreferrer"
                                                class="social-link" aria-label="Facebook">
                                                <?= ha_svg_icon('facebook-line'); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($instagram)): ?>
                                            <a href="<?= esc_url($instagram) ?>" target="_blank" rel="noopener noreferrer"
                                                class="social-link" aria-label="Instagram">
                                                <?= ha_svg_icon('instagram-line'); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($whatsapp)): ?>
                                            <?php $whatsapp_url = preg_replace('/\D/', '', $whatsapp); ?>
                                            <a href="https://wa.me/<?= esc_attr($whatsapp_url) ?>" target="_blank"
                                                rel="noopener noreferrer" class="social-link" aria-label="WhatsApp">
                                                <?= ha_svg_icon('message-circle'); ?>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="our-team-carousel__control">
                    <div class="swiper-button-prev">
                        <?= ha_svg_icon('arrow-left-long'); ?>
                    </div>
                    <div class="swiper-button-next">
                        <?= ha_svg_icon('arrow-right-long'); ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>