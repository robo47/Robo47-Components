<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Log
 * @group Robo47_Log_Filter
 * @group Robo47_Log_Filter_Mock
 */
class Robo47_Log_Filter_MockTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Log_Filter_Mock
     */
    public function testDefaultConstructor()
    {
        $filter = new Robo47_Log_Filter_Mock();
        $this->assertTrue($filter->accept, 'Wrong value for accept');
    }

    /**
     * @covers Robo47_Log_Filter_Mock::accept
     */
    public function testFilter()
    {
        $filter = new Robo47_Log_Filter_Mock(true);
        $this->assertTrue($filter->accept(array()));

        $filter = new Robo47_Log_Filter_Mock(false);
        $this->assertFalse($filter->accept(array()));

        $filter = new Robo47_Log_Filter_Mock(false, 'foo', 'bar');
        $this->assertEquals(array(false, 'foo', 'bar'), $filter->constructorParams);
    }

    /**
     * @covers Robo47_Log_Filter_Mock::getOptions
     */
    public function testGetOptions()
    {
        $filter = new Robo47_Log_Filter_Mock();
        $options = $filter->getOptions();
        $this->assertEquals(array(), $options, 'Options are wrong');
    }

    /**
     * @covers Robo47_Log_Filter_Mock::factory
     */
    public function testFactory()
    {
        $config = array('foo');
        $filter = Robo47_Log_Filter_Mock::factory($config);
        $this->assertInstanceOf('Robo47_Log_Filter_Mock', $filter, 'Wrong datatype from factory');
        $this->assertEquals(array($config), $filter->constructorParams, 'Constructor Params are wrong');
    }

    /**
     * @covers Robo47_Log_Filter_Mock::factory
     */
    public function testFactoryWithConfig()
    {
        $config = new Zend_Config(array('foo'));
        $filter = Robo47_Log_Filter_Mock::factory($config);
        $this->assertInstanceOf('Robo47_Log_Filter_Mock', $filter, 'Wrong datatype from factory');
        $this->assertEquals(array($config), $filter->constructorParams, 'Constructor Params are wrong');
    }
}