<?php
$heading = get_field('hd_faq_tpl_t');
$sub_hd = get_field('sub_hd_faq_tpl_t');
$img = get_field('img_faq_tpl_t');
$hd_img = get_field('hd_img_faq_tpl_t');
$faqs = get_field('faqs_list_tpl_t');
?>

<?php if (!empty($faqs)): ?>
    <section class="vm-section faqs-section">
        <div class="faqs-section__graphic">
            <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
                alt="image graphic for FAQs phu quoc tours" width="300" height="259" />
        </div>

        <div class="container">
            <div class="faqs-section-inner d-flex">
                <div class="faqs-section__left">
                    <?php vm_icon_heading() ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="vm-heading">
                            <?= $heading ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($sub_hd)): ?>
                        <p class="vm-sub-heading">
                            <?= $sub_hd ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($img)): ?>
                        <div class="faqs-section__img">
                            <img src="<?= $img ?>" alt="image for <?= $heading ?>" />
                            <?php if (!empty($hd_img)): ?>
                                <p class="mb-0 h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-quote-icon lucide-quote">
                                        <path
                                            d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                                        </path>
                                        <path
                                            d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                                        </path>
                                    </svg>
                                    <?= $hd_img ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="faqs-section__right">
                    <div class="faqs-section__list faqs-list">
                        <?php foreach ($faqs as $key => $item): ?>
                            <?php $classActive = $key == 0 ? 'is-active' : '' ?>
                            <div class="faq-item <?= $classActive ?>">
                                <div class="faq-item__question d-flex justify-content-between gap-2">
                                    <h3 class="h6 mb-0">
                                        0
                                        <?= $key + 1 ?> -
                                        <?= $item['question'] ?>
                                    </h3>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>
                                <div class="faq-item__answer">
                                    <?= $item['answer'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>