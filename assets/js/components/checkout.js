/**
 * Checkout Component - Hoi An Travel
 *
 * Handles:
 * 1. Form validation for customer details.
 * 2. AJAX submission to create a tour booking (ha_ajax_submit_checkout).
 * 3. Success confirmation screen transition & error handling.
 */

(function ($) {
    'use strict';

    const initCheckoutForm = () => {
        const $form = $('#ha-checkout-form');
        const $formWrapper = $('#ha-checkout-form-wrapper');
        const $successWrapper = $('#ha-booking-success');

        if (!$form.length) return;

        // Clear individual validation error on user input or change
        $form.find('input, textarea, select').on('input change', function () {
            const $this = $(this);
            const $group = $this.closest('.form-group');
            $group.find('.invalid-feedback').stop(true, true).slideUp(200, function () {
                $(this).text('');
            });
            $this.removeClass('is-invalid');
        });

        // Form submission handler
        $form.on('submit', function (e) {
            e.preventDefault();

            const $btn = $form.find('button[type="submit"]');
            if ($btn.hasClass('is-loading')) return;

            // Reset previous validation state
            $form.find('.invalid-feedback').hide().text('');
            $form.find('.is-invalid').removeClass('is-invalid');

            let isValid = true;
            let $firstErrorField = null;

            const setError = ($input, message) => {
                isValid = false;
                $input.addClass('is-invalid');
                const $feedback = $input.siblings('.invalid-feedback');
                if ($feedback.length) {
                    $feedback.text(message).stop(true, true).slideDown(200);
                } else {
                    $input.closest('.form-group').find('.invalid-feedback').text(message).stop(true, true).slideDown(200);
                }
                if (!$firstErrorField) {
                    $firstErrorField = $input;
                }
            };

            // 1. Validate Customer Name
            const $nameInput = $form.find('input[name="customer_name"]');
            const name = ($nameInput.val() || '').trim();
            if (!name) {
                setError($nameInput, 'Full name is required.');
            }

            // 2. Validate Customer Email
            const $emailInput = $form.find('input[name="customer_email"]');
            const email = ($emailInput.val() || '').trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                setError($emailInput, 'Email address is required.');
            } else if (!emailRegex.test(email)) {
                setError($emailInput, 'Please enter a valid email address.');
            }

            // 3. Validate Phone Number
            const $phoneInput = $form.find('input[name="customer_phone"]');
            const phone = ($phoneInput.val() || '').trim();
            if (!phone) {
                setError($phoneInput, 'Phone / WhatsApp number is required.');
            }

            // 4. Validate Pick-up Location
            const $pickupInput = $form.find('input[name="customer_pickup"]');
            const pickup = ($pickupInput.val() || '').trim();
            if (!pickup) {
                setError($pickupInput, 'Pick-up location is required.');
            }

            // 5. Validate Drop-off Location
            const $dropoffInput = $form.find('input[name="customer_dropoff"]');
            const dropoff = ($dropoffInput.val() || '').trim();
            if (!dropoff) {
                setError($dropoffInput, 'Drop-off location is required.');
            }

            // 6. Validate Terms & Conditions Checkbox
            const $termsInput = $form.find('input[name="terms_conditions"]');
            if ($termsInput.length && !$termsInput.is(':checked')) {
                isValid = false;
                const $termsFeedback = $termsInput.closest('.form-group').find('.invalid-feedback');
                $termsFeedback.text('You must accept the terms and conditions to proceed.').stop(true, true).slideDown(200);
                if (!$firstErrorField) {
                    $firstErrorField = $termsInput;
                }
            }

            // Focus on first invalid input
            if (!isValid) {
                if ($firstErrorField && $firstErrorField.length) {
                    const offset = $firstErrorField.offset().top - 120;
                    $('html, body').animate({ scrollTop: offset > 0 ? offset : 0 }, 300);
                    $firstErrorField.focus();
                }
                return;
            }

            // Enter loading state
            const originalBtnHtml = $btn.html();
            $btn.addClass('is-loading').prop('disabled', true);
            $btn.html('<span>Submitting...</span>');

            const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.php_data && window.php_data.tour_options_nonce) ? window.php_data.tour_options_nonce : '';

            const formData = {
                action: 'ha_ajax_submit_checkout',
                nonce: nonce,
                booking_token: ($form.find('input[name="booking_token"]').val() || '').trim(),
                customer_name: name,
                customer_email: email,
                customer_phone: phone,
                customer_pickup: pickup,
                customer_dropoff: dropoff,
                customer_country: ($form.find('input[name="customer_country"]').val() || '').trim(),
                customer_messages: ($form.find('textarea[name="customer_messages"]').val() || '').trim(),
                payment_method: $form.find('input[name="payment_method"]:checked').val() || 'pay_on_arrival'
            };

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function (response) {
                    if (response && response.success && response.data) {
                        const data = response.data;

                        // Fill confirmation summary fields
                        $('#booking-ref').text(data.reference || '');
                        $('#booking-date').text(data.date || '');
                        $('#booking-payment').text(data.payment_method || '');

                        if (data.country) {
                            $('#booking-country').text(data.country);
                            $('#summary-item-country').css('display', 'flex');
                        } else {
                            $('#summary-item-country').hide();
                        }

                        $('#booking-status').text(data.status || 'Pending Confirmation');
                        $('#booking-email').text(email);

                        // Smoothly transition from form to success message
                        $formWrapper.fadeOut(300, function () {
                            $successWrapper.fadeIn(400);
                            const targetTop = $successWrapper.offset().top - 120;
                            $('html, body').animate({
                                scrollTop: targetTop > 0 ? targetTop : 0
                            }, 500);
                        });
                    } else {
                        $btn.removeClass('is-loading').prop('disabled', false).html(originalBtnHtml);

                        let $errorBox = $form.find('.server-error');
                        if (!$errorBox.length) {
                            $form.append('<div class="server-error invalid-feedback" style="display:none; color:#c24a2d; font-size:14px; margin-top:16px; text-align:center; font-weight:500;"></div>');
                            $errorBox = $form.find('.server-error');
                        }
                        const errorMsg = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'An error occurred while submitting your booking. Please try again.';
                        $errorBox.text(errorMsg).stop(true, true).slideDown(200);
                    }
                },
                error: function () {
                    $btn.removeClass('is-loading').prop('disabled', false).html(originalBtnHtml);
                    let $errorBox = $form.find('.server-error');
                    if (!$errorBox.length) {
                        $form.append('<div class="server-error invalid-feedback" style="display:none; color:#c24a2d; font-size:14px; margin-top:16px; text-align:center; font-weight:500;"></div>');
                        $errorBox = $form.find('.server-error');
                    }
                    $errorBox.text('Server connection error. Please try again later.').stop(true, true).slideDown(200);
                }
            });
        });
    };

    $(document).ready(function () {
        initCheckoutForm();
    });

})(jQuery);

