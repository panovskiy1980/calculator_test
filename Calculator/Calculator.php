<?php

namespace Calculator;

use Calculator\Classes\CalculatorCache;

/**
 * Calculator class
 * Calculates values by passed commands and arguments, e.g.: 'add 2', 'apply 4', etc.
 *
 * @author Ilya Panovskiy <panovskiy1980@gmail.com>
 */
class Calculator
{
    /**
     * All possible commands and numbers' amounts
     * This param could be overridden if needed
     * [command => amount of numbers]
     * 
     * @var array $_operations
     */
    protected $_operations = [
        'add' => 1,
        'subtract' => 1,
        'divide' => 1,
        'multiply' => 1
    ];
    
    /** @var string $_applyCommand */
    protected $_applyCommand = 'apply';
    
    /** @param array $_input */
    private $_input = [];
    
    /** @var string $_uniqueIdForCache */
    private $_uniqueIdForCache = '';
    
    /** @var float $_applyNumber */
    protected $_applyNumber = 0;

    /** @var array $_cacheConfig */
    private $_cacheConfig = [];
    
    /** @var CalculatorCache $_cacheObject */
    private $_cacheObject;
    
    /** @var array $_cacheDataArray */
    private $_cacheDataArray = [];

    /** @var string|integer $_result */
    private $_result = null;
    
    /** @var array $_errors */
    private $_errors = [];

    /**
     * @param string $input
     * @param string $uniqueIdForCache
     */
    public function __construct(string $input, string $uniqueIdForCache = '')
    {
        $this->_input = explode(' ', trim(preg_replace('/\s+/', ' ', $input)));
        $this->_uniqueIdForCache = $uniqueIdForCache;
        $this->_cacheConfig = $this->getCacheConfig();
        $this->_cacheObject = $this->getCacheObject();
        $this->_cacheDataArray = $this->_cacheObject->getCache();
            
        $this->getExceptionsIfExist();
    }
    
    /**
     * Gets cache configuration data
     * This method could be overridden if needed
     * 
     * @return array
     */
    protected function getCacheConfig() : array
    {
        $configArray = require 'config' . DIRECTORY_SEPARATOR . 'config.php';
        
        return $configArray['calculator'];
    }
    
    /**
     * Shapes cache object depending on cache configuration
     * 
     * @return CalculatorCache
     */
    private function getCacheObject() : CalculatorCache
    {
        return new CalculatorCache(new $this->_cacheConfig['cache_class'](
                $this->_cacheConfig['default_file_prefix'] . $this->_uniqueIdForCache, 
                $this->_cacheConfig['path_to_cache_folder']
                ));
    }
    
    /**
     * If something wrong we'll get it here
     * 
     * @throws Exception
     */
    private function getExceptionsIfExist() : void
    {
        try {
            $this->ifCommand();
        }
        catch (Exception $e) {
            $this->_errors[] = $e->getMessage();
        }
    }
    
    /**
     * Checks if command applies
     */
    private function ifCommand()
    {
        if ($this->_input[0] == $this->_applyCommand)
        {
            $this->_applyNumber = (float) $this->_input[1];

            return $this->action();
        }
        
        $this->checkOperation();
    }

    /**
     * Sets up user-friendly errors if ones exist
     */
    private function checkOperation() 
    {
        if (!array_key_exists($this->_input[0], $this->_operations))
        {
            $this->_errors[] = (
                    "Operation '" . $this->_input[0] . "' does not exist." . PHP_EOL
                    . "Operation list: '" . implode("', '", array_keys($this->_operations)) . "'." . PHP_EOL
                    );
            
            return FALSE;
        }
        
        if ((count($this->_input) - 1) !== $this->_operations[$this->_input[0]])
        {
            $this->_errors[] = "Operation must contain " 
                    . $this->_operations[$this->_input[0]] 
                    . " cipher (command and cipher(s))." . PHP_EOL;
            
            return FALSE;
        }
        
        $this->setCacheData();
    }
    
    /**
     * Sets up cache data in to cache storage
     */
    private function setCacheData() : void
    {
        $this->_cacheDataArray[] = $this->_input;
        $this->_cacheObject->setCache($this->_cacheDataArray);
    }
    
    /**
     * Apply each given command in $_cacheDataArray
     */
    private function action() : void
    {
        if(!empty($this->_cacheDataArray))
        {
            foreach ($this->_cacheDataArray as $operationArray)
            {
                $method = $operationArray[0];
                $this->_applyNumber = $this->$method(implode(',', array_slice($operationArray, 1, NULL, TRUE)));
            }
        }
        
        $this->clearInputArray();
        
        $this->_result = $this->_applyNumber;
    }
    
    /**
     * Add command
     * 
     * @param integer|float $inputNumber
     * @return integer|float
     */
    protected function add($inputNumber)
    {
        return $this->_applyNumber + $inputNumber;
    }
    
    /**
     * Subtract command
     * 
     * @param integer|float $inputNumber
     * @return integer|float
     */
    protected function subtract($inputNumber)
    {
        return $this->_applyNumber - $inputNumber;
    }
    
    /**
     * Divide command
     * 
     * @param integer|float $inputNumber
     * @return integer|float
     */
    protected function divide($inputNumber)
    {
        return $this->_applyNumber / $inputNumber;
    }
    
    /**
     * Multiply command
     * 
     * @param integer|float $inputNumber
     * @return integer|float
     */
    protected function multiply($inputNumber)
    {
        return $this->_applyNumber * $inputNumber;
    }
    
    /**
     * Clears all cache data
     */
    private function clearInputArray() : void
    {
        $this->_cacheDataArray = [];
        $this->_cacheObject->deleteCache();
    }
    
    /**
     * Gets result
     * 
     * @return integer|float|NULL
     */
    public function getResult()
    {
        return $this->_result;
    }
    
    /**
     * Gets all errors
     * 
     * @return array
     */
    public function getErrors() : array
    {
        return $this->_errors;
    }
}
