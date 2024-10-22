<?php

namespace WP_Statistics\Service\Admin\LicenseManagement\Views;

use Exception;
use WP_Statistics\Components\View;
use WP_Statistics\Utils\Request;
use WP_STATISTICS\Menus;
use WP_STATISTICS\Admin_Template;
use WP_Statistics\Abstracts\BaseTabView;
use WP_Statistics\Exception\SystemErrorException;
use WP_Statistics\Service\Admin\LicenseManagement\ApiCommunicator;
use WP_Statistics\Service\Admin\LicenseManagement\LicenseHelper;
use WP_Statistics\Service\Admin\NoticeHandler\Notice;
use WP_Statistics\Service\Admin\LicenseManagement\LicenseManagerDataProvider;

class TabsView extends BaseTabView
{
    protected $defaultTab = 'add-ons';
    protected $tabs = [
        'add-ons',
        'add-license',
        'downloads',
        'get-started',
    ];

    private $apiCommunicator;

    public function __construct()
    {
        $this->dataProvider    = new LicenseManagerDataProvider();
        $this->apiCommunicator = new ApiCommunicator();
        $this->handleUrlLicenseValidation();

        // Check licenses status on main add-on page
        if ($this->isTab('add-ons')) {
            LicenseHelper::checkLicensesStatus();
        }

        parent::__construct();
    }

    /**
     * Validate the license key sent via URL
     *
     * @return void
     */
    private function handleUrlLicenseValidation()
    {
        $license = Request::get('license_key');

        if (!empty($license)) {
            $this->apiCommunicator->validateLicense($license);
        }
    }

    /**
     * Returns the current tab to be displayed.
     *
     * @return string
     */
    protected function getCurrentTab()
    {
        $currentTab = Request::get('tab', $this->defaultTab);

        // If license key is sent via URL, redirect to "Downloads" tab
        if (in_array($currentTab, ['add-ons', 'add-license']) && Request::has('license_key')) {
            return 'downloads';
        }

        // If license key has not been found, prevent accessing certain tabs
        if (in_array($currentTab, ['downloads', 'get-started']) && !Request::has('license_key')) {
            return 'add-license';
        }

        return $currentTab;
    }

    /**
     * Returns data for "Add-Ons" tab.
     *
     * @return array
     */
    public function getAddOnsData()
    {
        return $this->dataProvider->getAddOnsData();
    }

    /**
     * Returns data for "Download Add-ons" tab.
     *
     * @return array
     */
    public function getDownloadsData()
    {
        return $this->dataProvider->getDownloadsData();
    }

    /**
     * Returns data for "Get Started" tab.
     *
     * @return array
     */
    public function getGetStartedData()
    {
        return $this->dataProvider->getGetStartedData();
    }

    public function render()
    {
        try {
            $currentTab = $this->getCurrentTab();
            $data       = $this->getTabData();

            $args = [
                'title'      => esc_html__('License Manager', 'wp-statistics'),
                'pageName'   => Menus::get_page_slug('plugins'),
                'custom_get' => ['tab' => $currentTab],
                'data'       => $data,
                'tabs'       => [
                    [
                        'link'  => Menus::admin_url('plugins', ['tab' => 'add-ons']),
                        'title' => esc_html__('Add-Ons', 'wp-statistics'),
                        'class' => $this->isTab('add-ons') ? 'current' : '',
                    ],
                    [
                        'link'  => Menus::admin_url('plugins', ['tab' => 'add-license']),
                        'title' => esc_html__('Add Your License', 'wp-statistics'),
                        'class' => $this->isTab('add-license') ? 'current' : '',
                    ],
                    [
                        'link'  => Menus::admin_url('plugins', ['tab' => 'downloads']),
                        'title' => esc_html__('Download Add-Ons', 'wp-statistics'),
                        'class' => $this->isTab('downloads') ? 'current' : '',
                    ],
                    [
                        'link'  => Menus::admin_url('plugins', ['tab' => 'get-started']),
                        'title' => esc_html__('Get Started', 'wp-statistics'),
                        'class' => $this->isTab('get-started') ? 'current' : '',
                    ],
                ]
            ];

            if ($this->isTab('add-ons')) {
                $args['title']                  = esc_html__('Add-Ons', 'wp-statistics');
                $args['install_addon_btn_txt']  = esc_html__('Install Add-On', 'wp-statistics');
                $args['install_addon_btn_link'] = esc_url(Menus::admin_url('plugins', ['tab' => 'add-license']));

                Admin_Template::get_template(['layout/header', 'layout/title'], $args);
            } else {
                Admin_Template::get_template(['layout/header', 'layout/addon-header-steps'], $args);
            }

            View::load("pages/license-manager/$currentTab", $args);
            Admin_Template::get_template(['layout/postbox.hide', 'layout/footer'], $args);
        } catch (Exception $e) {
            Notice::renderNotice($e->getMessage(), $e->getCode(), 'error');
        }
    }
}
