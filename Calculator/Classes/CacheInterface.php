<?php

namespace Calculator\Classes;

/**
 * CacheInterface
 * Must be implemented in each cache class for Calculator class
 * 
 * @author Ilya Panovskiy <panovskiy1980@gmail.com>
 */
interface CacheInterface
{
    /**
     * @return mixed
     */
    public function getCache();
    
    /**
     * @param mixed
     * 
     * @return boolean
     */
    public function setCache($cache);
    
    /**
     * @return boolean
     */
    public function deleteCache();
}
