<?php
/**
 * Single Tour FAQs Section
 *
 * Reusable accordion list utilizing global theme behavior (.faqs-list / .faq-item)
 *
 * @package hoiantravel
 */

$faqs = get_field('faqs_list_tour');

if (empty($faqs) || !is_array($faqs)) {
    return;
}
?>

<div id="tour-faqs" class="tour-section-block tour-faqs">
    <div class="tour-section-header">
        <h2 class="ha-heading tour-section-title h3">
            <?php esc_html_e('Frequently Asked Questions', 'ha'); ?>
        </h2>
    </div>

    <div class="faqs-section__list faqs-list">
        <?php foreach ($faqs as $key => $item):
            $question = $item['question'] ?? '';
            $answer = $item['answer'] ?? '';
            if (empty($question)) {
                continue;
            }
            $is_first = ($key === 0);
            $class_active = $is_first ? 'is-active' : '';
            ?>
            <div class="faq-item <?= esc_attr($class_active); ?>">
                <div class="faq-item__question" role="button" tabindex="0"
                    aria-expanded="<?= $is_first ? 'true' : 'false'; ?>">
                    <div class="faq-item__q-left">
                        <span class="faq-item__index"><?php printf('%02d', $key + 1); ?></span>
                        <h3 class="faq-item__title">
                            <?= esc_html($question); ?>
                        </h3>
                    </div>

                    <span class="faq-item__arrow" aria-hidden="true">
                        <?= ha_svg_icon('chevron-down'); ?>
                    </span>
                </div>

                <div class="faq-item__answer">
                    <div class="faq-item__answer-inner">
                        <?= wp_kses_post(wpautop($answer)); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>