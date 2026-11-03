<?php
$heading = get_field('heading_contact');
$desc = get_field('desc_contact');
$form_contact = get_field('form_contact');
$address = get_field('address', 'option');
$phone = get_field('phone', 'option');
$email = get_field('email', 'option');
$open_time = get_field('open_time', 'option');
?>
<section class="vm-section main-section">
    <div class="container">

        <?php vm_icon_heading() ?>

        <?php if (!empty($heading)): ?>
            <h2 class="vm-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($desc)): ?>
            <p class="vm-sub-heading">
                <?= $desc ?>
            </p>
        <?php endif; ?>

        <div class="main-section-warp">
            <div class="main-section__left">

                <div class="main-section__left-bg">

                </div>

                <div class="main-section__left-inner">
                    <h3 class="h4">Let's Connect</h3>

                    <div class="contact-cards">
                        <?php if (!empty($address)): ?>
                            <div class="contact-info d-flex address">
                                <div class="contact-info__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-map-pin-check-icon lucide-map-pin-check">
                                        <path
                                            d="M19.43 12.935c.357-.967.57-1.955.57-2.935a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 32.197 32.197 0 0 0 .813-.728" />
                                        <circle cx="12" cy="10" r="3" />
                                        <path d="m16 18 2 2 4-4" />
                                    </svg>
                                </div>
                                <div class="contact-info__content">
                                    <h3 class="h5">Address</h3>
                                    <p class="mb-0"> <?= $address ?> </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($phone)): ?>
                            <?php $whatsapp_phone = preg_replace('/\D/', '', $phone); ?>

                            <a href="https://wa.me/<?= esc_attr($whatsapp_phone) ?>" aria-label="WhatsApp"
                                class="contact-info d-flex" target="_blank" rel="noopener noreferrer">
                                <div class="contact-info__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M21 11.5a8.38 8.38 0 0 1-9 8.5 8.5 8.5 0 0 1-4.4-1.2L3 20l1.2-4.5A8.5 8.5 0 1 1 21 11.5Z" />
                                        <path d="M8 10.5c.5 2 2 3.5 4 4" />
                                    </svg>
                                </div>

                                <div class="contact-info__content">
                                    <h3 class="h5">WhatsApp</h3>
                                    <p class="mb-0">
                                        <?= esc_html($phone) ?>
                                    </p>
                                </div>
                            </a>
                        <?php endif; ?>


                        <?php if (!empty($email)): ?>
                            <a href="mailto:<?= $email ?>" aria-label="Send us email" class="contact-info d-flex">
                                <div class="contact-info__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-mail-check-icon lucide-mail-check">
                                        <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                        <path d="m16 19 2 2 4-4" />
                                    </svg>
                                </div>
                                <div class="contact-info__content">
                                    <h3 class="h5">Email Us</h3>
                                    <p class="mb-0">
                                        <?= $email ?>
                                    </p>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="main-section__right">
                <?php if (!empty($form_contact)): ?>

                    <div class="form-wrapper">
                        <?= do_shortcode($form_contact) ?>
                    </div>

                    <div class="form-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    <polyline points="9 12 11 14 15 10"></polyline>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Your data is safe</h4>
                                <p>We never share your info</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Quick response</h4>
                                <p>We'll get back to you soon</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h4>Friendly support</h4>
                                <p>Always ready to help</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>