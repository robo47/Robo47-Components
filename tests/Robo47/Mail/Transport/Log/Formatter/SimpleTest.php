<?php

require_once dirname(__FILE__ ) . '/../../../../../TestHelper.php';

/**
 * @group Robo47_Mail
 * @group Robo47_Mail_Transport
 * @group Robo47_Mail_Transport_Log
 * @group Robo47_Mail_Transport_Log_Formatter
 * @group Robo47_Mail_Transport_Log_Formatter_Simple
 */
class Robo47_Mail_Transport_Log_Formatter_SimpleTest extends PHPUnit_Framework_TestCase
{
    
    public function mailProvider()
    {
        $data = array();

        $mail = new Zend_Mail();
        $mail->setSubject('Some Mail to log');
        $mail->addTo('email@example.com');
        $mail->setBodyText('Some Text');
        $mail->setBodyHtml('<div>Some Html</div>');

        $message = 'Subject: ' . $mail->getSubject() . PHP_EOL;
        $message .= 'To: ' . implode(', ', $mail->getRecipients()) . PHP_EOL;
        $message .= 'Text: ' . $mail->getBodyText()->getContent() . PHP_EOL . PHP_EOL;
        $message .= 'Html: ' . $mail->getBodyHtml()->getContent();

        $data[] = array($mail, $message);

        return $data;
    }

    /**
     * @dataProvider mailProvider
     * @covers Robo47_Mail_Transport_Log_Formatter_Simple::format
     */
    public function testFormat($mail, $message)
    {
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $result = $formatter->format($mail);
        $this->assertEquals($message, $result, 'Formatting missmatch');
    }
}