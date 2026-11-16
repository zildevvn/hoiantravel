<?php
/**
 * Single Car FAQs Section
 *
 * Reusable accordion list utilizing global theme behavior (.faqs-list / .faq-item)
 * Fetches data strictly from ACF field: `faqs_list_car`.
 *
 * @package hoiantravel
 */

$faqs = get_field('faqs_list_car');

if (empty($faqs) || !is_array($faqs)) {
    return;
}

// Filter out any completely empty items
$valid_faqs = array_filter($faqs, function ($item) {
    return !empty($item['question']) || !empty($item['answer']);
});

if (empty($valid_faqs)) {
    return;
}
?>

<section id="car-faqs" class="tour-section-block tour-faqs car-faqs-block">
    <header class="tour-section-header">
        <h2 class="ha-heading tour-section-title h3">
            <?php esc_html_e('Frequently Asked Questions', 'hoiantravel'); ?>
        </h2>
    </header>

    <div class="faqs-section__list faqs-list">
        <?php
        $faq_index = 0;
        foreach ($valid_faqs as $item):
            $question = $item['question'] ?? '';
            $answer   = $item['answer'] ?? '';
            if (empty($question)) {
                continue;
            }
            $is_first = ($faq_index === 0);
            $class_active = $is_first ? 'is-active' : '';
            ?>
            <div class="faq-item <?= esc_attr($class_active); ?>">
                <div class="faq-item__question" role="button" tabindex="0"
                    aria-expanded="<?= $is_first ? 'true' : 'false'; ?>">
                    <div class="faq-item__q-left">
                        <span class="faq-item__index"><?php printf('%02d', $faq_index + 1); ?></span>
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
            <?php
            $faq_index++;
        endforeach;
        ?>
    </div>
</section>

