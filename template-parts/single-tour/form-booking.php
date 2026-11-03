<?php
$price_tour = get_field('price_tour');
$price_group = get_field('price_for_group_tour');
?>
<div id="form-booking-tour" class="vm-form-booking">
    <div class="vm-form-booking__header">
        <div class="price">
            <span class="label">FROM</span>
            <span class="value">
                <span class="mb-0">₫</span><?php echo number_format((float) ($price_tour ? $price_tour : 100)); ?>
            </span>
            <?php
            $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
            $is_private_only = empty($price_group) && !empty($price_private);
            if (!$is_private_only && !$is_car_tour):
                ?>
                <span class="unit">/ person</span>
            <?php endif; ?>
        </div>
        <div class="rating">
            <?php if (function_exists('kk_star_ratings')): ?>
                <span class="text">
                    <?php echo kk_star_ratings(); ?>
                </span>
            <?php else: ?>
                <span class="stars">★★★★★</span>
                <?php
                $post_id = get_the_ID();
                $views = function_exists('vm_get_post_views') ? vm_get_post_views($post_id) : 0;
                $display_views = $views > 0 ? number_format($views) : 128;
                ?>
                <span class="text">4.9 (
                    <?php echo esc_html($display_views); ?> reviews)
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="vm-form-booking__body">
        <div class="form-group">
            <label>Date Visit</label>
            <input type="date" id="vm-date-visit" name="date_visit" class="form-control"
                value="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                min="<?php echo esc_attr(current_time('Y-m-d')); ?>">
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label>Adults</label>
                <div class="quantity-selector">
                    <button class="qty-btn">-</button>
                    <input type="number" value="2" min="1">
                    <button class="qty-btn">+</button>
                </div>
            </div>
            <div class="form-group half">
                <label>Children <small>(4-11 yrs)</small></label>
                <div class="quantity-selector">
                    <button class="qty-btn">-</button>
                    <input type="number" value="0" min="0">
                    <button class="qty-btn">+</button>
                </div>
            </div>
        </div>

        <div class="vm-form-error"
            style="display: none; color: #dc3545; font-size: 13px; font-weight: 500; margin-bottom: 15px; padding: 10px; background-color: #f8d7da; border-radius: 6px; border: 1px solid #f5c6cb;">
        </div>
        <button id="vm-btn-check-availability" class="vm-button d-flex align-items-center justify-content-center">
            Check Availability

            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                color="#000000">
                <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>

    <div class="vm-form-booking__footer">
        <div class="feature-line">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            Free cancellation up to 24 hours
        </div>
        <div class="feature-line">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Instant confirmation
        </div>
    </div>
</div>