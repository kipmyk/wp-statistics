<?php

namespace WP_Statistics\Service\PrivacyAudit;

class PrivacyAuditManager
{

    public function __construct()
    {
        add_filter('wp_statistics_admin_menu_list', [$this, 'addMenuItem']);
        add_filter('wp_statistics_ajax_list', [$this, 'registerAjaxCallbacks']);
        add_action('admin_init', [$this, 'initPrivacyStatusOption']);
    }

    /**
     * Add privacy audit status options, if not already added.
     *
     * @return void
     */
    public function initPrivacyStatusOption()
    {
        PrivacyStatusOption::init();
    }

    /**
     * Add menu item
     *
     * @param array $items
     * @return array
     */
    public function addMenuItem($items)
    {
        $newItem = [
            'privacy_audit' => [
                'sub'      => 'overview',
                'title'    => esc_html__('Privacy Audit', 'wp-statistics'),
                'page_url' => 'privacy-audit',
                'callback' => PrivacyAuditPage::class,
            ]
        ];

        array_splice($items, 14, 0, $newItem);

        return $items;
    }

    /**
     * Add ajax actions
     *
     * @param array $list
     * @return array
     */
    public function registerAjaxCallbacks($list)
    {
        $privacyAuditController = new PrivacyAuditController();

        $list[] = [
            'class'   => $privacyAuditController,
            'action'  => 'getPrivacyStatus'
        ];

        $list[] = [
            'class'   => $privacyAuditController,
            'action'  => 'updatePrivacyStatus'
        ];

        return $list;
    }

}