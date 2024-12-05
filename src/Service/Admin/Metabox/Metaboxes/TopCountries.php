<?php
namespace WP_Statistics\Service\Admin\Metabox\Metaboxes;

use WP_Statistics\Components\View;
use WP_Statistics\Abstracts\BaseMetabox;
use WP_STATISTICS\Menus;

class TopCountries extends BaseMetabox
{
    protected $key = 'top_countries';
    protected $priority = 'side';

    public function getName()
    {
        return esc_html__('Top Countries', 'wp-statistics');
    }

    public function getOptions()
    {
        return [
            'datepicker'    => true,
            'button'        => View::load('metabox/action-button',['link'=> Menus::admin_url('geographic'),'title'=>'View Top Countries'],true)
        ];
    }

    public function getData()
    {
        $args = [
            'ignore_date' => true
        ];

        //  @todo  Add data
        $data = [];

        $output = View::load('metabox/top-countries', ['data' => $data], true);

        return $output;
    }

    public function render()
    {
        View::load('metabox/metabox-skeleton', []);
    }
}