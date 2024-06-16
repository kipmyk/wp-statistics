<?php

use WP_STATISTICS\Menus;
use WP_STATISTICS\UserAgent;

?>
<div class="postbox-container wps-postbox-full">
    <div class="metabox-holder">
        <div class="meta-box-sortables">
            <div class="postbox">
                <div class="inside">
                    <?php if (!empty($data['visitors'])) : ?>
                        <div class="o-table-wrapper">
                            <table width="100%" class="o-table wps-new-table">
                                <thead>
                                    <tr>
                                        <th class="wps-pd-l">
                                            <?php esc_html_e('OS', 'wp-statistics'); ?>
                                            <span class="wps-tooltip" title="<?php esc_html_e('OS Tooltip', 'wp-statistics'); ?>"><i class="wps-tooltip-icon info"></i></span>
                                        </th>
                                        <th class="wps-pd-l">
                                            <?php esc_html_e('Visitor Count', 'wp-statistics'); ?>
                                            <span class="wps-tooltip" title="<?php esc_html_e('Visitor Count Tooltip', 'wp-statistics'); ?>"><i class="wps-tooltip-icon info"></i></span>
                                        </th>
                                        <th class="wps-pd-l">
                                            <?php esc_html_e('Percent Share', 'wp-statistics'); ?>
                                            <span class="wps-tooltip" title="<?php esc_html_e('Percent Share Tooltip', 'wp-statistics'); ?>"><i class="wps-tooltip-icon info"></i></span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($data['visitors'] as $item) : ?>
                                        <tr>
                                            <td class="wps-pd-l">
                                                <span title="<?php echo esc_attr($item->platform); ?>" class="wps-platform-name">
                                                    <img alt="<?php echo esc_attr($item->platform); ?>" src="<?php echo esc_url(UserAgent::getPlatformLogo($item->platform)); ?>" title="<?php echo esc_attr($item->platform); ?>" class="log-tools wps-flag" />
                                                    <?php echo esc_html($item->platform); ?>
                                                </span>
                                            </td>
                                            <td class="wps-pd-l">
                                                <?php echo intval($item->views); ?>
                                            </td>
                                            <td class="wps-pd-l">
                                                <?php echo number_format((intval($item->views) / intval($data['total']->views_sum)) * 100, 2); ?>%
                                            </td>
                                            <td class="view-more">
                                                <a href="<?php echo esc_url(Menus::admin_url('devices', ['type' => 'single-platform', 'platform' => $item->platform])); ?>" title="<?php esc_html_e('View Details', 'wp-statistics'); ?>">
                                                    <?php esc_html_e('View Details', 'wp-statistics'); ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="o-wrap o-wrap--no-data wps-center">
                            <?php esc_html_e('No recent data available.', 'wp-statistics'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php echo isset($pagination) ? $pagination : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
            </div>
        </div>
    </div>
</div>