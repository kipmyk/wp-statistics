<?php

namespace WP_Statistics\Utils;

use WP_Statistics\Traits\Cacheable;
use WP_STATISTICS\DB;
use WP_STATISTICS\TimeZone;
use InvalidArgumentException;

class Query
{
    use Cacheable;

    private $operation;
    private $table;
    private $fields = '*';
    private $subQuery;
    private $orderClause;
    private $groupByClause;
    private $limitClause;
    private $joinClauses = [];
    private $whereClauses = [];
    private $whereValues = [];
    private $bypassCache = false;

    /** @var wpdb $db */
    protected $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    public static function select($fields = '*')
    {
        $instance            = new self();
        $instance->operation = 'select';

        if (is_array($fields)) {
            $fields = implode(', ', $fields);
        }

        $instance->fields = $fields;

        return $instance;
    }

    private function getTable($table)
    {
        if (DB::table($table)) {
            $table = DB::table($table);
        } else {
            $table = "{$this->db->prefix}{$table}";
        }

        return $table;
    }

    /**
     * Sets the table for the query.
     *
     * @param string $table The name of the table.
     */
    public function from($table)
    {
        $this->table = $this->getTable($table);
        return $this;
    }

    /**
     * Sets the sub-query for the query. 
     * Useful for times we want to get fields from a certain sub-query, not table.
     *
     * @param string $subQuery The subquery to be used in the query.
     * @param string $alias The alias to be assigned to the subquery. Defaults to 'sub_query'.
     */
    public function fromQuery($subQuery, $alias = 'sub_query')
    {
        $this->subQuery = "($subQuery) AS {$alias}";
        return $this;
    }

    /**
     * Filters the records based on the given date range for a specific field.
     *
     * @param string $field The name of the field to filter by.
     * @param mixed $date The date range to filter by. Either an array of date range, or string date.
     * 
     * @example [2024-01-01, 2024-01-31]
     * @example 'today', 'yesterday', 'year', etc...
     * @see TimeZone::getDateFilters() for a list of all supported string dates
     */
    public function whereDate($field, $date)
    {
        if (is_array($date)) {
            $from = isset($date['from']) ? $date['from'] : '';
            $to   = isset($date['to']) ? $date['to'] : '';
        }

        if (is_string($date)) {
            $date = TimeZone::calculateDateFilter($date);
            $from = $date['from'];
            $to   = $date['to'];
        }

        if (!empty($from) && !empty($to)) {
            $condition            = "DATE($field) BETWEEN %s AND %s";
            $this->whereClauses[] = $condition;
            $this->whereValues[]  = $from;
            $this->whereValues[]  = $to;
        }

        return $this;
    }


    /**
     * Filters the records based on the given condition.
     *
     * @param string $field The name of the field to filter by.
     * @param string $operator The operator to use for the condition. Supported operators are: '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN'.
     * @param mixed $value The value to compare against. For operators 'IN' and 'NOT IN', an array of values can be provided. For operator 'BETWEEN', an array of two values representing the range can be provided.
     */
    public function where($field, $operator, $value)
    {
        if (is_array($value)) {
            $value = array_filter($value);
        }

        if (empty($value)) return $this;

        $condition = $this->generateCondition($field, $operator, $value);
        
        if (!empty($condition)) {
            $this->whereClauses[]   = $condition['condition'];
            $this->whereValues      = array_merge($this->whereValues, $condition['values']);
        }

        return $this;
    }

