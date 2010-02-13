<?php

require_once dirname(__FILE__) . '/../TestHelper.php';

class Robo47_ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Robo47_Exception
     */
    public function testClass()
    {
        $reflection = new ReflectionClass('Robo47_Exception');
        $parentClass = $reflection->getParentClass();
        $this->assertEquals('Zend_Exception', $parentClass->name);
    }

    /**
     * @covers Robo47_Exception
     */
    public function testConstruct()
    {
        $e = new Robo47_Exception();
        $this->assertEquals('', $e->getMessage(), 'default message is wrong');
        $this->assertEquals(0,  $e->getCode(), 'default code is wrong');
        $this->assertEquals(null, $e->getPrevious(), 'default previous is wrong');
    }
}