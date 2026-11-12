<?php
$heading = get_field('hd_faq_tpl_blog');
$badge = get_field('badge_faq_tpl_blog');
$img = get_field('img_faq_tpl_blog');
$faqs = get_field('faqs_list_tpl_blog');
?>

<?php if (!empty($faqs)): ?>
    <section class="ha-section faqs-section" id="faqs-section">
        <div class="container">
            <div class="faqs-section__grid">
                <div class="faqs-section__sidebar">
                    <?php if (!empty($badge)): ?>
                        <div class="ha-badge">
                            <?php echo ha_svg_icon('badge'); ?>
                            <?= esc_html($badge); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="faqs-section__title">
                            <?= esc_html($heading); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($img) && !empty($img['url'])): ?>
                        <div class="faqs-section__media">
                            <img src="<?= esc_url($img['url']); ?>"
                                alt="<?= esc_attr(!empty($img['alt']) ? $img['alt'] : (!empty($heading) ? $heading : 'Hoi An Travel FAQs')); ?>"
                                loading="lazy" />
                        </div>
                    <?php endif; ?>
                </div>

                <div class="faqs-section__content">
                    <div class="faqs-section__list faqs-list">
                        <?php foreach ($faqs as $key => $item): ?>
                            <?php
                            $classActive = ($key === 0) ? 'is-active' : '';
                            $q_num = sprintf('%02d', $key + 1);
                            $question = !empty($item['question']) ? $item['question'] : '';
                            $answer = !empty($item['answer']) ? $item['answer'] : '';
                            ?>
                            <div class="faq-item <?= esc_attr($classActive); ?>">
                                <button type="button" class="faq-item__question"
                                    aria-expanded="<?= ($key === 0) ? 'true' : 'false'; ?>">
                                    <h3 class="faq-item__title">
                                        <span class="faq-item__number">
                                            <?= esc_html($q_num); ?>
                                        </span>
                                        <span class="faq-item__text">
                                            <?= esc_html($question); ?>
                                        </span>
                                    </h3>

                                    <span class="faq-item__icon" aria-hidden="true">
                                        <?php echo function_exists('ha_svg_icon') && ha_svg_icon('chevron-down') ? ha_svg_icon('chevron-down') : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>'; ?>
                                    </span>
                                </button>
                                <div class="faq-item__answer">
                                    <div class="faq-item__answer-inner">
                                        <?= $answer; ?>
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