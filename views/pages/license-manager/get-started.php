<?php

use WP_Statistics\Components\View;

?>

<div class="wps-wrap__main">
    <div class="wp-header-end"></div>
    <div class="wps-postbox-addon__step">
        <div class="wps-addon__step__info">
            <span class="wps-addon__step__image wps-addon__step__image--checked"></span>
            <h2 class="wps-addon__step__title"><?php esc_html_e('You\'re All Set! Your License is Successfully Activated!', 'wp-statistics'); ?></h2>
            <p class="wps-addon__step__desc"><?php esc_html_e('Choose the add-ons you want to install. You can modify your selection later.', 'wp-statistics'); ?></p>
        </div>
        <div class="wps-addon__step__download">
            <div class="wps-addon__download__title">
                <h3>
                    <?php esc_html_e('Select Your Add-ons', 'wp-statistics'); ?>
                </h3>
                <a href="" class="wps-addon__download_select-all"><?php esc_html_e('Active All', 'wp-statistics'); ?></a>
            </div>
            <div class="wps-addon__download__items">
                <?php
                $args = [
                    'title'       => esc_html__('Data Plus', 'wp-statistics'),
                    'icon'        => esc_url(WP_STATISTICS_URL . 'assets/images/add-ons/wp-statistics-data-plus.svg'),
                    'active_link' => '',
                    'description' => esc_html__('Track custom post types, taxonomies, links, and download tracker.', 'wp-statistics'),
                ];
                View::load('components/addon-active-card', $args);

                $args = [
                    'title'        => esc_html__('REST API', 'wp-statistics'),
                    'icon'         => esc_url(WP_STATISTICS_URL . 'assets/images/add-ons/wp-statistics-mini-chart.svg'),
                    'status_text'  => esc_html__('Failed', 'wp-statistics'),
                    'status_class' => 'danger',
                    'retry_link'   => '',
                    'description'  => esc_html__('Enable WP Statistics endpoints in the REST API for advanced data retrieval.', 'wp-statistics'),
                ];
                View::load('components/addon-active-card', $args);

                $args = [
                    'title'        => esc_html__('REST API', 'wp-statistics'),
                    'icon'         => esc_url(WP_STATISTICS_URL . 'assets/images/add-ons/wp-statistics-mini-chart.svg'),
                    'status_text'  => esc_html__('Downloading', 'wp-statistics'),
                    'status_class' => 'purple',
                    'description'  => esc_html__('Enable WP Statistics endpoints in the REST API for advanced data retrieval.', 'wp-statistics'),
                ];
                View::load('components/addon-active-card', $args);

                $args = [
                    'title'              => esc_html__('REST API', 'wp-statistics'),
                    'icon'               => esc_url(WP_STATISTICS_URL . 'assets/images/add-ons/wp-statistics-mini-chart.svg'),
                    'status_text'        => esc_html__('Activated', 'wp-statistics'),
                    'status_class'       => 'success',
                    'setting_link'       => '#',
                    'detail_link'        => '#',
                    'change_log_link'    => '#',
                    'documentation_link' => '#',
                    'description'        => esc_html__('Enable WP Statistics endpoints in the REST API for advanced data retrieval.', 'wp-statistics'),
                ];
                View::load('components/addon-active-card', $args);
                ?>
            </div>
        </div>
        <div class="wps-review_premium">
            <div>
                <div class="wps-review_premium__content">
                    <h4><?php esc_html_e('Love WP Statistics Premium? Let Us Know!', 'wp-statistics'); ?></h4>
                    <p><?php esc_html_e('Thanks for choosing WP Statistics Premium! If you’re enjoying the new features, please leave us a 5-star review. Your feedback helps us improve!', 'wp-statistics'); ?></p>
                    <p><?php esc_html_e('Thanks for being part of our community!', 'wp-statistics'); ?></p>
                </div>
                <div class="wps-review_premium__actions">
                    <a href="" class="wps-review_premium__actions__review-btn"><?php esc_html_e('Write a Review', 'wp-statistics'); ?></a>
                    <a href="" class="wps-review_premium__actions__overview-btn"><?php esc_html_e('No, Take me to the Overview', 'wp-statistics'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
