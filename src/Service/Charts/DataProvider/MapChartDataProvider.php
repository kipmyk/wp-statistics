<?php

namespace WP_Statistics\Service\Charts\DataProvider;

use WP_STATISTICS\Country;
use WP_Statistics\Decorators\VisitorDecorator;
use WP_STATISTICS\Helper;
use WP_Statistics\Models\VisitorsModel;
use WP_Statistics\Service\Analytics\DeviceDetection\DeviceHelper;
use WP_Statistics\Service\Charts\AbstractChartDataProvider;
use WP_Statistics\Service\Charts\Traits\MapChartResponseTrait;

class MapChartDataProvider extends AbstractChartDataProvider
{
    use MapChartResponseTrait;

    public $args;
    protected $visitorsModel;

    public function __construct($args)
    {
        $this->args = $args;

        $this->visitorsModel = new VisitorsModel();
    }

    public function getData()
    {
        $args = array_merge($this->args, [
            'fields' => [
                'visitor.location as country',
                'COUNT(visitor.ID) as visitors'
            ],
            'order_by' => [],
        ]);

        $this->initChartData();

        $data       = $this->visitorsModel->getVisitorsGeoData($args);
        $parsedData = $this->parseData($data);

        $labels = wp_list_pluck($parsedData, 'label');
        $flags  = wp_list_pluck($parsedData, 'flag');
        $codes  = wp_list_pluck($parsedData, 'code');
        $data   = wp_list_pluck($parsedData, 'visitors');

        $this->setChartLabels($labels);
        $this->setChartFlags($flags);
        $this->setChartCountryCodes($codes);
        $this->setChartData($data);

        return $this->getChartData();
    }

    protected function parseData($data)
    {
        $parsedData = [];

        foreach ($data as $item) {
            if (empty($item->country)) continue;

            $parsedData[] = [
                'label'    => Country::getName($item->country),
                'code'     => $item->country,
                'visitors' => $item->visitors,
                'flag'     => Country::flag($item->country)
            ];
        }

        return $parsedData;
    }
}
