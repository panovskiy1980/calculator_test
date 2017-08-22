<?php

namespace Calculator\Classes;

use Calculator\Classes\CacheInterface;

/**
 * SimpleFileCache class
 *
 * @author Ilya Panovskiy <panovskiy1980@gmail.com>
 */
class SimpleFileCache implements CacheInterface
{
    /** @var string $_fileName */
    private $_fileName = '';
    
    /** @var string $_pathToCacheFolder */
    private $_pathToCacheFolder = '';

    public function __construct(string $fileName, string $pathToCacheFolder)
    {
        $this->_fileName = $fileName;
        $this->_pathToCacheFolder = $pathToCacheFolder;
        
        $this->checkFileAndPath();
    }
    
    /**
     * Get cache
     * 
     * @return mixed
     */
    public function getCache()
    {
        return unserialize(
                file_get_contents(
                        $this->_pathToCacheFolder 
                        . DIRECTORY_SEPARATOR 
                        . $this->_fileName
                    )
                );
    }
    
    /**
     * Set cache
     * 
     * @param mixed $cache
     * 
     * @return boolean|integer
     */
    public function setCache($cache)
    {
        return file_put_contents(
                $this->_pathToCacheFolder 
                . DIRECTORY_SEPARATOR 
                . $this->_fileName, 
                !empty($cache) ? serialize($cache) : '', 
                LOCK_EX);
    }
    
    /**
     * Delete cache
     * 
     * @return boolean
     */
    public function deleteCache() : bool
    {
        return unlink(
                $this->_pathToCacheFolder 
                . DIRECTORY_SEPARATOR 
                . $this->_fileName
                );
    }
    
    /**
     * Check file and path existence
     */
    private function checkFileAndPath() : void
    {
        if(!file_exists($this->_pathToCacheFolder))
        {
            mkdir($this->_pathToCacheFolder, 0777, TRUE);
        }
        
        if(!file_exists(
                $this->_pathToCacheFolder 
                . DIRECTORY_SEPARATOR 
                . $this->_fileName
                )
            )
        {
            $this->setCache(null);
        }
    }
}
