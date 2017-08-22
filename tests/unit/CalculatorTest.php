<?php

use PHPUnit\Framework\TestCase as Testcase;
use Calculator\Calculator;
use Calculator\Classes\CalculatorCache;

/**
 * CalculatorTest
 *
 * @author Ilya Panovskiy <panovskiy1980@mail.ru>
 */
class CalculatorTest extends Testcase
{
    public function testCreatingObject()
    {
        $calculator = new Calculator('add 1', '_test');
        
        $this->assertTrue($calculator instanceof Calculator);
        
        $reflection = new \ReflectionClass($calculator);
        
        $inputTest = $reflection->getProperty('_input');
        $inputTest->setAccessible(TRUE);
        
        $this->assertTrue(is_array($inputTest->getValue($calculator)));
        
        $cacheConfigTest = $reflection->getProperty('_cacheConfig');
        $cacheConfigTest->setAccessible(TRUE);
        
        $this->assertArrayHasKey('cache_class', $cacheConfigTest->getValue($calculator));
        
        $cacheObjectTest = $reflection->getProperty('_cacheObject');
        $cacheObjectTest->setAccessible(TRUE);
        
        $this->assertTrue($cacheObjectTest->getValue($calculator) instanceof CalculatorCache);
        
        $cacheDataArray = $reflection->getProperty('_cacheDataArray');
        $cacheDataArray->setAccessible(TRUE);
        
        $this->assertTrue(is_array($cacheDataArray->getValue($calculator)));
    }
    
    public function testIfCommand()
    {
        new Calculator('apply 1', '_test'); //delete previous data if exists in cache
        
        $calculator = new Calculator('apply 1', '_test');
        
        $this->assertEquals(1, $calculator->getResult());
    }
    
    public function testCheckOperation()
    {
        $calculator = new Calculator('abc 1', '_test');
        
        $this->assertTrue(!empty($calculator->getErrors()));
        
        $calculator = new Calculator('add 1 3', '_test');
        
        $this->assertTrue(!empty($calculator->getErrors()));
        
        $calculator = new Calculator('apply 1 3', '_test');
        
        $this->assertTrue(empty($calculator->getErrors()));
    }
    
    public function testSetCacheData()
    {
        $calculator = new Calculator('add 1', '_test');
        
        $reflection = new \ReflectionClass($calculator);
        
        $cacheObjectTest = $reflection->getProperty('_cacheObject');
        $cacheObjectTest->setAccessible(TRUE);
        $cacheObjectTest->getValue($calculator)->setCache([0 => ['add', 2]]);
        
        $this->assertEquals([0 => ['add', 2]], $cacheObjectTest->getValue($calculator)->getCache());
        
        new Calculator('apply 1', '_test'); //delete all data
    }
    
    public function testAction()
    {
        new Calculator('add 5', '_test');
        new Calculator('subtract 1', '_test');
        new Calculator('multiply 3', '_test');
        new Calculator('divide 5', '_test');
        $calculator = new Calculator('apply 1', '_test');
        
        $this->assertEquals(3, $calculator->getResult());
    }
}