    protected function generateCondition($field, $operator, $value)
    {
        $condition = '';
        $values    = [];

        switch ($operator) {
            case '=':
            case '!=':
            case '>':
            case '>=':
            case '<':
            case '<=':
            case 'LIKE':
            case 'NOT LIKE':
                if (!empty($value)) {
                    $condition  = "$field $operator %s";
                    $values[]   = $value;
                }
                break;

            case 'IN':
            case 'NOT IN':
                if (is_string($value)) {
                    $value = explode(',', $value);
                }

                if (is_array($value)) {
                    $placeholders   = implode(', ', array_fill(0, count($value), '%s'));
                    $condition      = "$field $operator ($placeholders)";
                    $values         = $value;
                }
                break;

            case 'BETWEEN':
                if (is_array($value) && count($value) === 2) {
                    $condition  = "$field BETWEEN %s AND %s";
                    $values     = $value;
                }
                break;

            default:
                throw new InvalidArgumentException(esc_html__(sprintf("Unsupported operator: %s", $operator)));
        }

        if (empty($condition)) return;

        return [
            'condition' => $condition, 
            'values'    => $values
        ];
    }

    public function getVar()
    {
        $query = $this->buildQuery();
        $query = $this->prepareQuery($query, $this->whereValues);

        if (!$this->bypassCache) {
            $cachedResult = $this->getCachedResult($query);
            if ($cachedResult !== false) {
                return $cachedResult;
            }
        }

        $result = $this->db->get_var($query);

        if (!$this->bypassCache) {
            $this->setCachedResult($query, $result);
        }

        return $result;
    }

    public function getAll()
    {
        $query = $this->buildQuery();
        $query = $this->prepareQuery($query, $this->whereValues);

        if (!$this->bypassCache) {
            $cachedResult = $this->getCachedResult($query);
            if ($cachedResult !== false) {
                return $cachedResult;
            }
        }

        $result = $this->db->get_results($query);

        if (!$this->bypassCache) {
            $this->setCachedResult($query, $result);
        }

        return $result;
    }

    public function getCol()
    {
        $query = $this->buildQuery();
        $query = $this->prepareQuery($query, $this->whereValues);

        if (!$this->bypassCache) {
            $cachedResult = $this->getCachedResult($query);
            if ($cachedResult !== false) {
                return $cachedResult;
            }
        }

        $result = $this->db->get_col($query);

        if (!$this->bypassCache) {
            $this->setCachedResult($query, $result);
        }

        return $result;
    }

    public function getRow()
    {
        $query = $this->buildQuery();
        $query = $this->prepareQuery($query, $this->whereValues);

        if (!$this->bypassCache) {
            $cachedResult = $this->getCachedResult($query);
            if ($cachedResult !== false) {
                return $cachedResult;
            }
        }

        $result = $this->db->get_row($query);

        if (!$this->bypassCache) {
            $this->setCachedResult($query, $result);
        }

        return $result;
    }


    /**
     * Joins the current table with another table based on a given condition.
     *
     * @param string $table The name of the table to join with.
     * @param array $on Table keys to join. ['table1.primary_key', 'table2.foreign_key']
     * @param array[] $conditions Array of extra join conditions to append. [['field', 'operator', 'value'], ...]
     * @param string $joinType The type of join to perform. Defaults to 'INNER'.
     * 
     * @throws InvalidArgumentException If the join clause is invalid.
     */
    public function join($table, $on, $conditions = [], $joinType = 'INNER')
    {
        $joinTable = $this->getTable($table);

        if (is_array($on) && count($on) == 2) {
            $joinClause = "{$joinType} JOIN {$joinTable} AS $table ON {$on[0]} = {$on[1]}";

            if (!empty($conditions)) {
                foreach ($conditions as $condition) {
                    $field      = $condition[0];
                    $operator   = $condition[1];
                    $value      = $condition[2];

                    $condition  = $this->generateCondition($field, $operator, $value);

                    if (!empty($condition)) {
                        $condition  = $this->prepareQuery($condition['condition'], $condition['values']);
                        $joinClause .= " AND {$condition}";
                    }
                }
            }

            $this->joinClauses[] = $joinClause;
        } else {
            throw new InvalidArgumentException(esc_html__('Invalid join clause', 'wp-statistics'));
        }
        
        return $this;
    }

