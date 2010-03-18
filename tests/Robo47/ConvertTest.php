<?php

require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * @group Robo47_Convert
 */
class Robo47_ConvertTest extends PHPUnit_Framework_TestCase
{
    
    public function shorthandToBytesProvider()
    {
        $data = array();
        // don't go over 1g because it will throw exception on 32bit systems

        // a number
        $data[] = array('5', 5);

        // shorthands with lower sign
        $data[] = array('5k', 5 * 1024);
        $data[] = array('5m', 5 * 1024 * 1024);
        $data[] = array('1g', 1 * 1024 * 1024 * 1024);

        // shorthands wit upper sign
        $data[] = array('5K', 5 * 1024);
        $data[] = array('5M', 5 * 1024 * 1024);
        $data[] = array('1G', 1 * 1024 * 1024 * 1024);

        return $data;
    }

    /**
     * @covers Robo47_Convert::shorthandToBytes
     * @dataProvider shorthandToBytesProvider
     */
    public function testShorthandToBytes($shortHand, $value)
    {
        $result = Robo47_Convert::shortHandToBytes($shortHand);
        $this->assertEquals($value, $result);
    }

    /**
     * @covers Robo47_Convert::shorthandToBytes
     * @covers Robo47_Convert_Exception
     */
    public function testShorthandToBytesExceptionOnToBigInput()
    {
        $maxInt = ((string)(((((float)PHP_INT_MAX) + 1) / 1024 / 1024 / 1024) + 1) . 'g');
        try {
            $result = Robo47_Convert::shortHandToBytes($maxInt);
            $this->fail('no exception thrown on too big shorthand');
        } catch (Robo47_Convert_Exception $e) {
            $this->assertEquals('input is greater than PHP_INT_MAX on this plattform (' . PHP_INT_MAX . ')', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Convert::shorthandToBytes
     */
    public function testShorthandToBytesExceptionOnInvalidSign()
    {
        $value = '1234b';
        try {
            $result = Robo47_Convert::shortHandToBytes($value);
            $this->fail('no exception thrown on unknown sign');
        } catch (Robo47_Convert_Exception $e) {
            $this->assertEquals('invalid last sign in ' . $value, $e->getMessage());
        }
    }
}