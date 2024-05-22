<?php

namespace WP_Statistics\Models;

use WP_STATISTICS\Helper;
use WP_Statistics\Utils\Query;

class AuthorModel extends DataProvider
{
    /**
     * Calculates the average number of posts per author based on the given arguments.
     *
     * @param array $args An array of arguments to filter the count.
     * @return float|int The average number of posts per author. Returns 0 if no authors are found.
     */
    public function averagePostsPerAuthor($args = [])
    {
        $args = wp_parse_args($args, [
            'from'      => '',
            'to'        => '',
            'post_type' => '',
        ]);

        $query = Query::select('COUNT(ID)')
                    ->fromTable($this->db->posts)
                    ->where('post_status', '=', 'publish')
                    ->where('post_type', 'IN', $args['post_type'])
                    ->whereDate('post_date', [$args['from'], $args['to']])
                    ->get();

        $totalPosts   = $this->getVar($query);
        $totalAuthors = $this->count();

        return $totalPosts ? $totalPosts / $totalAuthors : 0;
    }

    /**
     * Counts the authors based on the given arguments. 
     * By default, it will return total number of authors.
     *
     * @param array $args An array of arguments to filter the count.
     * @return int The total number of distinct authors. Returns 0 if no authors are found.
     */
    public function count($args = [])
    {
        $args = wp_parse_args($args, [
            'from'      => '',
            'to'        => '',
            'post_type' => Helper::get_list_post_type()
        ]);

        $query = Query::select('COUNT(DISTINCT post_author)')
                    ->fromTable($this->db->posts)
                    ->where('post_status', '=', 'publish')
                    ->where('post_type', 'IN', $args['post_type'])
                    ->whereDate('post_date', [$args['from'], $args['to']])
                    ->get();

        $result = $this->getVar($query);

        return $result ? $result : 0;
    }

}