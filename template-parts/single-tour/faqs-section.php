<?php
$faqs = get_field('faqs_list_tour');
?>

<?php if (!empty($faqs)): ?>
    <div id="tour-faqs" class="tour-faqs">
        <h2 class="vm-heading ">Frequently Asked Questions</h2>
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
<?php endif; ?>