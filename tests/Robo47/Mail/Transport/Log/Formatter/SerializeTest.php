<?php

require_once dirname(__FILE__ ) . '/../../../../../TestHelper.php';

/**
 * @group Robo47_Mail
 * @group Robo47_Mail_Transport
 * @group Robo47_Mail_Transport_Log
 * @group Robo47_Mail_Transport_Log_Formatter
 * @group Robo47_Mail_Transport_Log_Formatter_Serialize
 */
class Robo47_Mail_Transport_Log_Formatter_SerializeTest extends PHPUnit_Framework_TestCase
{
    
    public function mailProvider()
    {
        $data = array();

        $mail1 = new Zend_Mail();
        $mail1->addTo('email@example.com');
        $mail1->setBodyText('Some Text');

        $data[] = array(new Zend_Mail());
        $data[] = array($mail1);


        return $data;
    }

    /**
     *
     * @dataProvider mailProvider
     * @covers Robo47_Mail_Transport_Log_Formatter_Serialize::format
     */
    public function testFormat($mail)
    {
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Serialize();
        $result = $formatter->format($mail);
        $this->assertEquals(serialize($mail), $result, 'Formatting missmatch');
    }
}