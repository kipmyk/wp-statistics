<?php

namespace WP_Statistics\Traits;

/**
 * Trait to handle caching logic.
 * @doc https://github.com/wp-statistics/wp-statistics/wiki/TransientCacheTrait.md
 */
trait TransientCacheTrait
{
    /**
     * Get the cache key for the given query.
     *
     * @param string $query
     *
     * @return string
     */
    protected function getCacheKey($query)
    {
        $hash = substr(md5($query), 0, 10);
        return sprintf('wp_statistics_cache_%s', $hash);
    }

    /**
     * Get the cached result for the given query.
     *
     * @param string $query
     *
     * @return mixed
     */
    protected function getCachedResult($query)
    {
        $cacheKey = $this->getCacheKey($query);
        return get_transient($cacheKey);
    }

    /**
     * Set the cached result for the given query.
     *
     * @param string $query
     * @param mixed $result
     * @param int $expiration Expiration time for the cache in seconds.
     *
     * @return bool
     */
    protected function setCachedResult($query, $result, $expiration = HOUR_IN_SECONDS)
    {
        $cacheKey = $this->getCacheKey($query);
        return set_transient($cacheKey, $result, $expiration * 24);
    }

    /**
     * Clear the cached result for the given query.
     *
     * @param string $query
     *
     * @return bool
     */
    protected function clearCache($query)
    {
        $cacheKey = $this->getCacheKey($query);
        return delete_transient($cacheKey);
    }
}
