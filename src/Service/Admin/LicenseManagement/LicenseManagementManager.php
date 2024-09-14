<?php

namespace WP_Statistics\Service\Admin\LicenseManagement;

use Exception;
use WP_Statistics\Components\Assets;
use WP_STATISTICS\Menus;
use WP_Statistics\Utils\Request;

class LicenseManagementManager
{
    public function __construct()
    {
        add_filter('wp_statistics_admin_menu_list', [$this, 'addMenuItem']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_filter('wp_statistics_ajax_list', [$this, 'registerAjaxCallbacks']);
    }

    /**
     * Adds menu item.
     *
     * @param array $items
     *
     * @return array
     *
     * @hooked filter: `wp_statistics_admin_menu_list` - 10
     */
    public function addMenuItem($items)
    {
        $items['plugins'] = [
            'sub'      => 'overview',
            'title'    => __('Add-Ons', 'wp-statistics'),
            'name'     => '<span class="wps-text-warning">' . __('Add-Ons', 'wp-statistics') . '</span>',
            'page_url' => 'plugins',
            'callback' => LicenseManagerPage::class,
            'priority' => 90,
            'break'    => true,
        ];

        return $items;
    }

    /**
     * Enqueues admin scripts.
     *
     * @return void
     *
     * @hooked action: `admin_enqueue_scripts` - 10
     */
    public function enqueueScripts()
    {
        if (Menus::in_page('plugins')) {
            Assets::script('license-manager', 'js/license-manager.js', ['jquery'], [
                'ajaxUrl' => admin_url('admin-ajax.php?nonce=' . wp_create_nonce('wp_statistics_license_manager')),
            ], true);
        }
    }

    /**
     * Registers AJAX actions and callbacks.
     *
     * @param array $list
     *
     * @return array
     *
     * @hooked filter: `wp_statistics_ajax_list` - 10
     */
    public function registerAjaxCallbacks($list)
    {
        $list[] = [
            'class'  => $this,
            'action' => 'check_license',
        ];
        $list[] = [
            'class'  => $this,
            'action' => 'download_plugin',
        ];

        return $list;
    }

    /**
     * Handles `check_license` ajax call and checks license status with try/catch.
     *
     * @return void
     */
    public function check_license_action_callback()
    {
        try {
            if (!wp_verify_nonce(wp_unslash(Request::get('nonce')), 'wp_statistics_license_manager')) {
                throw new Exception(__('Access denied.', 'wp-statistics'));
            }

            $licenseKey = Request::has('license') ? wp_unslash(Request::get('license')) : '';

            if (empty($licenseKey)) {
                throw new Exception(__('License key is missing.', 'wp-statistics'));
            }

            // @todo review
            $licenseValidator = new LicenseValidator();
            $licenses         = $licenseValidator->validateLicense($licenseKey);

            wp_send_json_success([
                'licenses' => $licenses,
            ]);

        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }

        exit;
    }

    /**
     * Handles `download_plugin` ajax call and downloads a plugin with try/catch.
     *
     * @return void
     */
    public function download_plugin_action_callback()
    {
        try {
            if (!wp_verify_nonce(wp_unslash(Request::get('nonce')), 'wp_statistics_license_manager')) {
                throw new Exception(__('Access denied.', 'wp-statistics'));
            }

            // Get the download URL and plugin slug from the request
            $pluginUrl  = Request::has('download_url') ? wp_unslash(Request::get('download_url')) : '';
            $pluginSlug = Request::has('plugin_slug') ? wp_unslash(Request::get('plugin_slug')) : '';

            if (empty($pluginUrl) || empty($pluginSlug)) {
                throw new Exception(__('Missing plugin URL or slug.', 'wp-statistics'));
            }

            // Instantiate the PluginInstaller class
            $installer = new PluginInstaller($pluginUrl, $pluginSlug);
            $installer->downloadAndInstallPlugin();
            $installer->activatePlugin();

            // Respond with success
            wp_send_json_success([
                'message' => __('Plugin downloaded, installed, and activated successfully!', 'wp-statistics'),
            ]);

        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
            ]);
        }

        exit;
    }
}
