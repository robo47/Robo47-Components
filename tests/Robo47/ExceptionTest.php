<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

/**
 * @group Robo47_Exception
 */
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

}