<?php 
namespace WP_Statistics\Service\Admin\ContentAnalytics;

use WP_STATISTICS\Helper;
use WP_Statistics\Models\PostsModel;
use WP_Statistics\Models\ViewsModel;
use WP_Statistics\Models\VisitorsModel;
use WP_STATISTICS\TimeZone;

class ContentAnalyticsDataProvider
{
    protected $args;
    private $postsModel;
    private $viewsModel;
    private $visitorsModel;
    
    public function __construct($args)
    {
        $this->args = $args;

        $this->postsModel       = new PostsModel();
        $this->viewsModel       = new ViewsModel();
        $this->visitorsModel    = new VisitorsModel();
    }

    public function getPerformanceChartData()
    {
        $result = [
            'labels'    => [],
            'views'     => [],
            'visitors'  => []
        ];

        for ($i = 14; $i >= 0; $i--) {
            $date       = date('Y-m-d', strtotime("-$i days"));
            $dateFilter = ['date' => ['from' => $date, 'to' => $date]];

            $result['labels'][]     = date_i18n('j M', strtotime($date));
            $result['visitors'][]   = $this->visitorsModel->countVisitors(array_merge($this->args, $dateFilter));
            $result['views'][]      = $this->viewsModel->countViews(array_merge($this->args, $dateFilter));
            $result['posts'][]      = $this->postsModel->countPosts(array_merge($this->args, $dateFilter));
        }

        return $result;
    }

    public function getSearchEnginesChartData()
    {
        // Get results up to 30 days
        $args = [];
        $days = TimeZone::getNumberDayBetween($this->args['date']['from'], $this->args['date']['to']);
        if ($days > 30) {
            $args = [
                'date' => [
                    'from' => date('Y-m-d', strtotime("-30 days", strtotime($this->args['date']['to']))),
                    'to'   => $this->args['date']['to']
                ]
            ];
        }

        $args = array_merge($this->args, $args);

        $datesList = TimeZone::getListDays($args['date']);
        $datesList = array_keys($datesList);

        $result = [
            'labels'    => array_map(function($date) { return date_i18n('j M', strtotime($date)); }, $datesList),
            'datasets'  => []
        ];

        $data       = $this->visitorsModel->getSearchEngineReferrals($args);
        $parsedData = [];

        // Format and parse data
        foreach ($data as $item) {
            $parsedData[$item->engine][$item->date] = $item->visitors;
        }
    
        // Fill out missing visitors with 0
        foreach ($parsedData as $searchEngine => $data) {
            $parsedData[$searchEngine] = array_merge(array_fill_keys($datesList, 0), $parsedData[$searchEngine]);
            ksort($data);
        }
    
        // Generate dataset with proper data
        foreach ($parsedData as $searchEngine => $data) {
            $result['datasets'][] = [
                'label' => ucfirst($searchEngine),
                'data'  => array_values($data)
            ];
        }
        
        return $result;
    }

    public function getOverviewData()
    {
        $totalPosts     = $this->postsModel->countPosts($this->args);
        $totalViews     = $this->viewsModel->countViews($this->args);
        $totalVisitors  = $this->visitorsModel->countVisitors($this->args);
        $totalWords     = $this->postsModel->countWords($this->args);
        $totalComments  = $this->postsModel->countComments($this->args);

        $data = [
            'published' => [
                'total' => $totalPosts
            ],
            'views'     => [
                'total' => $totalViews,
                'avg'   => Helper::divideNumbers($totalViews, $totalPosts)
            ],
            'visitors'  => [
                'total' => $totalVisitors,
                'avg'   => Helper::divideNumbers($totalVisitors, $totalPosts)
            ],
            'words'     => [
                'total' => $totalWords,
                'avg'   => Helper::divideNumbers($totalWords, $totalPosts)
            ],
            'comments'  => [
                'total' => $totalComments,
                'avg'   => Helper::divideNumbers($totalComments, $totalPosts)
            ]
        ];

        return $data;
    }

    public function getVisitorsData()
    {
        return $this->visitorsModel->getParsedVisitorsData($this->args);
    }

    public function getPostTypeData()
    {
        $overviewData       = $this->getOverviewData();
        $visitorsData       = $this->getVisitorsData();
        
        $visitorsSummary    = $this->visitorsModel->getVisitorsSummary($this->args);
        $viewsSummary       = $this->viewsModel->getViewsSummary($this->args);
        
        $referrersData      = $this->visitorsModel->getReferrers($this->args);
        
        $performanceArgs    = ['date' => ['from' => date('Y-m-d', strtotime('-15 days')), 'to' => date('Y-m-d')]];
        $performanceData    = [
            'posts'     => $this->postsModel->countPosts(array_merge($this->args, $performanceArgs)),
            'visitors'  => $this->visitorsModel->countVisitors(array_merge($this->args, $performanceArgs)),
            'views'     => $this->viewsModel->countViews(array_merge($this->args, $performanceArgs)),
        ];

        $topPostsByView     = $this->postsModel->getPostsViewsData($this->args);
        $topPostsByComment  = $this->postsModel->getPostsCommentsData($this->args);
        $recentPosts        = $this->postsModel->getPostsViewsData(array_merge($this->args, ['date' => '', 'date_field' => 'post_date', 'order_by' => 'post_date']));

        return [
            'visits_summary'    => array_replace_recursive($visitorsSummary, $viewsSummary),
            'overview'          => $overviewData,
            'visitors_data'     => $visitorsData,
            'performance'       => $performanceData,
            'referrers'         => $referrersData,
            'posts'             => [
                'top_viewing'   => $topPostsByView,
                'top_commented' => $topPostsByComment,
                'recent'        => $recentPosts
            ],
        ];
    }
}