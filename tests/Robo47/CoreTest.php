<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

/**
 * @group Robo47_Core
 */
class Robo47_CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function typeProvider()
    {
        $data = array();

        $data[] = array(true, 'boolean');
        $data[] = array('string', 'string');
        $data[] = array(0, 'integer');
        $data[] = array(0.0, 'double');
        $data[] = array(array(), 'array');
        $data[] = array(new stdClass(), 'stdClass');
        $data[] = array(null, 'null');
        $data[] = array(opendir(dirname(__FILE__ )), 'resource');

        return $data;
    }

    /**
     * @covers Robo47_Core::getType
     * @dataProvider typeProvider
     */
    public function testGetType($var, $expectedType)
    {
        $this->assertEquals($expectedType, Robo47_Core::getType($var));
    }
}