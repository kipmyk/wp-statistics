<?php
namespace WP_Statistics\Service\Admin\Metabox\Metaboxes;

use WP_Statistics\Abstracts\BaseMetabox;

class TrafficSummary extends BaseMetabox
{
    public function getKey()
    {
        return 'traffic_summary';
    }

    public function getName()
    {
        return esc_html__('Traffic Summary', 'wp-statistics');
    }

    public function getPriority()
    {
        return 'side';
    }

    public function getData()
    {
        wp_send_json('TEST');
    }

    public function render($data = [])
    {
        echo 'TEST';
    }
}