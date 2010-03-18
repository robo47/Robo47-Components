<?php

require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * @group Robo47_ErrorException
 */
class Robo47_ErrorExceptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_ErrorException
     */
    public function testConstruct()
    {
        $reflection = new ReflectionClass('Robo47_ErrorException');
        $parentClass = $reflection->getParentClass();
        $this->assertEquals('ErrorException', $parentClass->name);
    }
}