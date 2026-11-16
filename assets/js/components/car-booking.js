/**
 * Car Booking & Vehicle Capacity Component
 * Single Car Page - Hoi An Travel
 *
 * Handles:
 * 1. Passenger counter for Adults & Children with live capacity indicator.
 * 2. Real-time client-side option filtering based on $total_passengers <= type.
 * 3. AJAX availability checking & rendering of vehicle options.
 * 4. Option selection and booking session redirect to Checkout.
 *
 * Strict Business Rules:
 * - Total passengers = Adults + Children.
 * - Vehicle capacity is strictly determined by option 'type' (4: Sedan, 7: SUV, 16: Van).
 * - Price is fixed per vehicle/option, NEVER multiplied by passenger count.
 */

(function ($) {
    'use strict';

    /**
     * Quantity selectors specifically for Car Booking
     */
    const initCarQuantitySelectors = () => {
        const $selectors = $('.ha-car-qty-selector');
        if (!$selectors.length) return;

        const updatePassengerCount = () => {
            const adults = parseInt($('#ha-car-adults').val(), 10) || 0;
            const children = parseInt($('#ha-car-children').val(), 10) || 0;
            const total = adults + children;

            $('#ha-car-total-pax-display').text(total);

            // Live update rendered options container if already visible
            updateRenderedCarOptions(total);
        };

        $selectors.off('click.haCarQty').on('click.haCarQty', '.qty-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const $input = $btn.siblings('input[type="number"]');
            if (!$input.length) return;

            let val = parseInt($input.val(), 10);
            if (isNaN(val)) val = 0;

            const min = parseInt($input.attr('min'), 10) || 0;
            const max = parseInt($input.attr('max'), 10) || 16;

            if ($btn.hasClass('qty-btn--plus') || $btn.text().trim() === '+') {
                if (val < max) val++;
            } else if ($btn.hasClass('qty-btn--minus') || $btn.text().trim() === '-') {
                if (val > min) val--;
            }

            $input.val(val).trigger('change');
            updatePassengerCount();
        });

        // Initialize passenger display on load
        updatePassengerCount();
    };

    /**
     * Live client-side update of rendered car option cards when passenger count changes
     */
    const updateRenderedCarOptions = (totalPax) => {
        const $container = $('#ha-car-options-container');
        if (!$container.length || !$container.is(':visible')) return;

        let suitableCount = 0;
        let selectedBecameUnfit = false;

        $container.find('.car-option-item').each(function () {
            const $card = $(this);
            const capacity = parseInt($card.data('capacity'), 10) || 0;
            const $btn = $card.find('.btn-select-car');

            if (totalPax <= capacity) {
                // Fit: Show option completely in the UI
                suitableCount++;
                $card.removeClass('is-unfit is-disabled').data('fit', '1').show();
                $btn.prop('disabled', false).removeClass('btn-select--disabled');
                if ($card.hasClass('is-selected')) {
                    $btn.addClass('btn-select--active').text('Continue');
                } else {
                    $btn.removeClass('btn-select--active').text('Select');
                }
            } else {
                // Unfit: Completely hide option from UI, clear selected state if was selected
                if ($card.hasClass('is-selected')) {
                    selectedBecameUnfit = true;
                    $card.removeClass('is-selected');
                    $btn.removeClass('btn-select--active').text('Select');
                }
                $card.addClass('is-unfit is-disabled').data('fit', '0').hide();
                $btn.prop('disabled', true).addClass('btn-select--disabled').removeClass('btn-select--active');
            }
        });

        const $grid = $container.find('.ha-car-options__grid');
        const $emptyState = $container.find('.ha-car-options-empty');
        const $title = $container.find('.ha-car-options__title');
        const $subtitle = $container.find('.ha-car-options__subtitle');
        const $badge = $container.find('.ha-car-options__badge');
        const $selectionNotice = $container.find('.ha-car-selection-notice');

        const adults = parseInt($('#ha-car-adults').val(), 10) || 0;
        const children = parseInt($('#ha-car-children').val(), 10) || 0;

        if (suitableCount > 0) {
            // Suitable options exist
            $emptyState.hide();
            $grid.show();
            if ($badge.length) $badge.show();

            const vehicleText = suitableCount === 1 ? '1 suitable vehicle' : suitableCount + ' suitable vehicles';
            $title.text('Choose from ' + vehicleText + ' for ' + totalPax + ' passengers');
            $subtitle.text('Selected party: ' + adults + ' Adults, ' + children + ' Children (' + totalPax + ' total seats required). Price is fixed per vehicle.');

            // If the previously selected option became unfit, prompt customer to choose an available vehicle
            if (selectedBecameUnfit) {
                if ($selectionNotice.length) {
                    $selectionNotice.stop(true, true).slideDown(200);
                }
            } else {
                if ($selectionNotice.length) {
                    $selectionNotice.stop(true, true).slideUp(200);
                }
            }
        } else {
            // No suitable option fits the passenger group size (e.g. > 16 pax)
            $grid.hide();
            if ($badge.length) $badge.hide();
            if ($selectionNotice.length) $selectionNotice.hide();

            $title.text('No Vehicles Available for Your Group Size');
            $subtitle.text('Selected party: ' + adults + ' Adults, ' + children + ' Children (' + totalPax + ' total seats required).');

            $emptyState.find('.ha-car-options-empty__title').text('Group Size (' + totalPax + ' Passengers) Exceeds Single Vehicle Capacity');
            $emptyState.stop(true, true).slideDown(300);
        }
    };

    /**
     * Check Availability & Car Options Controller
     */
    const initCheckCarAvailability = () => {
        const $btn = $('#ha-btn-check-car-availability');
        const $form = $('#form-booking-car');
        const $container = $('#ha-car-options-container');

        if (!$btn.length || !$form.length || !$container.length) {
            return;
        }

        // Hide options container initially if empty
        if (!$container.children().length) {
            $container.hide();
        }

        $btn.off('click.haCarCheck').on('click.haCarCheck', function (e) {
            e.preventDefault();

            const $dateInput = $form.find('#ha-car-date-visit');
            const dateVal = ($dateInput.val() || '').trim();
            const $error = $form.find('.ha-car-form-error');

            $error.hide().text('');

            // 1. Date validation
            if (!dateVal) {
                $error.text('Please select a date for your transfer.').stop(true, true).slideDown(200);
                if ($dateInput.length) $dateInput.focus();
                return;
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(dateVal);
            selectedDate.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                $error.text('Please select today or a future date for your transfer.').stop(true, true).slideDown(200);
                return;
            }

            // 2. Passengers validation
            const adults = parseInt($form.find('#ha-car-adults').val(), 10) || 0;
            const children = parseInt($form.find('#ha-car-children').val(), 10) || 0;
            const totalPax = adults + children;

            if (adults < 1) {
                $error.text('At least 1 adult passenger is required.').stop(true, true).slideDown(200);
                return;
            }

            if (totalPax < 1) {
                $error.text('Please specify at least 1 passenger.').stop(true, true).slideDown(200);
                return;
            }

            // Guard against duplicate in-flight requests
            if ($btn.hasClass('is-loading')) return;

            const originalHtml = $btn.html();
            $btn.addClass('is-loading').prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="width:1rem;height:1rem;border-width:2px;display:inline-block;vertical-align:text-bottom;border-radius:50%;border-style:solid;border-color:currentColor transparent currentColor currentColor;animation:ha-spin .75s linear infinite;"></span><span>Checking Vehicles...</span>');

            const postId = $container.data('post-id');
            const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.php_data && window.php_data.tour_options_nonce) ? window.php_data.tour_options_nonce : '';

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ha_ajax_check_car_availability',
                    nonce: nonce,
                    post_id: postId,
                    date: dateVal,
                    adults: adults,
                    children: children
                },
                success: function (response) {
                    $btn.removeClass('is-loading').prop('disabled', false).html(originalHtml);

                    if (response && response.success && response.data) {
                        $container.html(response.data.html).slideDown(400, function () {
                            const targetOffset = $container.offset().top - 90;
                            $('html, body').animate({
                                scrollTop: targetOffset > 0 ? targetOffset : 0
                            }, 500);
                        });
                    } else {
                        const msg = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'Unable to check vehicle options. Please try again.';
                        $error.text(msg).stop(true, true).slideDown(200);
                    }
                },
                error: function () {
                    $btn.removeClass('is-loading').prop('disabled', false).html(originalHtml);
                    $error.text('Server connection error. Please try again later.').stop(true, true).slideDown(200);
                }
            });
        });

        // Option card selection
        $container.off('click.haSelectCarOption').on('click.haSelectCarOption', '.car-option-item', function (e) {
            const $card = $(this);

            // Prevent selection if vehicle does not fit group size or is hidden
            if ($card.hasClass('is-disabled') || $card.hasClass('is-unfit') || $card.is(':hidden')) {
                return;
            }

            // Ignore click if user clicked directly on active button
            if ($(e.target).closest('.btn-select--active').length) {
                return;
            }

            $container.find('.car-option-item').removeClass('is-selected');
            $container.find('.btn-select-car:not(.btn-select--disabled)').removeClass('btn-select--active').text('Select');

            $card.addClass('is-selected');
            $card.find('.btn-select-car').addClass('btn-select--active').text('Continue');

            // Hide the re-selection notice once customer picked an available option
            $container.find('.ha-car-selection-notice').stop(true, true).slideUp(200);
        });

        // Click Select / Continue on Car Option to proceed to Checkout
        $container.off('click.haProceedCarBooking').on('click.haProceedCarBooking', '.btn-select-car', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $thisBtn = $(this);
            const $card = $thisBtn.closest('.car-option-item');

            if ($thisBtn.prop('disabled') || $thisBtn.hasClass('btn-select--disabled') || $card.hasClass('is-disabled') || $card.hasClass('is-unfit') || $card.is(':hidden')) {
                return;
            }

            const dateVal = ($form.find('#ha-car-date-visit').val() || '').trim();
            if (!dateVal) {
                const $error = $form.find('.ha-car-form-error');
                $error.text('Please select a transfer date first.').stop(true, true).slideDown(200);
                $('html, body').animate({ scrollTop: $form.offset().top - 120 }, 400);
                return;
            }

            if ($thisBtn.hasClass('is-loading')) return;

            // Mark card as selected
            $container.find('.car-option-item').removeClass('is-selected');
            $container.find('.btn-select-car:not(.btn-select--disabled)').removeClass('btn-select--active').text('Select');
            $card.addClass('is-selected');

            $thisBtn.addClass('is-loading btn-select--active').prop('disabled', true).text('Processing...');

            const optionId = ($thisBtn.data('option-id') !== undefined) ? $thisBtn.data('option-id') : $card.data('key');
            const postId = $thisBtn.data('car-id') || $container.data('post-id');
            const adults = parseInt($form.find('#ha-car-adults').val(), 10) || 2;
            const children = parseInt($form.find('#ha-car-children').val(), 10) || 0;
            const ajaxUrl = (window.php_data && window.php_data.ajax_url) ? window.php_data.ajax_url : '/wp-admin/admin-ajax.php';
            const nonce = (window.php_data && window.php_data.tour_options_nonce) ? window.php_data.tour_options_nonce : '';

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ha_ajax_process_car_booking',
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
                        $thisBtn.removeClass('is-loading').prop('disabled', false).text('Select');
                        const msg = (response && response.data && response.data.message)
                            ? response.data.message
                            : 'An error occurred while preparing your car reservation. Please try again.';
                        alert(msg);
                    }
                },
                error: function () {
                    $thisBtn.removeClass('is-loading').prop('disabled', false).text('Select');
                    alert('Server error. Please check your connection and try again.');
                }
            });
        });
    };

    // Auto-initialize on DOM ready
    $(document).ready(function () {
        initCarQuantitySelectors();
        initCheckCarAvailability();
    });

})(jQuery);

