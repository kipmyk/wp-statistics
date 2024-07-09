<?php

namespace WP_Statistics\Models;

use WP_Statistics\Abstracts\BaseModel;
use WP_STATISTICS\Helper;
use WP_Statistics\Service\Admin\Posts\WordCountService;
use WP_Statistics\Utils\Query;

class PostsModel extends BaseModel
{

    public function countPosts($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => '',
            'author_id' => '',
            'taxonomy'  => '',
            'term'      => ''
        ]);

        $query = Query::select('COUNT(ID)')
            ->from('posts')
            ->where('post_status', '=', 'publish')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_author', '=', $args['author_id'])
            ->whereDate('post_date', $args['date'])
            ->bypassCache($bypassCache);

        if (!empty($args['taxonomy']) || !empty($args['term'])) {
            $query
                ->join('term_relationships', ['posts.ID', 'term_relationships.object_id'])
                ->join('term_taxonomy', ['term_relationships.term_taxonomy_id', 'term_taxonomy.term_taxonomy_id'])
                ->where('term_taxonomy.taxonomy', 'IN', $args['taxonomy']);

            if (!empty($args['term'])) {
                $query
                    ->join('terms', ['term_taxonomy.term_id', 'terms.term_id'])
                    ->where('terms.term_id', '=', $args['term']);
            }
        }

        $result = $query->getVar();

        return $result ? $result : 0;
    }

    public function countWords($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => '',
            'author_id' => '',
            'post_id'   => '',
            'taxonomy'  => '',
            'term'      => ''
        ]);

        $wordsCountMetaKey = WordCountService::WORDS_COUNT_META_KEY;

        $query = Query::select('SUM(meta_value)')
            ->from('posts')
            ->join('postmeta', ['posts.ID', 'postmeta.post_id'])
            ->where('post_status', '=', 'publish')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('posts.ID', '=', $args['post_id'])
            ->where('post_author', '=', $args['author_id'])
            ->where('meta_key', '=', $wordsCountMetaKey)
            ->whereDate('post_date', $args['date'])
            ->bypassCache($bypassCache);

        if (!empty($args['taxonomy']) || !empty($args['term'])) {
            $query
                ->join('term_relationships', ['posts.ID', 'term_relationships.object_id'])
                ->join('term_taxonomy', ['term_relationships.term_taxonomy_id', 'term_taxonomy.term_taxonomy_id'])
                ->where('term_taxonomy.taxonomy', 'IN', $args['taxonomy']);

            if (!empty($args['term'])) {
                $query
                    ->join('terms', ['term_taxonomy.term_id', 'terms.term_id'])
                    ->where('terms.term_id', '=', $args['term']);
            }
        }

        $result = $query->getVar();

        return $result ? $result : 0;
    }

    public function countComments($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => '',
            'author_id' => '',
            'post_id'   => '',
            'taxonomy'  => '',
            'term'      => ''
        ]);

        $query = Query::select('COUNT(comment_ID)')
            ->from('posts')
            ->join('comments', ['posts.ID', 'comments.comment_post_ID'])
            ->where('post_status', '=', 'publish')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_author', '=', $args['author_id'])
            ->where('comments.comment_type', '=', 'comment')
            ->where('posts.ID', '=', $args['post_id'])
            ->whereDate('post_date', $args['date'])
            ->bypassCache($bypassCache);

        if (!empty($args['taxonomy']) || !empty($args['term'])) {
            $query
                ->join('term_relationships', ['posts.ID', 'term_relationships.object_id'])
                ->join('term_taxonomy', ['term_relationships.term_taxonomy_id', 'term_taxonomy.term_taxonomy_id'])
                ->where('term_taxonomy.taxonomy', 'IN', $args['taxonomy']);

            if (!empty($args['term'])) {
                $query
                    ->join('terms', ['term_taxonomy.term_id', 'terms.term_id'])
                    ->where('terms.term_id', '=', $args['term']);
            }
        }

        $result = $query->getVar();

        return $result ? $result : 0;
    }

    public function getPostPublishOverview($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => [
                'from'  => date('Y-m-d', strtotime('-365 days')),
                'to'    => date('Y-m-d', time()),
            ],
            'post_type' => Helper::get_list_post_type(),
            'author_id' => ''
        ]);

        $result = Query::select(['DATE(post_date) as date', 'COUNT(ID) as posts'])
            ->from('posts')
            ->where('post_status', '=', 'publish')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_author', '=', $args['author_id'])
            ->whereDate('post_date', $args['date'])
            ->groupBy('Date(post_date)')
            ->bypassCache($bypassCache)
            ->getAll();

        return $result;
    }

    public function getPostsReportData($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => Helper::get_list_post_type(),
            'order_by'  => 'title',
            'order'     => 'DESC',
            'page'     => 1,
            'per_page' => 5,
            'author_id' => ''
        ]);

        $commentsQuery = Query::select(['comment_post_ID', 'COUNT(comment_ID) AS total_comments'])
            ->from('comments')
            ->where('comment_type', '=', 'comment')
            ->groupBy('comment_post_ID')
            ->getQuery();

        $viewsQuery = Query::select(['id', 'SUM(count) AS views'])
            ->from('pages')
            ->groupBy('id')
            ->getQuery();

        $result = Query::select([
                'posts.ID AS post_id',
                'posts.post_author AS author_id',
                'posts.post_title AS title',
                'COALESCE(pages.views, 0) AS views',
                'COALESCE(comments.total_comments, 0) AS comments',
                "MAX(CASE WHEN postmeta.meta_key = 'wp_statistics_words_count' THEN postmeta.meta_value ELSE 0 END) AS words"
            ])
            ->from('posts')
            ->joinQuery($commentsQuery, ['posts.ID', 'comments.comment_post_ID'], 'comments', 'LEFT')
            ->joinQuery($viewsQuery, ['posts.ID', 'pages.id'], 'pages', 'LEFT')
            ->join('postmeta', ['posts.ID', 'postmeta.post_id'], [], 'LEFT')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_status', '=', 'publish')
            ->where('posts.post_author', '=', $args['author_id'])
            ->whereDate('posts.post_date', $args['date'])
            ->groupBy('posts.ID')
            ->orderBy($args['order_by'], $args['order'])
            ->perPage($args['page'], $args['per_page'])
            ->bypassCache($bypassCache)
            ->getAll();

        return $result;
    }

    public function getPostsViewsData($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'          => '',
            'date_field'    => 'pages.date',
            'post_type'     => Helper::get_list_post_type(),
            'order_by'      => 'views',
            'order'         => 'DESC',
            'page'          => 1,
            'per_page'      => 5,
            'author_id'     => '',
            'taxonomy'      => '',
            'term'          => ''
        ]);

        $viewsQuery = Query::select(['id', 'date', 'SUM(count) AS views'])
            ->from('pages')
            ->groupBy('id')
            ->getQuery();

        $query = Query::select([
                'posts.ID',
                'posts.post_author',
                'posts.post_title',
                'posts.post_date',
                'COALESCE(pages.views, 0) AS views',
            ])
            ->from('posts')
            ->joinQuery($viewsQuery, ['posts.ID', 'pages.id'], 'pages', 'LEFT')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_status', '=', 'publish')
            ->where('posts.post_author', '=', $args['author_id'])
            ->whereDate($args['date_field'], $args['date'])
            ->groupBy('posts.ID')
            ->orderBy($args['order_by'], $args['order'])
            ->perPage($args['page'], $args['per_page'])
            ->bypassCache($bypassCache);

        if (!empty($args['taxonomy']) || !empty($args['term'])) {
            $query
                ->join('term_relationships', ['posts.ID', 'term_relationships.object_id'])
                ->join('term_taxonomy', ['term_relationships.term_taxonomy_id', 'term_taxonomy.term_taxonomy_id'])
                ->where('term_taxonomy.taxonomy', 'IN', $args['taxonomy']);

            if (!empty($args['term'])) {
                $query
                    ->join('terms', ['term_taxonomy.term_id', 'terms.term_id'])
                    ->where('terms.term_id', '=', $args['term']);
            }
        }

        $result = $query->getAll();

        return $result;
    }

    public function getPostsCommentsData($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => Helper::get_list_post_type(),
            'order_by'  => 'comments',
            'order'     => 'DESC',
            'page'      => 1,
            'per_page'  => 5,
            'author_id' => ''
        ]);

        $result = Query::select([
                'posts.ID',
                'posts.post_author',
                'posts.post_title',
                'COALESCE(COUNT(comment_ID), 0) AS comments',
            ])
            ->from('posts')
            ->join('comments', ['posts.ID', 'comments.comment_post_ID'])
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_status', '=', 'publish')
            ->where('posts.post_author', '=', $args['author_id'])
            ->where('comments.comment_type', '=', 'comment')
            ->whereDate('posts.post_date', $args['date'])
            ->groupBy('posts.ID')
            ->orderBy($args['order_by'], $args['order'])
            ->perPage($args['page'], $args['per_page'])
            ->bypassCache($bypassCache)
            ->getAll();

        return $result;
    }

    public function getPostsWordsData($args = [], $bypassCache = false)
    {
        $args = $this->parseArgs($args, [
            'date'      => '',
            'post_type' => Helper::get_list_post_type(),
            'order_by'  => 'words',
            'order'     => 'DESC',
            'page'      => 1,
            'per_page'  => 5,
            'author_id' => ''
        ]);

        $result = Query::select([
                'posts.ID',
                'posts.post_author',
                'posts.post_title',
                "MAX(CASE WHEN postmeta.meta_key = 'wp_statistics_words_count' THEN postmeta.meta_value ELSE 0 END) AS words",
            ])
            ->from('posts')
            ->join('postmeta', ['posts.ID', 'postmeta.post_id'], [], 'LEFT')
            ->where('post_type', 'IN', $args['post_type'])
            ->where('post_status', '=', 'publish')
            ->where('posts.post_author', '=', $args['author_id'])
            ->whereDate('posts.post_date', $args['date'])
            ->groupBy('posts.ID')
            ->orderBy($args['order_by'], $args['order'])
            ->perPage($args['page'], $args['per_page'])
            ->bypassCache($bypassCache)
            ->getAll();

        return $result;
    }
}