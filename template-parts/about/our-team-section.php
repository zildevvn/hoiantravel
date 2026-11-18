<?php
/**
 * About Page - Our Team Section
 *
 * Warm & Professional Team Member Showcase
 * Fully synchronized with Hoi An Travel Design System
 *
 * @package hoiantravel
 */

$heading = get_field('hd_our_team');
$description = get_field('sub_hd_our_team');
$teams = get_field('our_team_list');
$team_count = (!empty($teams) && is_array($teams)) ? count($teams) : 0;

if (!empty($teams) && is_array($teams)):
    ?>
    <section class="ha-section our-team-section ha-section--bg"
        aria-label="<?php esc_attr_e('Meet Our Team at Hoi An Travel', 'hoiantravel'); ?>">
        <div class="container">
            <!-- Section Header -->
            <div class="our-team-section__header text-center">
                <div class="our-team-section__badge-wrap">
                    <span class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('users'); ?></span>
                        <span class="ha-badge__text"><?php esc_html_e('The Faces Behind Your Journey', 'hoiantravel'); ?></span>
                    </span>
                </div>

                <?php if (!empty($heading)): ?>
                    <h2 class="our-team-section__title ha-heading">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php else: ?>
                    <h2 class="our-team-section__title ha-heading">
                        <?php esc_html_e('Meet Our Local Experts', 'hoiantravel'); ?>
                    </h2>
                <?php endif; ?>
            </div>

            <!-- Team Members Grid -->
            <div class="our-team-section__grid our-team-section__grid--cols-<?= esc_attr(min($team_count, 4)); ?>"
                style="--team-count: <?= esc_attr($team_count); ?>;">
                <?php foreach ($teams as $key => $team): ?>
                    <?php
                    $avatar = $team['avatar'] ?? null;
                    $name = $team['name'] ?? '';
                    $position = $team['position'] ?? '';
                    $facebook = $team['facebook'] ?? '';
                    $whatsapp = $team['whatsapp'] ?? '';
                    $instagram = $team['instagram'] ?? '';

                    // Extract avatar URL and alt safely
                    $avatar_url = '';
                    $avatar_alt = $name ?: sprintf(__('Team Member %d', 'hoiantravel'), $key + 1);

                    if (is_array($avatar)) {
                        $avatar_url = $avatar['sizes']['large'] ?? ($avatar['url'] ?? '');
                        if (!empty($avatar['alt'])) {
                            $avatar_alt = $avatar['alt'];
                        }
                    } elseif (is_string($avatar)) {
                        $avatar_url = $avatar;
                    }
                    ?>
                    <div class="ha-team-card">
                        <!-- Avatar Portrait -->
                        <div class="ha-team-card__image-wrap">
                            <?php if (!empty($avatar_url)): ?>
                                <img src="<?= esc_url($avatar_url); ?>" alt="<?= esc_attr($avatar_alt); ?>"
                                    class="ha-team-card__image" loading="lazy" />
                            <?php else: ?>
                                <div class="ha-team-card__placeholder" aria-hidden="true">
                                    <span class="ha-team-card__placeholder-icon">
                                        <?= ha_svg_icon('user'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="ha-team-card__overlay" aria-hidden="true"></div>
                        </div>

                        <!-- Member Meta Info -->
                        <div class="ha-team-card__body">
                            <?php if (!empty($name)): ?>
                                <h3 class="ha-team-card__name">
                                    <?= esc_html($name); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if (!empty($position)): ?>
                                <span class="ha-team-card__position">
                                    <?= esc_html($position); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Social Contacts -->
                            <?php if (!empty($facebook) || !empty($whatsapp) || !empty($instagram)): ?>
                                <div class="ha-team-card__social"
                                    aria-label="<?= esc_attr(sprintf(__('Contact channels for %s', 'hoiantravel'), $name)); ?>">
                                    <?php if (!empty($facebook)): ?>
                                        <a href="<?= esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer"
                                            class="social-link"
                                            aria-label="<?= esc_attr(sprintf(__('Facebook profile of %s', 'hoiantravel'), $name)); ?>">
                                            <?= ha_svg_icon('facebook-line'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($instagram)): ?>
                                        <a href="<?= esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer"
                                            class="social-link"
                                            aria-label="<?= esc_attr(sprintf(__('Instagram profile of %s', 'hoiantravel'), $name)); ?>">
                                            <?= ha_svg_icon('instagram-line'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($whatsapp)): ?>
                                        <?php $clean_wa = preg_replace('/\D/', '', $whatsapp); ?>
                                        <a href="https://wa.me/<?= esc_attr($clean_wa); ?>" target="_blank" rel="noopener noreferrer"
                                            class="social-link"
                                            aria-label="<?= esc_attr(sprintf(__('Chat with %s on WhatsApp', 'hoiantravel'), $name)); ?>">
                                            <?= ha_svg_icon('whatsapp'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>