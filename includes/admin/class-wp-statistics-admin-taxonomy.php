<?php

namespace WP_STATISTICS;

use WP_Statistics\MiniChart\WP_Statistics_Mini_Chart_Settings;

class Admin_Taxonomy
{
    /**
     * Admin_Taxonomy constructor.
     */
    public function __construct()
    {

        // Add Hits Column in All Admin Post-Type Wp_List_Table
        if (User::Access('read') and !Option::get('disable_column')) {
            add_action('admin_init', array($this, 'init'));
        }

        // Remove Term Hits when Term Id deleted
        add_action('delete_term', array($this, 'modify_delete_term'), 10, 2);
    }

    /**
     * Init Hook
     */
    public function init()
    {

        // Check Active
        if (!apply_filters('wp_statistics_show_taxonomy_hits', true)) {
            return;
        }

        // Add Column
        foreach (Helper::get_list_taxonomy() as $tax => $name) {
            add_action('manage_edit-' . $tax . '_columns', array($this, 'add_column'), 10, 2);
            add_filter('manage_' . $tax . '_custom_column', array($this, 'render_column'), 10, 3);
            add_filter('manage_edit-' . $tax . '_sortable_columns', array($this, 'modify_sortable_columns'));
        }
        add_filter('terms_clauses', array($this, 'modify_order_by_hits'), 10, 3);
    }

    /**
     * Add a custom column to post/pages for hit statistics.
     *
     * @param array $columns Columns
     * @return array Columns
     */
    public function add_column($columns)
    {

        // Check WooCommerce sortable UI
        if (isset($columns['handle'])) {
            $col = array();
            foreach ($columns as $k => $v) {
                if ($k == "handle") {
                    $col['wp-statistics-tax-hits'] = __('Views', 'wp-statistics');
                }
                $col[$k] = $v;
            }
            return $col;
        }

        $columns['wp-statistics-tax-hits'] = __('Views', 'wp-statistics');
        return $columns;
    }

    /**
     * Render the custom column on the post/pages lists.
     *
     * @param string $value
     * @param string $column_name Column Name
     * @param int $term_id
     * @return string
     */
    public function render_column($value, $column_name, $term_id)
    {
        if ($column_name == 'wp-statistics-tax-hits') {
            $term       = get_term($term_id);
            $hit_number = wp_statistics_pages('total', "", $term_id, null, null, $term->taxonomy);

            if ($hit_number) {
                $preview_chart_unlock_html = sprintf('<div class="wps-admin-column__unlock"><a href="%s" target="_blank"><span class="wps-admin-column__unlock__text">%s</span><img class="wps-admin-column__unlock__lock" src="%s"/><img class="wps-admin-column__unlock__img" src="%s"/></a></div>',
                    'https://wp-statistics.com/product/wp-statistics-mini-chart?utm_source=wp_statistics&utm_medium=display&utm_campaign=wordpress',
                    __('Unlock This Feature!', 'wp-statistics'),
                    WP_STATISTICS_URL . 'assets/images/mini-chart-posts-lock.svg',
                    WP_STATISTICS_URL . 'assets/images/mini-chart-posts-preview.svg'
                );

                $setting = class_exists(WP_Statistics_Mini_Chart_Settings::class) ? get_option(WP_Statistics_Mini_Chart_Settings::get_instance()->setting_name) : '';
                $value   = '';
                if (
                    !Helper::isAddOnActive('mini-chart') ||
                    (!empty($setting) && !empty($setting['active_mini_chart_' . $term->taxonomy]))
                ) {
                    // If add-on is not active, this line will display the "Unlock This Feature!" button
                    // If add-on is active but current taxonomy is not selected in the settings, nothing will be displayed
                    $value = apply_filters("wp_statistics_before_hit_column", $preview_chart_unlock_html, $term_id, $term->taxonomy);
                }

                $value .= sprintf('<div class="%s"><span class="%s">%s</span> <a href="%s" class="wps-admin-column__link">%s</a></div>',
                    Helper::isAddOnActive('mini-chart') && Option::getByAddon('count_display', 'mini_chart', 'total') === 'disabled' ? 'wps-hide' : '',
                    Helper::isAddOnActive('mini-chart') ? '' : 'wps-hide',
                    Helper::isMiniChartMetricSetToVisitors() ? esc_html__('Visitors:', 'wp-statistics') : esc_html__('Views:', 'wp-statistics'),
                    Menus::admin_url('category-analytics', ['type' => 'single', 'term_id' => $term_id]),
                    number_format($hit_number)
                );
            }

        }

        return $value;
    }

    /**
     * Added Sortable Params
     *
     * @param $columns
     * @return mixed
     */
    public function modify_sortable_columns($columns)
    {
        $columns['wp-statistics-tax-hits'] = 'hits';
        return $columns;
    }

    /**
     * Sort Taxonomy By Hits
     *
     * @param $clauses
     * @param $query
     */
    public function modify_order_by_hits($clauses, $taxonomy, $query)
    {

        // Check in Admin
        if (!is_admin()) {
            return;
        }

        // If order-by.
        if (isset($query['orderby']) and $query['orderby'] == 'hits') {
            // Select Field
            $clauses['fields'] .= ", (select SUM(" . DB::table("pages") . ".count) from " . DB::table("pages") . " where (" . DB::table("pages") . ".type = 'category' OR " . DB::table("pages") . ".type = 'post_tag' OR " . DB::table("pages") . ".type = 'tax') AND t.term_id = " . DB::table("pages") . ".id) as tax_hist_sortable ";

            // And order by it.
            $clauses['orderby'] = " ORDER BY coalesce(tax_hist_sortable, 0)";
        }

        return $clauses;
    }

    /**
     * Delete All Term Hits When Term is Deleted
     *
     * @param $term
     * @param $term_id
     */
    public static function modify_delete_term($term, $term_id)
    {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare("DELETE FROM `" . DB::table('pages') . "` WHERE `id` = %d AND (`type` = 'category' OR `type` = 'post_tag' OR `type` = 'tax');", esc_sql($term_id))
        );
    }
}

new Admin_Taxonomy;
