<?php
namespace WP_Statistics\Utils;

class Url
{
    /**
     * Retrieves the protocol from a given URL.
     *
     * @param string $url The URL from which to extract the scheme.
     * @return string The scheme extracted from the URL, or an empty string if the URL is invalid.
     */
    public static function getProtocol($url)
    {
        $parsedUrl = wp_parse_url($url);

        return $parsedUrl['scheme'] ?? '';
    }

    /**
     * Retrieves the domain from a given URL without www.
     *
     * @param string $url The URL from which to extract the domain.
     * @param bool $protocol (Optional) Whether to include the protocol in the domain. Default is false.
     * @return string The domain extracted from the URL, or an empty string if the URL is invalid.
     */
    public static function getDomain($url, $protocol = false)
    {
        // Make url lower case
        $url = strtolower($url);

        // Sanitize url
        $url = sanitize_url($url);

        // Parse URL
        $parsedUrl = wp_parse_url($url);

        // If host is empty, return early
        if (empty($parsedUrl['host'])) return '';

        // Get domain name
        $domain = $parsedUrl['host'];

        // Remove www if present
        $domain = preg_replace('/^www\./', '', $domain);

        // Remove trailing slash
        $domain = rtrim($domain, '/');

        // Add protocol
        if ($protocol && !empty($parsedUrl['scheme'])) {
            $domain = $parsedUrl['scheme'] . '://' . $domain;
        }

        return $domain;
    }

    /**
     * Formats a given URL by removing trailing slashes and adding a protocol if missing.
     *
     * @param string $url The URL to be formatted.
     * @return string The formatted URL.
     */
    public static function formatUrl($url) {
        // Remove trailing slash
        $url = rtrim($url, '/');

        // Add https protocol if missing
        if (empty(self::getProtocol($url))) {
            $url = "https://$url";
        }

        return $url;
    }
}
