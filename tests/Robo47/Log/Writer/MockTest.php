<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Log
 * @group Robo47_Log_Writer
 * @group Robo47_Log_Writer_Mock
 */
class Robo47_Log_Writer_MockTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Log_Writer_Mock
     */
    public function testConstruct()
    {
        $writer = new Robo47_Log_Writer_Mock('foo', 'baa');
        $this->assertEquals(array('foo', 'baa'), $writer->constructorParams);
    }

    /**
     * @covers Robo47_Log_Writer_Mock::_write
     */
    public function testWrite()
    {
        $writer = new Robo47_Log_Writer_Mock();

        $this->assertEquals(0, count($writer->events));

        $writer->write(array('foo' => 'bla'));

        $this->assertEquals(1, count($writer->events));

        $this->assertArrayHasKey(0, $writer->events);
        $this->assertArrayHasKey('foo', $writer->events[0]);
        $this->assertEquals('bla', $writer->events[0]['foo']);
    }

    /**
     * @covers Robo47_Log_Writer_Mock::shutdown
     */
    public function testShutdown()
    {
        $writer = new Robo47_Log_Writer_Mock();

        $this->assertFalse($writer->shutdown);
        $writer->shutdown();
        $this->assertTrue($writer->shutdown);
    }

    /**
     * @covers Robo47_Log_Writer_Mock::factory
     */
    public function testFactory()
    {
        $config = array('foo');
        $writer = Robo47_Log_Writer_Mock::factory($config);
        $this->assertInstanceOf('Robo47_Log_Writer_Mock', $writer, 'Wrong datatype from factory');
        $this->assertEquals(array($config), $writer->constructorParams, 'Constructor Params are wrong');
    }

    /**
     * @covers Robo47_Log_Writer_Mock::factory
     */
    public function testFactoryWithConfig()
    {
        $config = new Zend_Config(array('foo'));
        $writer = Robo47_Log_Writer_Mock::factory($config);
        $this->assertInstanceOf('Robo47_Log_Writer_Mock', $writer, 'Wrong datatype from factory');
        $this->assertEquals(array($config), $writer->constructorParams, 'Constructor Params are wrong');
    }
}