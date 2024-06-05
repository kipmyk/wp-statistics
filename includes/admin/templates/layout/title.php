<div class="wps-wrap__top">
    <?php if (isset($backUrl, $backTitle)): ?>
        <a href="<?php echo esc_url($backUrl) ?>" title="<?php echo esc_html($backTitle) ?>" class="wps-previous-url"><?php echo esc_html($backTitle) ?></a>
    <?php endif ?>
    <h2 class="wps_title"><?php echo(isset($title) ? esc_attr($title) : (function_exists('get_admin_page_title') ? get_admin_page_title() : '')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	 ?>
        <?php if (!empty($tooltip)) : ?>
            <span class="wps-tooltip" title="<?php echo esc_attr($tooltip); ?>"><i class="wps-tooltip-icon info"></i></span>
        <?php endif; ?>
    </h2>    <?php do_action('wp_statistics_after_admin_page_title'); ?>
    <?php if (isset($real_time_button)): ?>
        <?php
        $is_realtime_active = \WP_STATISTICS\Helper::isAddOnActive('realtime-stats');
        $button_class = $is_realtime_active ? 'wps-realtime-btn' : 'wps-realtime-btn disabled';
        $button_href = $is_realtime_active ? esc_url(admin_url('admin.php?page=wp_statistics_realtime_stats')) : '';
        ?>
        <a class="<?php echo $button_class; ?>" href="<?php echo $button_href; ?>">
            <?php esc_html_e('Realtime', 'wp-statistics'); ?>
        </a>
    <?php endif; ?>
    <?php if (isset($Datepicker)): ?>
        <form class="wps-search-date wps-today-datepicker" method="get">
            <div>
                <input type="hidden" name="page" value="<?php echo esc_attr($pageName); ?>">
                <input class="wps-search-date__input wps-js-calendar-field" id="search-date-input" type="text" size="18" name="day" data-wps-date-picker="day" readonly value="<?php echo esc_attr($day); ?>" autocomplete="off" placeholder="YYYY-MM-DD" required>
            </div>
        </form>
    <?php endif ?>
    <?php if (isset($HasDateRang)): ?>
        <div class="wps-head-filters">
            <?php include 'date.range.php'; ?>
            <?php
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    require_once "filters/$filter-filter.php";
                }
            }
            ?>
        </div>
    <?php endif ?>
</div>
<div class="wps-wrap__main">
    <div class="wp-header-end"></div>