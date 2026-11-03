<?php
$heading = get_field('hd_tip_tickets_hp');
$sub_hd = get_field('sub_hd_tip_tickets_hp');
$faqs = get_field('faqs_list_tip_tickets_hp');
?>

<?php if (!empty($faqs)): ?>
    <section class="vm-section tips-section">
        <div class="tips-section__graphic">
            <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
                alt="image graphic for FAQs phu quoc island tours" />
        </div>

        <div class="container">
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

            <div class="faqs-list">
                <?php foreach ($faqs as $key => $item): ?>
                    <?php $classActive = $key == 0 ? 'is-active' : '' ?>
                    <div class="faq-item <?= $classActive ?>">
                        <div class="faq-item__question d-flex justify-content-between gap-2">
                            <h3 class="h6 mb-0">
                                0
                                <?= $key + 1 ?> -
                                <?= $item['question'] ?>
                            </h3>

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-chevron-down-icon lucide-chevron-down">
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
    </section>
<?php endif; ?>