<?php
/**
 * About Page - Counters Section
 *
 * Heritage Credentials & Animated Stats Ribbon
 * Fully synchronized with Hoi An Travel Design System & intro-section
 *
 * @package hoiantravel
 */

$counters = get_field('counters_list_ab');

if (!empty($counters) && is_array($counters)):

    // Helper to parse counter values cleanly (splits prefix, numeric value, suffix)
    if (!function_exists('ha_parse_counter_stat')) {
        function ha_parse_counter_stat($raw)
        {
            $raw = trim((string) $raw);
            if (empty($raw)) {
                return ['prefix' => '', 'number' => '', 'display' => '', 'suffix' => '', 'decimals' => 0, 'is_numeric' => false];
            }

            // Regex matches: [prefix][digits, decimals][suffix]
            if (preg_match('/^([^\d\.,]*)([\d\.,]+)(.*)$/u', $raw, $m)) {
                $prefix     = trim($m[1]);
                $clean_num  = str_replace(',', '', trim($m[2]));
                $suffix     = trim($m[3]);
                $is_numeric = is_numeric($clean_num);
                $decimals   = (strpos($clean_num, '.') !== false) ? strlen(substr(strrchr($clean_num, '.'), 1)) : 0;

                return [
                    'prefix'     => $prefix,
                    'number'     => $is_numeric ? (float) $clean_num : $m[2],
                    'display'    => $m[2],
                    'suffix'     => $suffix,
                    'decimals'   => $decimals,
                    'is_numeric' => $is_numeric,
                ];
            }

            return [
                'prefix'     => '',
                'number'     => $raw,
                'display'    => $raw,
                'suffix'     => '',
                'decimals'   => 0,
                'is_numeric' => false,
            ];
        }
    }

    // Default icon fallback list by position if user hasn't uploaded an icon
    $default_icons = [
        0 => 'users',
        1 => 'clock',
        2 => 'tour-handcrafted',
        3 => 'star-fill',
    ];
?>

<section class="ha-section counters-section" aria-label="<?php esc_attr_e('Hoi An Travel Statistics & Milestones', 'hoiantravel'); ?>">
    <div class="container counters-section__container">
        <div class="counters-section__box">
            <div class="counters-section__grid">
                <?php foreach ($counters as $index => $counter): ?>
                    <?php
                    $label     = $counter['label'] ?? '';
                    $raw_num   = $counter['number'] ?? '';
                    $icon_url  = $counter['icon'] ?? '';
                    $stat      = ha_parse_counter_stat($raw_num);
                    $fallback  = $default_icons[$index % count($default_icons)] ?? 'badge';
                    ?>
                    <div class="ha-counter-item">
                        <!-- Icon Badge -->
                        <div class="ha-counter-item__icon-wrap">
                            <?php if (!empty($icon_url)): ?>
                                <img src="<?= esc_url($icon_url); ?>" 
                                     alt="<?= esc_attr($label); ?>" 
                                     class="ha-counter-item__icon-img" 
                                     loading="lazy" />
                            <?php else: ?>
                                <span class="ha-counter-item__icon-svg" aria-hidden="true">
                                    <?= ha_svg_icon($fallback); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Number & Label Content -->
                        <div class="ha-counter-item__body">
                            <div class="ha-counter-item__number-wrap">
                                <?php if (!empty($stat['prefix'])): ?>
                                    <span class="ha-counter-item__prefix"><?= esc_html($stat['prefix']); ?></span>
                                <?php endif; ?>

                                <span class="ha-counter-item__number"
                                      data-counter-target="<?= esc_attr($stat['is_numeric'] ? $stat['number'] : ''); ?>"
                                      data-counter-decimal="<?= esc_attr($stat['decimals']); ?>">
                                    <?= esc_html($stat['display']); ?>
                                </span>

                                <?php if (!empty($stat['suffix'])): ?>
                                    <span class="ha-counter-item__suffix"><?= esc_html($stat['suffix']); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($label)): ?>
                                <h3 class="ha-counter-item__label">
                                    <?= esc_html($label); ?>
                                </h3>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
