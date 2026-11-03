<?php
$itinerary_tour = get_field('itinerary_tour');
$time_line = $itinerary_tour['itinerary_list'];
?>

<div id="tour-itinerary" class="tour-itinerary">
    <h2 class="vm-heading ">Itinerary</h2>
    <?php if (!empty($itinerary_tour['itinerary_desc'])): ?>
        <div class="tour-itinerary__desc">
            <?= $itinerary_tour['itinerary_desc']; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($time_line)): ?>
        <div class="tour-itinerary__list">
            <?php foreach ($time_line as $index => $item): ?>
                <div class="itinerary-item">
                    <?php
                    $time = $item['time'] ?? '';
                    $title = $item['title'] ?? '';
                    $description = $item['description'] ?? '';
                    ?>

                    <div class="itinerary-item__time-wrapper">
                        <?php if ($time): ?>
                            <div class="itinerary-item__time">
                                <?= esc_html($time); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="itinerary-item__divider">
                        <div class="itinerary-item__dot"></div>
                        <div class="itinerary-item__line"></div>
                    </div>
                    <div class="itinerary-item__content">
                        <h3 class="itinerary-item__title">
                            <?= esc_html($title); ?>
                        </h3>
                        <?php if ($description): ?>
                            <div class="itinerary-item__desc">
                                <?= wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($itinerary_tour['img'])): ?>
        <img src="<?= $itinerary_tour['img'] ?>" alt="image Itinerary for <?= the_title() ?>" />
    <?php endif; ?>
</div>