<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Validate
 * @group Robo47_Validate_Uri
 */
class Robo47_Validate_UriTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function isValidProvider()
    {
        $data = array();

        $data[] = array('http://www.domain.tld', true);
        $data[] = array('https://www.domain.tld', true);
        $data[] = array('http://www.dom$ain.tld', true);
        $data[] = array('htttp://www.domain.tld', false);
        $data[] = array('www.domain.tld', false);
        $data[] = array('mailto://www.domain.tld', false);

        return $data;
    }

    /**
     * @dataProvider isValidProvider
     * @covers Robo47_Validate_Uri::isValid
     */
    public function testIsValid($value, $result)
    {
        $validator = new Robo47_Validate_Uri();
        $this->assertSame($result, $validator->isValid($value));
    }
}