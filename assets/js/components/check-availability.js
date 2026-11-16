/**
 * Check Availability & Booking Component
 * Single Tour Page - Hoi An Travel
 *
 * Handles:
 * 1. Guests quantity increment / decrement controls.
 * 2. Tour date & party size validation.
 * 3. AJAX availability checking & rendering of tour options.
 * 4. Option selection and booking session redirect to checkout.
 */

(function ($) {
    'use strict';

    /**
     * Quantity selector counter for adults & children
     */
    const initQuantitySelectors = () => {
        const $selectors = $('.quantity-selector');
        if (!$selectors.length) return;

        // Prevent duplicate listener binding
        $selectors.off('click.haQty').on('click.haQty', '.qty-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const $input = $btn.siblings('input[type="number"]');
            if (!$input.length) return;

            let val = parseInt($input.val(), 10);
            if (isNaN(val)) val = 0;

            let min = parseInt($input.attr('min'), 10);
            if (isNaN(min)) min = 0;

            let max = parseInt($input.attr('max'), 10);
            if (isNaN(max)) max = 999;

            if ($btn.hasClass('qty-btn--plus') || $btn.text().trim() === '+') {
                if (val < max) val++;
            } else if ($btn.hasClass('qty-btn--minus') || $btn.text().trim() === '-') {
                if (val > min) val--;
            }

            $input.val(val).trigger('change');
        });

        // Clamp manually entered values
        $selectors.find('input[type="number"]').off('change.haQty input.haQty').on('change.haQty input.haQty', function () {
            const $input = $(this);
            let val = parseInt($input.val(), 10);
            const min = parseInt($input.attr('min'), 10) || 0;
            const max = parseInt($input.attr('max'), 10) || 999;

            if (isNaN(val) || val < min) {
                $input.val(min);
            } else if (val > max) {
                $input.val(max);
            }
        });
    };

    /**
     * Check Availability & Tour Options Controller
     */
    const initCheckAvailability = () => {
        const $btn = $('#ha-btn-check-availability');
        const $form = $('#form-booking-tour');
        const $container = $('#ha-tour-options-container');

        // Graceful exit if elements do not exist on the current page
        if (!$btn.length || !$form.length || !$container.length) {
            return;
        }

        // Hide options container until availability is checked
        if (!$container.children().length) {
            $container.hide();
        }

        // Handle Check Availability button click
        $btn.off('click.haCheck').on('click.haCheck', function (e) {
            e.preventDefault();

            const $dateInput = $form.find('#ha-date-visit');
            const dateVal = ($dateInput.val() || '').trim();
            const $error = $form.find('.ha-form-error');

            $error.hide().text('');

            // Validate date
            if (!dateVal) {
                $error.text('Please select a date for your tour.').stop(true, true).slideDown(200);
                if ($dateInput.length) {
                    $dateInput.focus();
                }
                return;
            }

            // Check if selected date is in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(dateVal);
            selectedDate.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                $error.text('Please select today or a future date.').stop(true, true).slideDown(200);
                return;
            }

            // Validate guests
            const $adultsInput = $form.find('#ha-adults');
            const $childrenInput = $form.find('#ha-children');
            const adults = parseInt($adultsInput.val(), 10) || 0;
            const children = parseInt($childrenInput.val(), 10) || 0;
            const totalPax = adults + children;

            if (adults < 1) {
                $error.text('At least 1 adult is required.').stop(true, true).slideDown(200);
                return;
            }

            if (totalPax > 50) {
                $error.text('The total number of guests cannot exceed 50.').stop(true, true).slideDown(200);
                return;
            }

            // Guard against duplicate in-flight requests
            if ($btn.hasClass('is-loading')) return;

            const originalHtml = $btn.html();
            $btn.addClass('is-loading').prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width:1rem;height:1rem;border-width:2px;display:inline-block;vertical-align:text-bottom;border-radius:50%;border-style:solid;border-color:currentColor transparent currentColor currentColor;animation:ha-spin .75s linear infinite;"></span><span>Checking...</span>');

            const postId = $container.data('post-id');
            const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.php_data && window.php_data.tour_options_nonce) ? window.php_data.tour_options_nonce : '';

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ha_ajax_check_availability',
                    nonce: nonce,
                    post_id: postId,
                    date: dateVal,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    $btn.removeClass('is-loading').prop('disabled', false).html(originalHtml);

                    if (response && response.success) {
                        if (response.data && response.data.count > 0) {
                            $container.html(response.data.html).slideDown(400, function () {
                                const targetOffset = $container.offset().top - 90;
                                $('html, body').animate({
                                    scrollTop: targetOffset > 0 ? targetOffset : 0
                                }, 500);
                            });
                        } else {
                            $container.html(
                                '<section class="ha-section ha-tour-options">' +
                                '  <div class="container">' +
                                '    <div class="ha-tour-options-empty" style="padding:48px 24px;text-align:center;background:#fff;border-radius:16px;border:1px solid #e7e5e4;box-shadow:0 4px 16px rgba(25,35,43,0.04);">' +
                                '      <h3 class="h4" style="color:#19232B;margin-bottom:8px;">No Options Available</h3>' +
                                '      <p style="color:#555;margin:0;">No tour options found for the selected date. Please choose another date or contact our team directly for custom arrangements.</p>' +
                                '    </div>' +
                                '  </div>' +
                                '</section>'
                            ).slideDown(400, function () {
                                const targetOffset = $container.offset().top - 90;
                                $('html, body').animate({
                                    scrollTop: targetOffset > 0 ? targetOffset : 0
                                }, 500);
                            });
                        }
                    } else {
                        const message = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'Unable to check availability. Please try again.';
                        $error.text(message).stop(true, true).slideDown(200);
                    }
                },
                error: function () {
                    $btn.removeClass('is-loading').prop('disabled', false).html(originalHtml);
                    $error.text('Server connection error. Please try again later.').stop(true, true).slideDown(200);
                }
            });
        });

        // Option item selection toggle
        $container.off('click.haSelectOption').on('click.haSelectOption', '.option-item', function (e) {
            // Ignore click if user clicked active button or read-more link directly
            if ($(e.target).closest('.btn-select--active').length || $(e.target).closest('.option-item__read-more').length) {
                return;
            }

            $container.find('.option-item').removeClass('is-selected');
            $container.find('.btn-select').removeClass('btn-select--active').text('Select');

            $(this).addClass('is-selected');
            $(this).find('.btn-select').addClass('btn-select--active').text('Continue');
        });

        // Read more / read less description toggle
        $container.off('click.haReadMore').on('click.haReadMore', '.option-item__read-more', function (e) {
            e.preventDefault();
            const $desc = $(this).closest('.option-item__desc');
            $desc.toggleClass('is-expanded');
            $(this).text($desc.hasClass('is-expanded') ? 'Read less' : 'Read more');
        });

        // Continue / Select button click to start checkout session
        $container.off('click.haProceedBooking').on('click.haProceedBooking', '.btn-select', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $thisBtn = $(this);
            const $item = $thisBtn.closest('.option-item');

            // If this card is not active yet, activate it first
            if (!$thisBtn.hasClass('btn-select--active')) {
                $container.find('.option-item').removeClass('is-selected');
                $container.find('.btn-select').removeClass('btn-select--active').text('Select');
                $item.addClass('is-selected');
                $thisBtn.addClass('btn-select--active').text('Continue');
                return;
            }

            if ($thisBtn.hasClass('is-loading')) return;

            const originalText = $thisBtn.text();
            $thisBtn.addClass('is-loading').prop('disabled', true).text('Processing...');

            const optionId = ($thisBtn.data('option-id') !== undefined) ? $thisBtn.data('option-id') : $item.data('key');
            const postId = $thisBtn.data('tour-id') || $container.data('post-id');
            const dateVal = ($form.find('#ha-date-visit').val() || '').trim();
            const adults = parseInt($form.find('#ha-adults').val(), 10) || 2;
            const children = parseInt($form.find('#ha-children').val(), 10) || 0;
            const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.php_data && window.php_data.tour_options_nonce) ? window.php_data.tour_options_nonce : '';

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ha_ajax_process_booking',
                    nonce: nonce,
                    post_id: postId,
                    option_id: optionId,
                    date: dateVal,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    if (response && response.success && response.data && response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        $thisBtn.removeClass('is-loading').prop('disabled', false).text(originalText);
                        const msg = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'An error occurred while preparing your booking. Please try again.';
                        alert(msg);
                    }
                },
                error: function () {
                    $thisBtn.removeClass('is-loading').prop('disabled', false).text(originalText);
                    alert('Server error. Please check your connection and try again.');
                }
            });
        });
    };

    // Auto-initialize when DOM is ready
    $(document).ready(function () {
        initQuantitySelectors();
        initCheckAvailability();
    });

})(jQuery);

