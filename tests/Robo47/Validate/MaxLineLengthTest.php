<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Validate
 * @group Robo47_Validate_MaxLineLength
 */
class Robo47_Validate_MaxLineLengthTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Validate_MaxLineLength::__construct
     */
    public function testDefaultConstruct()
    {
        $validator = new Robo47_Validate_MaxLineLength();
        $this->assertEquals(80, $validator->getLineLength());
        $this->assertEquals('utf-8', $validator->getEncoding());
    }

    /**
     * @covers Robo47_Validate_MaxLineLength<extended>
     * @covers Robo47_Validate_MaxLineLength::__construct
     */
    public function testConstruct()
    {
        $validator = new Robo47_Validate_MaxLineLength(90, 'iso-8859-1');
        $this->assertEquals(90, $validator->getLineLength());
        $this->assertEquals('iso-8859-1', $validator->getEncoding());
    }

    /**
     * @covers Robo47_Validate_MaxLineLength::setEncoding
     * @covers Robo47_Validate_MaxLineLength::getEncoding
     */
    public function testSetGetEncoding()
    {
        $validator = new Robo47_Validate_MaxLineLength();
        $return = $validator->setEncoding('iso-8859-1');
        $this->assertSame($validator, $return, 'No Fluent Interface');
        $this->assertEquals('iso-8859-1', $validator->getEncoding());
    }
    
    public function validLineLengthProvider()
    {
        $data = array();
        $data[] = array(1, 1);
        $data[] = array(10, 10);
        return $data;
    }

    /**
     * @dataProvider validLineLengthProvider
     * @covers Robo47_Validate_MaxLineLength::getLineLength
     * @covers Robo47_Validate_MaxLineLength::setLineLength
     */
    public function testSetGetLineLength($lineLength, $expected)
    {
        $validator = new Robo47_Validate_MaxLineLength();
        $return = $validator->setLineLength($lineLength);
        $this->assertSame($validator, $return, 'No Fluent Interface');
        $this->assertEquals($expected, $validator->getLineLength());
    }
    
    public function invalidLineLengthBelowOneProvider()
    {
        $data = array();
        $data[] = array(0);
        $data[] = array( - 1);
        $data[] = array('-1');
        $data[] = array('');
        return $data;
    }

    /**
     * @dataProvider invalidLineLengthBelowOneProvider
     * @covers Robo47_Validate_MaxLineLength::setLineLength
     * @covers Robo47_Validate_Exception
     */
    public function testSetLineLengthBelowOneThrowsException($lineLength)
    {
        $validator = new Robo47_Validate_MaxLineLength();
        try {
            $validator->setLineLength($lineLength);
            $this->fail('No exception thrown');
        } catch (Robo47_Validate_Exception  $e) {
            $this->assertEquals('lineLength is less than 1', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Validate_MaxLineLength::isValid
     */
    public function testIsValidFillsErrorMessages()
    {
        $validator = new Robo47_Validate_MaxLineLength();
        $validator->isValid(str_repeat('a', 100));
        $message = array(Robo47_Validate_MaxLineLength::LINE_TOO_LONG => 'Line 1 is too long');
        $this->assertEquals($message, $validator->getMessages(), 'Wrong Validation Message');

        $text = str_repeat('a', 79) . PHP_EOL .
            str_repeat('a', 80) . PHP_EOL .
            str_repeat('a', 81) . PHP_EOL;
        $validator = new Robo47_Validate_MaxLineLength();
        $validator->isValid($text);
        $message = array(Robo47_Validate_MaxLineLength::LINE_TOO_LONG => 'Line 3 is too long');
        $this->assertEquals($message, $validator->getMessages(), 'Wrong Validation Message');
    }
    
    public function isValidProvider()
    {
        $data = array();
        $data[] = array(str_repeat('a', 80), true);
        $data[] = array(str_repeat('a', 81), false);
        $data[] = array(str_repeat('ä', 80), true);
        $data[] = array(str_repeat('ä', 81), false);

        $code = str_repeat('ä', 40) . PHP_EOL;
        $code .= str_repeat('ä', 80) . PHP_EOL;
        $code .= str_repeat('ä', 40) . PHP_EOL;
        $data[] = array($code, true);

        $code = str_repeat('ä', 81) . PHP_EOL;
        $code .= str_repeat('ä', 81) . PHP_EOL;
        $code .= str_repeat('ä', 81) . PHP_EOL;
        $data[] = array($code, false);

        return $data;
    }

    /**
     * @dataProvider isValidProvider
     * @covers Robo47_Validate_MaxLineLength::isValid
     */
    public function testIsValid($value, $isValid)
    {
        $validator = new Robo47_Validate_MaxLineLength(80);
        $this->assertEquals($isValid, $validator->isValid($value), 'with string ' . mb_strwidth($value, 'utf-8'));

        if ($isValid) {
            $this->assertEquals(0, count($validator->getErrors()));
        } else {
            $this->assertEquals(1, count($validator->getErrors()));
        }
    }
}