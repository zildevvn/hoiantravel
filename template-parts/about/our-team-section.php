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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($instagram)): ?>
                                            <a href="<?= esc_url($instagram) ?>" target="_blank" rel="noopener noreferrer"
                                                class="social-link" aria-label="Instagram">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($whatsapp)): ?>
                                            <?php $whatsapp_url = preg_replace('/\D/', '', $whatsapp); ?>
                                            <a href="https://wa.me/<?= esc_attr($whatsapp_url) ?>" target="_blank"
                                                rel="noopener noreferrer" class="social-link" aria-label="WhatsApp">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path
                                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                                    </path>
                                                </svg>
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
                        <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="#000000">
                            <path d="M21 12L3 12M3 12L11.5 3.5M3 12L11.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next">
                        <svg width=" 24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="#000000">
                            <path d="M3 12L21 12M21 12L12.5 3.5M21 12L12.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>