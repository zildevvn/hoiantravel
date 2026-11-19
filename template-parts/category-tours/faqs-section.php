<?php
/**
 * Category Tours - FAQs Section
 *
 * Synchronized visually & structurally with Blog Template's `faqs-section.php`
 * (`template-parts/blog/faqs-section.php`) using `.faqs-section` + `.faqs-list`
 * and the global FAQ accordion JS handler, while preserving Category Tours'
 * own ACF fields: `hd_faq_cate_tour_tpl`, `badge_faq_cate_tour_tpl`,
 * `img_faq_cate_tour_tpl`, and `faqs_list_faq_cate_tour_tpl`.
 *
 * @package hoiantravel
 */

$heading = function_exists('get_field') ? get_field('hd_faq_cate_tour_tpl') : '';
$badge = function_exists('get_field') ? get_field('badge_faq_cate_tour_tpl') : '';
$img = function_exists('get_field') ? get_field('img_faq_cate_tour_tpl') : null;
$faqs = function_exists('get_field') ? get_field('faqs_list_faq_cate_tour_tpl') : [];

$img_url = '';
$img_alt = !empty($heading) ? $heading : __('Hoi An Travel FAQs', 'hoiantravel');
if (is_array($img) && !empty($img['url'])) {
    $img_url = $img['url'];
    if (!empty($img['alt'])) {
        $img_alt = $img['alt'];
    }
} elseif (is_numeric($img)) {
    $img_url = wp_get_attachment_image_url((int) $img, 'large');
    $alt_meta = get_post_meta((int) $img, '_wp_attachment_image_alt', true);
    if (!empty($alt_meta)) {
        $img_alt = $alt_meta;
    }
} elseif (is_string($img) && !empty($img)) {
    $img_url = $img;
}
?>

<?php if (!empty($faqs) && is_array($faqs)): ?>
    <section class="ha-section faqs-section" id="faqs-section">
        <div class="container">
            <div class="faqs-section__grid">
                <div class="faqs-section__sidebar">
                    <?php if (!empty($badge)): ?>
                        <div class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('info-circle'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($badge); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="faqs-section__title">
                            <?= esc_html($heading); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($img_url)): ?>
                        <div class="faqs-section__media">
                            <img src="<?= esc_url($img_url); ?>" alt="<?= esc_attr($img_alt); ?>" loading="lazy" />
                        </div>
                    <?php endif; ?>
                </div>

                <div class="faqs-section__content">
                    <div class="faqs-section__list faqs-list">
                        <?php foreach ($faqs as $key => $item): ?>
                            <?php
                            $is_first = ($key === 0);
                            $classActive = $is_first ? 'is-active' : '';
                            $q_num = sprintf('%02d', $key + 1);
                            $question = !empty($item['question']) ? $item['question'] : '';
                            $answer = !empty($item['answer']) ? $item['answer'] : '';
                            $answer_id = 'category-tours-faq-answer-' . $key;
                            ?>
                            <div class="faq-item <?= esc_attr($classActive); ?>">
                                <button type="button" class="faq-item__question"
                                    aria-expanded="<?= $is_first ? 'true' : 'false'; ?>"
                                    aria-controls="<?= esc_attr($answer_id); ?>">
                                    <h3 class="faq-item__title">
                                        <span class="faq-item__number">
                                            <?= esc_html($q_num); ?>
                                        </span>
                                        <span class="faq-item__text">
                                            <?= esc_html($question); ?>
                                        </span>
                                    </h3>

                                    <span class="faq-item__icon" aria-hidden="true">
                                        <?= ha_svg_icon('chevron-down'); ?>
                                    </span>
                                </button>
                                <div class="faq-item__answer" id="<?= esc_attr($answer_id); ?>">
                                    <div class="faq-item__answer-inner">
                                        <?= wp_kses_post($answer); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>