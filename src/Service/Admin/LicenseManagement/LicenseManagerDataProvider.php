<?php

namespace WP_Statistics\Service\Admin\LicenseManagement;

class LicenseManagerDataProvider
{
    protected $args;
    private $licenseService;

    public function __construct($args = [])
    {
        $this->args           = $args;
        $this->licenseService = new LicenseManagementService();
    }

    /**
     * Return a list of the product for view
     *
     * @return ProductDecorator[]
     * @throws \Exception
     */
    public function getProductList()
    {
        return $this->licenseService->getProductList();
    }

    /**
     * Returns data for "Add-Ons" tab.
     *
     * @return array
     */
    public function getAddOnsData()
    {
        $activeAddOns   = [];
        $inactiveAddOns = [];

        try {
            foreach ($this->getProductList() as $addOn) {
                if ($addOn->isActivated()) {
                    $activeAddOns[] = $addOn;
                } else {
                    $inactiveAddOns[] = $addOn;
                }
            }
        } catch (\Exception $e) {
            $activeAddOns   = [];
            $inactiveAddOns = [];
        }

        return [
            'active_addons'   => $activeAddOns,
            'inactive_addons' => $inactiveAddOns,
        ];
    }
}
