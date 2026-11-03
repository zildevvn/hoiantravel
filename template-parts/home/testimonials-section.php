<?php

$heading = get_field('hd_testimonials_hp');
$description = get_field('sub_hd_testimonials_hp');
$testimonials = get_field('testimonials_list');
?>
<?php if (!empty($testimonials)): ?>
    <section class="vm-section testimonials-section">
        <div class="container">
            <?php vm_icon_heading() ?>
            <?php if (!empty($heading)): ?>
                <h2 class="vm-heading">
                    <?php echo $heading; ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($description)): ?>
                <div class="vm-sub-heading">
                    <?php echo $description; ?>
                </div>
            <?php endif; ?>

            <div class="testimonials-section___wrapper">
                <div class="testimonials-carousel swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($testimonials as $key => $testimonial): ?>
                            <div class="testimonial-item swiper-slide">
                                <?php if (!empty($testimonial['avatar'])): ?>
                                    <div class="testimonial-item__avatar">
                                        <img src="<?php echo esc_url($testimonial['avatar']); ?>"
                                            alt="avatar for <?= $testimonial['name'] ?>" />
                                    </div>
                                <?php endif; ?>

                                <div class="testimonial-item-content">
                                    <div class="testimonial-item__info">
                                        <div class="info">
                                            <?php if (!empty($testimonial['name'])): ?>
                                                <h3 class="testimonial-item__name h5 mb-0">
                                                    <?= $testimonial['name'] ?>
                                                </h3>
                                            <?php endif; ?>

                                            <?php if (!empty($testimonial['position'])): ?>
                                                <span class="testimonial-item__position h6">
                                                    <?= $testimonial['position'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="testimonial-item__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-quote-icon lucide-quote">
                                                <path
                                                    d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z" />
                                                <path
                                                    d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <?php if (!empty($testimonial['desc'])): ?>
                                        <div class="testimonial-item__quote">
                                            <?= $testimonial['desc'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="testimonials-carousel__control">
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
        </div>
    </section>
<?php endif; ?>