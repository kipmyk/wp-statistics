<?php

namespace WP_STATISTICS;

use WP_Statistics\Components\Assets;
use WP_Statistics\Service\Integrations\WpConsentApi;

class Frontend
{
    public function __construct()
    {
        # Enable ShortCode in Widget
        add_filter('widget_text', 'do_shortcode');

        # Add the honey trap code in the footer.
        add_action('wp_footer', array($this, 'add_honeypot'));

        # Enqueue scripts & styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        # Print out the WP Statistics HTML comment
        add_action('wp_head', array($this, 'print_out_plugin_html'));

        # Check to show hits in posts/pages
        if (Option::get('show_hits')) {
            add_filter('the_content', array($this, 'show_hits'));
        }
    }

    /**
     * Footer Action
     */
    public function add_honeypot()
    {
        if (Option::get('use_honeypot') && Option::get('honeypot_postid') > 0) {
            $post_url = get_permalink(Option::get('honeypot_postid'));
            echo '<a href="' . esc_html($post_url) . '" style="display: none;">&nbsp;</a>';
        }
    }


    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts()
    {
        /**
         * Merge & build the URLs
         */
        $params               = array_merge([Hits::$rest_hits_key => 'yes'], Helper::getHitsDefaultParams());
        $hitRequestUrl        = add_query_arg($params, get_rest_url(null, RestAPI::$namespace . '/' . Api\v2\Hit::$endpoint));
        $keepOnlineRequestUrl = add_query_arg($params, get_rest_url(null, RestAPI::$namespace . '/' . Api\v2\CheckUserOnline::$endpoint));

        /**
         * Handle the bypass ad blockers
         */
        if (Option::get('bypass_ad_blockers', false)) {
            $hitRequestUrl        = add_query_arg(array_merge($params, ['action' => 'wp_statistics_hit']), admin_url('admin-ajax.php'));
            $keepOnlineRequestUrl = add_query_arg(array_merge($params, ['action' => 'wp_statistics_online']), admin_url('admin-ajax.php'));
        }

        /**
         * Build the parameters
         */
        $jsArgs = array(
            'hitRequestUrl'        => $hitRequestUrl,
            'keepOnlineRequestUrl' => $keepOnlineRequestUrl,
            'option'               => [
                'consentLevel'         => Option::get('consent_level_integration', 'disabled'),
                'dntEnabled'           => Option::get('do_not_track'),
                'cacheCompatibility'   => Option::get('use_cache_plugin'),
                'isWpConsentApiActive' => WpConsentApi::isWpConsentApiActive(),
                'trackAnonymously'     => Helper::shouldTrackAnonymously()
            ],
        );

        Assets::script('tracker', 'js/tracker.js', [], $jsArgs, true, Option::get('bypass_ad_blockers', false));

        // Load Chart.js library
        if (Helper::isAdminBarShowing()) {
            Assets::script('chart.js', 'js/chartjs/chart.umd.min.js', [], [], true, false, null, '4.4.2');
            Assets::script('hammer.js', 'js/chartjs/hammer.min.js', [], [], true, false, null, '2.0.8');
            Assets::script('chartjs-plugin-zoom.js', 'js/chartjs/chartjs-plugin-zoom.min.js', ['wp-statistics-hammer.js'], [], true, false, null, '2.0.1');
            Assets::script('mini-chart', 'js/mini-chart.js', [], [], true);

            Assets::style('front', 'css/frontend.min.css');
        }
    }

    /*
     * Print out the WP Statistics HTML comment
     */
    public function print_out_plugin_html()
    {
        if (apply_filters('wp_statistics_html_comment', true)) {
            echo '<!-- Analytics by WP Statistics v' . WP_STATISTICS_VERSION . ' - ' . WP_STATISTICS_SITE . ' -->' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Show Hits in After WordPress the_content
     *
     * @param $content
     * @return string
     */
    public function show_hits($content)
    {

        // Get post ID
        $post_id = get_the_ID();

        // Check post ID
        if (!$post_id) {
            return $content;
        }

        // Get post hits
        $hits      = wp_statistics_pages('total', "", $post_id);
        $hits_html = '<p>' . sprintf(__('Views: %s', 'wp-statistics'), $hits) . '</p>';

        // Check hits position
        if (Option::get('display_hits_position') == 'before_content') {
            return $hits_html . $content;
        } elseif (Option::get('display_hits_position') == 'after_content') {
            return $content . $hits_html;
        } else {
            return $content;
        }
    }
}

new Frontend;
