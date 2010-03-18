<?php

require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * @todo test validation messages
 */
/**
 * @group Robo47_Validate
 * @group Robo47_Validate_Mock
 */
class Robo47_Validate_MockTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Validate_Mock<extended>
     * @covers Robo47_Validate_Mock::__construct
     */
    public function testConstruct()
    {
        $validator = new Robo47_Validate_Mock(true, array('foo'), array('baa'));
        $this->assertTrue($validator->isValid);
        $this->assertSame(array('foo'), $validator->getMessages());
        $this->assertSame(array('baa'), $validator->getErrors());

        $this->assertSame(array('foo'), $validator->messages);
        $this->assertSame(array('baa'), $validator->errors);
    }

    /**
     * @covers Robo47_Validate_Mock::getMessages
     */
    public function testGetMessages()
    {
        $validator = new Robo47_Validate_Mock();
        $validator->messages = array('foo');
        $this->assertEquals(array('foo'), $validator->getMessages());
    }

    /**
     * @covers Robo47_Validate_Mock::getErrors
     */
    public function testGetErrors()
    {
        $validator = new Robo47_Validate_Mock();
        $validator->errors = array('foo');
        $this->assertEquals(array('foo'), $validator->getErrors());
    }

    /**
     * @covers Robo47_Validate_Mock::isValid
     */
    public function testIsValid()
    {
        $validator = new Robo47_Validate_Mock(true);
        $this->assertTrue($validator->isValid('foo'));
        $validator = new Robo47_Validate_Mock(false);
        $this->assertFalse($validator->isValid('foo'));
    }

    /**
     * @covers Robo47_Validate_Mock::isValid
     */
    public function testIsValidFillsLastValue()
    {
        $validator = new Robo47_Validate_Mock(true);
        $validator->isValid('foo');
        $this->assertEquals('foo', $validator->lastValue);
        $validator->isValid('baa');
        $this->assertEquals('baa', $validator->lastValue);
    }
}