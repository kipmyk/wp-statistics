<div class="wps-card wps-card__sums">
    <div class="wps-card__title">
        <h2>
            <?php echo esc_html($title) ?>
            <?php if(isset($tooltip)): ?>
                <span class="wps-tooltip" title="<?php echo esc_attr($tooltip); ?>"><i class="wps-tooltip-icon info"></i></span>
            <?php endif?>
        </h2>
    </div>
    <div class="wps-card__summary">
        <div class="wps-card__summary--title">
            <span><?php echo $total ?></span>
            <span><?php esc_html_e('Total', 'wp-statistics') ?></span>
        </div>
        <div class="wps-card__summary--avg">
            <?php if(isset($avg)):  ?>
                <span><?php echo $avg ?></span>
            <?php endif?>

            <?php if(isset($avg_title)): ?>
                <span><?php echo $avg_title ?></span>
            <?php endif?>
        </div>
    </div>
</div>