<?php

namespace WP_Statistics\Service\Admin\LicenseManagement;

use WP_Statistics\Traits\TransientCacheTrait;
use WP_Statistics\Components\RemoteRequest;
use Exception;

class LicenseManagementService
{
    use TransientCacheTrait;

    private $apiUrl = 'https://staging.wp-statistics.veronalabs.com/wp-json/wp-license-manager/v1';
    private $licensesOption = 'wp_statistics_licenses'; // Option key to store licenses
    private $productDecorator;

    public function __construct()
    {
        $this->productDecorator = new ProductDecorator();
    }

    /**
     * Get the list of products (add-ons) from the API and cache it for 1 week.
     *
     * @return ProductDecorator[] List of products
     * @throws Exception if there is an error with the API call
     */
    public function getProductList()
    {
        // Try to get cached result
        $cachedProducts = $this->getCachedResult('product_list');
        if ($cachedProducts) {
            //return $this->productDecorator->decorateProducts($cachedProducts); // @todo let it be disable during the development
        }

        try {

            //// ---- dev
            /// @todo set the auth during development and DON'T PUSH IT IN GIT :)
            $arguments['headers']['Authorization'] = 'Basic ' . base64_encode('username:password');
            //// ---- dev

            $remoteRequest = new RemoteRequest("{$this->apiUrl}/product/list", 'GET', [], $arguments);
            $products      = $remoteRequest->execute();

        } catch (Exception $e) {
            throw new Exception(
                sprintf(__('Error fetching product list: %s', 'wp-statistics'), $e->getMessage())
            );
        }

        // Cache the response for 1 week (7 days)
        //$this->setCachedResult('product_list', $products, WEEK_IN_SECONDS); //@todo let it be disable during the development

        return $this->productDecorator->decorateProducts($products);
    }

    /**
     * Validate the license and get the status of licensed products.
     *
     * @param string $licenseKey
     *
     * @return object License status
     * @throws Exception if the API call fails
     */
    public function validateLicense($licenseKey)
    {
        $domain = home_url();

        try {

            //// ---- dev
            /// @todo set the auth during development and DON'T PUSH IT IN GIT :)
            $arguments['headers']['Authorization'] = 'Basic ' . base64_encode('username:password');
            //// ---- dev

            $remoteRequest = new RemoteRequest("{$this->apiUrl}/license/status", 'GET', [
                'license_key' => $licenseKey,
                'domain'      => $domain,
            ], $arguments);

            $licenseData = $remoteRequest->execute();

        } catch (Exception $e) {
            throw new Exception(
                sprintf(__('Error validating license: %s', 'wp-statistics'), $e->getMessage())
            );
        }

        // Store the license details in the database
        $this->storeLicense($licenseKey, $licenseData->license_details);

        return $licenseData;
    }

    /**
     * Store license details in the WordPress database.
     *
     * @param string $licenseKey
     * @param array $licenseDetails
     */
    public function storeLicense($licenseKey, $licenseDetails)
    {
        // Get current licenses
        $currentLicenses = get_option($this->licensesOption, []);

        // Store the new license with its details
        $currentLicenses[$licenseKey] = $licenseDetails;

        update_option($this->licensesOption, $currentLicenses);
    }

    /**
     * Merge the product list with the status from the license.
     *
     * @param string $licenseKey
     *
     * @return array Merged product list with status
     */
    public function mergeProductStatusWithLicense($licenseKey)
    {
        // Get the list of products
        $productList = $this->getProductList();

        // Get the license status
        $licenseStatus = $this->validateLicense($licenseKey);

        // Merge product list with license status and return the result
        return $this->productDecorator->decorateProductsWithLicense($productList, $licenseStatus->products);
    }

    /**
     * Get the download URL for a specific plugin slug from the license status.
     *
     * @param string $licenseKey
     * @param string $pluginSlug
     *
     * @return string|null The download URL if found, null otherwise
     */
    public function getPluginDownloadUrl($licenseKey, $pluginSlug)
    {
        // Validate the license and get the licensed products
        $licenseStatus = $this->validateLicense($licenseKey);

        // Search for the download URL in the licensed products
        foreach ($licenseStatus->products as $product) {
            if ($product['slug'] === $pluginSlug) {
                return $product['download_url'] ?? null;
            }
        }

        return null;
    }
}
