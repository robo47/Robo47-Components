<?php

require_once dirname(__FILE__) . '/../TestHelper.php';

class Robo47_MockTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Robo47_Mock::__construct
     * @covers Robo47_Mock::_logCall
     */
    public function testConstruct()
    {
        $mock = new Robo47_Mock();
        $this->assertEquals(1, count($mock->mockData['call']));
        $this->assertEquals(0, count($mock->mockData['get']));
        $this->assertEquals(0, count($mock->mockData['set']));

        $this->assertContains(array('__construct', array()), $mock->mockData['call']);
    }

    /**
     * @covers Robo47_Mock::__call
     * @covers Robo47_Mock::_logCall
     */
    public function testCall()
    {
        $mock = new Robo47_Mock();
        $mock->baafoo();

        $this->assertEquals(2, count($mock->mockData['call']));
        $this->assertEquals(0, count($mock->mockData['get']));
        $this->assertEquals(0, count($mock->mockData['set']));

        $this->assertContains(array('__construct', array()), $mock->mockData['call']);
        $this->assertContains(array('baafoo', array()), $mock->mockData['call']);

        $mock->blub('bla');

        $this->assertEquals(3, count($mock->mockData['call']));
        $this->assertEquals(0, count($mock->mockData['get']));
        $this->assertEquals(0, count($mock->mockData['set']));

        $this->assertContains(array('__construct', array()), $mock->mockData['call']);
        $this->assertContains(array('baafoo', array()), $mock->mockData['call']);
        $this->assertContains(array('blub', array('bla')), $mock->mockData['call']);
    }

    /**
     * @covers Robo47_Mock::__set
     * @covers Robo47_Mock::__get
     * @covers Robo47_Mock::_logSet
     * @covers Robo47_Mock::_logGet
     */
    public function testSetGet()
    {
        $mock = new Robo47_Mock();
        $mock->foo = 'bla';

        $this->assertEquals(1, count($mock->mockData['call']));
        $this->assertEquals(0, count($mock->mockData['get']));
        $this->assertEquals(1, count($mock->mockData['set']));

        $this->assertContains(array('__construct', array()), $mock->mockData['call']);
        $this->assertContains(array('foo', 'bla'), $mock->mockData['set']);

        $val = $mock->foo;

        $this->assertEquals(1, count($mock->mockData['call']));
        $this->assertEquals(1, count($mock->mockData['get']));
        $this->assertEquals(1, count($mock->mockData['set']));

        $this->assertContains('foo', $mock->mockData['get']);

        $this->assertEquals('bla', $val);

        $val = $mock->unsetValue;

        $this->assertEquals(1, count($mock->mockData['call']));
        $this->assertEquals(2, count($mock->mockData['get']));
        $this->assertEquals(1, count($mock->mockData['set']));

        $this->assertContains('unsetValue', $mock->mockData['get']);

        $this->assertNull($val);
    }
}