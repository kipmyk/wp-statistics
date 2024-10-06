<?php

namespace WP_Statistics\Service\Admin\Referrals;

use WP_STATISTICS\Menus;
use WP_STATISTICS\Option;
use WP_STATISTICS\User;
use WP_Statistics\Utils\Request;
use WP_Statistics\Models\VisitorsModel;
use WP_Statistics\Service\Analytics\VisitorProfile;

class ReferralsManager
{

    public function __construct()
    {
        add_filter('wp_statistics_admin_menu_list', [$this, 'addMenuItem']);
        add_filter('wp_statistics_visitor_data_before_update', [$this, 'handleLastTouchAttributionModel'], 10, 2);
        add_filter('wp_statistics_ajax_list', [$this, 'registerAjaxActions']);
    }

    /**
     * Updates visitor data based on the last touch attribution model, when user is coming from external sources.
     *
     * @param array $data Visitor data to be updated.
     * @param VisitorProfile $visitorProfile Visitor profile object.
     *
     * @return array Updated visitor data.
     */
    public function handleLastTouchAttributionModel($data, $visitorProfile)
    {
        // Update Visitor source info if attribution model is last touch
        if (Option::get('attribution_model') === 'last-touch') {
            // If visitor is referred from external sources, update referrals info
            if ($visitorProfile->isReferred()) {
                $data['referred']       = $visitorProfile->getReferrer();
                $data['source_channel'] = $visitorProfile->getSource()->getChannel();
                $data['source_name']    = $visitorProfile->getSource()->getName();
            }
        }

        return $data;
    }

    /**
     * Add menu item
     *
     * @param array $items
     * @return array
     */
    public function addMenuItem($items)
    {
        $items['referrals'] = [
            'sub'       => 'overview',
            'title'     => esc_html__('Referrals', 'wp-statistics'),
            'page_url'  => 'referrals',
            'callback'  => ReferralsPage::class,
            'priority'  => 27
        ];

        return $items;
    }

    /**
     * Registers AJAX actions for the referrals tab.
     *
     * @param array $list List of AJAX actions.
     *
     * @return array List of AJAX actions.
     */
    public function registerAjaxActions($list)
    {
        $list[] = [
            'class'     => $this,
            'action'    => 'searchReferrers',
            'public'    => false
        ];

        return $list;
    }

    /**
     * Handles the AJAX action for searching referrers in the referrals table.
     *
     * @since 14.0.4
     *
     * @ignore
     */
    public function searchReferrers_action_callback()
    {
        if (Request::isFrom('ajax') && User::Access('read')) {
            check_ajax_referer('wp_rest', 'wps_nonce');

            $results = [];
            $search  = Request::get('search', '');

            $visitorsModel = new VisitorsModel();
            $referrers  = $visitorsModel->getReferrers([
                'referrer'      => $search,
                'ignore_date'   => true
            ]);

            foreach ($referrers as $referrer) {
                $option = [
                    'id'   => Menus::admin_url('referrals', ['referrer' => $referrer->referrer]),
                    'text' => $referrer->referrer
                ];

                $results[] = $option;
            }

            wp_send_json(['results' => $results]);
        }

        exit;
    }
}
