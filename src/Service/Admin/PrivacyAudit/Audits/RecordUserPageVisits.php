<?php

namespace WP_Statistics\Service\Admin\PrivacyAudit\Audits;

use WP_Statistics\Service\Admin\PrivacyAudit\Audits\Abstracts\ResolvableAudit;

class RecordUserPageVisits extends ResolvableAudit
{
    public static $optionKey = 'visitors_log';

    public static function isOptionPassed()
    {
        // If option is disabled, consider it passed.
        return !self::isOptionEnabled();
    }

    public static function getPassedStateInfo()
    {
        return [
            'title'            => esc_html__('The “Track Logged-In User Activity” feature is currently disabled on your website.', 'wp-statistics'),
            'notes'            => __('<p> This status indicates that individual user page views and WordPress user IDs are not being tracked. Your privacy settings are configured to prioritize user privacy in alignment with applicable laws and regulations.</p><p><b>Why is this important?</b></p><p>Keeping this feature disabled ensures that your website minimally impacts user privacy, aligning with best practices for data protection and compliance with privacy laws such as GDPR and CCPA. If your operational or analytical needs change, please review our Guide to <a target="_blank" href="https://wp-statistics.com/resources/avoiding-pii-data-collection/?utm_source=wp-statistics&utm_medium=link&utm_campaign=privacy">Avoiding PII Data Collection</a> to ensure compliance and user transparency before enabling this feature.</p>', 'wp-statistics'),
            'suggestion_title' => esc_html__('Why is This Important?', 'wp-statistics'),
            'info_title'       => esc_html__('How to Disable This Feature', 'wp-statistics'),
            'info'             => esc_html__('navigate to Settings -> General and uncheck "Track Logged-In User Activity".', 'wp-statistics'),
            'suggestion'       => __('Enabling this feature necessitates a careful approach to privacy and data protection. To maintain compliance with privacy laws such as GDPR and CCPA, and to uphold user trust, please ensure the following:
            <ol>
                <li><b>Transparency:</b> Your website’s privacy policy should clearly describe the data collection practices, including the specific types of data collected and their intended use.</li>
                <li><b>Informed Consent:</b> Adequate measures are in place to inform users about the data collection and to obtain their consent where necessary. This may include consent banners, notifications, or other user interfaces that clearly communicate this information.</li>
                <li><b>Review and Action:</b> Regularly review the necessity of keeping this feature enabled. If the feature is no longer needed, or if you wish to enhance user privacy, consider disabling it. Refer to our guide on <a href="https://wp-statistics.com/resources/avoiding-pii-data-collection/?utm_source=wp-statistics&utm_medium=link&utm_campaign=privacy" target="_blank">Adjusting Your Privacy Settings</a> for detailed instructions on managing this feature.</li>
            </ol>
           ', 'wp-statistics')
        ];
    }

    public static function getUnpassedStateInfo()
    {
        return [
            'title'            => esc_html__('The “Track Logged-In User Activity” feature is currently enabled on your website.', 'wp-statistics'),
            'notes'            => __('This status indicates that individual user page visits and WordPress user IDs are not being tracked. Your privacy settings are configured to prioritize user privacy in alignment with applicable laws and regulations.</br><p>Why is this important?</p>Keeping this feature disabled ensures that your website minimally impacts user privacy, aligning with best practices for data protection and compliance with privacy laws such as GDPR and CCPA. If your operational or analytical needs change, please review our Guide to Avoiding PII Data Collection to ensure compliance and user transparency before enabling this feature.', 'wp-statistics'),
            'suggestion_title' => esc_html__('Enable or Disable \'Track User Activity', 'wp-statistics\''),
            'suggestion'       => esc_html__('navigate to Settings -> Basic Tracking -> Record User Page Visits and uncheck "Track User Activity"', 'wp-statistics'),
        ];
    }
}