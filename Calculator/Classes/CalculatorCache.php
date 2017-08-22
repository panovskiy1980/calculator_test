<?php

namespace Calculator\Classes;

use Calculator\Classes\CacheInterface;

/**
 * CalculatorCache class
 * Wrapper for all classes in config file for cache storage of Calculator class
 *
 * @author Ilya Panovskiy <panovskiy1980@gmail.com>
 */
class CalculatorCache
{
    /** @var CacheInterface $_cacheObject */
    private $_cacheObject;
    
    public function __construct(CacheInterface $cacheObject)
    {
        $this->_cacheObject = $cacheObject;
    }
    
    /**
     * Get cache
     * 
     * @return mixed
     */
    public function getCache()
    {
        return $this->_cacheObject->getCache();
    }
    
    /**
     * Set cache
     * 
     * @param mixed
     * 
     * @return boolean
     */
    public function setCache($cache)
    {
        return $this->_cacheObject->setCache($cache);
    }
    
    /**
     * Delete cache
     * 
     * @return boolean
     */
    public function deleteCache()
    {
        return $this->_cacheObject->deleteCache();
    }
}