    /**
     * Joins the current query with a subquery based on a given condition.
     *
     * @param string $subQuery The subquery to join with.
     * @param array $on Array of table keys to join. The array should contain two elements: the first element is the primary key of the current table, and the second element is the foreign key of the subquery table.
     * @param string $alias The alias to be assigned to the subquery.
     * @param string $joinType The type of join to perform. Defaults to 'INNER'.
     * 
     * @throws InvalidArgumentException If the join clause is invalid.
     */
    public function joinQuery($subQuery, $on, $alias, $joinType = 'INNER')
    {
        if (is_array($on) && count($on) == 2) {
            $joinClause          = "{$joinType} JOIN ({$subQuery}) AS {$alias} ON {$on[0]} = {$on[1]}";
            $this->joinClauses[] = $joinClause;
        } else {
            throw new InvalidArgumentException(esc_html__('Invalid join clause', 'wp-statistics'));
        }
        
        return $this;
    }
    
    public function orderBy($field, $order = 'DESC')
    {
        if (!empty($field) && !empty($order)) {
            if (is_array($field)) {
                $field = implode(', ', $field);
            }

            $this->orderClause = "ORDER BY {$field} {$order}";
        }
        
        return $this;
    }
    
    public function perPage($page = 1, $perPage = 10)
    {
        if (is_numeric($page) && is_numeric($perPage) && $page > 0 && $perPage > 0) {
            $offset = ($page - 1) * $perPage;
            $this->limitClause = "LIMIT {$perPage} OFFSET {$offset}";
        }
        
        return $this;
    }
    
    public function groupBy($fields)
    {
        if (is_array($fields)) {
            $fields = implode(', ', $fields);
        }

        $this->groupByClause = "GROUP BY {$fields}";
        
        return $this;
    }

    protected function buildQuery()
    {
        $queryMethod = "{$this->operation}Query";

        if (method_exists($this, $queryMethod)) {
            $query = $this->$queryMethod();
        } else {
            throw new InvalidArgumentException(sprintf(esc_html__('%s method is not defined.', 'wp-statistics'), $queryMethod));
        }
        
        return $query;
    }

    public function getQuery()
    {
        $query          = $this->buildQuery();
        $preparedQuery  = $this->prepareQuery($query, $this->whereValues);

        return $preparedQuery;
    }

    protected function prepareQuery($query, $args = [])
    {
        // Only if there's a placeholder, prepare the query
        if (preg_match('/%[i|s|f|d]/', $query)) {
            $query = $this->db->prepare($query, $args);
        }

        return $query;
    }

    protected function selectQuery()
    {
        $query = "SELECT $this->fields FROM ";

        // Append table
        if (!empty($this->table)) {
            $query .= ' ' . $this->table;
            $query .= ' AS ' . $this->removeTablePrefix($this->table);
        }
        
        // Append sub query
        if (!empty($this->subQuery)) {
            $query .= ' ' . $this->subQuery;
        }

        // Append JOIN clauses
        $joinClauses = array_filter($this->joinClauses);
        if (!empty($joinClauses)) {
            $query .= ' ' . implode(' ', $joinClauses);
        }

        // Append WHERE clauses
        $whereClauses = array_filter($this->whereClauses);
        if (!empty($whereClauses)) {
            $query .= ' WHERE ' . implode(" AND ", $whereClauses);
        }

        // Append GROUP BY clauses
        if (!empty($this->groupByClause)) {
            $query .= ' ' . $this->groupByClause;
        }
        
        // Append ORDER clauses
        if (!empty($this->orderClause)) {
            $query .= ' ' . $this->orderClause;
        }

        // Append LIMIT clauses
        if (!empty($this->limitClause)) {
            $query .= ' ' . $this->limitClause;
        }

        return $query;
    }

    public function bypassCache($flag = true)
    {
        $this->bypassCache = $flag;
        return $this;
    }

    public function removeTablePrefix($query)
    {
        return str_replace([$this->db->prefix, 'statistics_'], '', $query);
    }
}
