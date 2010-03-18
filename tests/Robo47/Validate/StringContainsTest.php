<?php

require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * @todo test validation-messages
 */
/**
 * @group Robo47_Validate
 * @group Robo47_Validate_StringContains
 */
class Robo47_Validate_StringContainsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Validate_StringContains<extended>
     * @covers Robo47_Validate_StringContains::__construct
     */
    public function testConstruct()
    {
        $validate = new Robo47_Validate_StringContains('foo');
        $this->assertEquals(array('foo'), $validate->getContains());

        $validate = new Robo47_Validate_StringContains(array('foo'));
        $this->assertEquals(array('foo'), $validate->getContains());
    }

    /**
     * @covers Robo47_Validate_StringContains::setContains
     * @covers Robo47_Validate_StringContains::getContains
     */
    public function testGetContainsSetContains()
    {
        $validate = new Robo47_Validate_StringContains('foo');
        $validate->setContains('bla');
        $this->assertEquals(array('bla'), $validate->getContains());

        $validate = new Robo47_Validate_StringContains('foo');
        $validate->setContains(array('bla'));
        $this->assertEquals(array('bla'), $validate->getContains());
    }
    
    public function setContainsExceptionProvider()
    {
        $data = array();

        $data[] = array(null);
        $data[] = array('');
        $data[] = array(array());

        return $data;
    }

    /**
     * @covers Robo47_Validate_StringContains::setContains
     * @covers Robo47_Validate_Exception
     * @dataProvider setContainsExceptionProvider
     */
    public function testSetContainsExceptions($contains)
    {
        $validate = new Robo47_Validate_StringContains('foo');

        try {
            $validate->setContains($contains);
        } catch (Robo47_Validate_Exception $e) {
            $this->assertEquals('$contains is empty', $e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function isValidProvider()
    {
        $data = array();

        $data[] = array('foo', 'f', true);
        $data[] = array('fo', 'o', true);
        $data[] = array('foo', 'foo', true);
        $data[] = array('foo', 'baa', false);
        $data[] = array('foo', 'z', false);
        $data[] = array('baafoo', array('blub', 'bla'), false);
        $data[] = array('baafoo', array('foo', 'bla'), true);

        return $data;
    }

    /**
     * @dataProvider isValidProvider
     * @covers Robo47_Validate_StringContains::isValid
     */
    public function testIsValid($string, $contains, $result)
    {
        $validate = new Robo47_Validate_StringContains($contains);
        $this->assertSame($result, $validate->isValid($string));
    }
}