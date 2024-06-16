<?php

namespace WP_Statistics\Service\Admin\Devices\Views;

use WP_Statistics\Abstracts\BaseView;
use WP_STATISTICS\Admin_Template;
use WP_Statistics\Exception\SystemErrorException;
use WP_STATISTICS\Menus;
use WP_Statistics\Service\Admin\Devices\DevicesDataProvider;
use WP_Statistics\Utils\Request;

class SinglePlatformView extends BaseView
{
    public function __construct()
    {
        if (!Request::has('platform')) {
            throw new SystemErrorException(
                esc_html__('Invalid platform provided!', 'wp-statistics')
            );
        }

        $this->dataProvider = new DevicesDataProvider([
            'date'     => [
                'from' => Request::get('from', date('Y-m-d', strtotime('-1 month'))),
                'to'   => Request::get('to', date('Y-m-d')),
            ],
            'per_page' => Admin_Template::$item_per_page,
            'page'     => Admin_Template::getCurrentPaged()
        ]);
    }

    public function render()
    {
        $args = [
            'title'           => sprintf(esc_html__('%s Report', 'wp-statistics'), Request::get('platform')),
            'backTitle'       => esc_html__('Devices', 'wp-statistics'),
            'backUrl'         => Menus::admin_url('devices', ['tab' => 'platforms']),
            'tooltip'         => esc_html__('Tooltip', 'wp-statistics'),
            'firstColTitle'   => esc_html__('Version', 'wp-statistics'),
            'firstColTooltip' => esc_html__('Version Tooltip', 'wp-statistics'),
            'data'            => $this->dataProvider->getSinglePlatformData(Request::get('platform')),
        ];

        Admin_Template::get_template(['layout/header', 'layout/title', 'pages/devices/single-locked', 'layout/footer'], $args);
    }
}
