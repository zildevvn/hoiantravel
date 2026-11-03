<?php
$counters = get_field('counters_list_hp');
?>

<?php if (!empty($counters)): ?>
    <section class="vm-section counters-section">
        <div class="container">
            <div class="vm-counters">
                <?php foreach ($counters as $key => $counter): ?>
                    <div class="counter-item">
                        <div class="counter-item__icon">
                            <img src="<?= $counter['icon'] ?>" alt="icon for <?= $counter['label'] ?>" width="50" height="50" />
                        </div>

                        <div class="counter-item-content">
                            <h2 class="h6"> <?= $counter['label'] ?> </h2>
                            <p class="vm-counter mb-0 h2">
                                <?= $counter['number'] ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>