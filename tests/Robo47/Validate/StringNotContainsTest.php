<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @todo test validation-messages
 */
/**
 * @group Robo47_Validate
 * @group Robo47_Validate_StringNotContains
 */
class Robo47_Validate_StringNotContainsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function isValidProvider()
    {
        $data = array();

        $data[] = array('asdf', 'sd', false);
        $data[] = array('asdf', 'asdf', false);
        $data[] = array('asdf', 'a', false);
        $data[] = array('asdf', 'foo', true);
        $data[] = array('asdf', 'z', true);

        return $data;
    }

    /**
     * @dataProvider isValidProvider
     * @covers Robo47_Validate_StringNotContains::isValid
     */
    public function testIsValid($string, $contains, $result)
    {
        $validate = new Robo47_Validate_StringNotContains($contains);
        $this->assertSame($result, $validate->isValid($string));
    }

    /**
     * @covers Robo47_Validate_StringNotContains<extended>
     * @covers Robo47_Validate_StringNotContains::__construct
     */
    public function testConstruct()
    {
        $validate = new Robo47_Validate_StringNotContains('foo');
        $this->assertEquals(array('foo'), $validate->getContains());

        $validate = new Robo47_Validate_StringNotContains(array('foo'));
        $this->assertEquals(array('foo'), $validate->getContains());
    }
}