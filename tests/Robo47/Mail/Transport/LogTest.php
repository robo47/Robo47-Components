<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Mail
 * @group Robo47_Mail_Transport
 * @group Robo47_Mail_Transport_Log
 */
class Robo47_Mail_Transport_LogTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        unset($this->_transport);
        Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail());
    }

    /**
     * @covers Robo47_Mail_Transport_Log
     */
    public function testDefaultConstructor()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);

        $this->assertSame($log, $transport->getLog(), 'Wrong Log');
        $this->assertSame($formatter, $transport->getFormatter(), 'Wrong Formatter');
    }

    /**
     * @covers Robo47_Mail_Transport_Log
     */
    public function testConstruct()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log, Zend_Log::CRIT, 'someLogCategory');

        $this->assertSame($log, $transport->getLog(), 'Wrong Log');
        $this->assertSame($formatter, $transport->getFormatter(), 'Wrong Formatter');
        $this->assertEquals(Zend_Log::CRIT, $transport->getLogPriority(), 'Wrong priority');
        $this->assertEquals('someLogCategory', $transport->getLogCategory(), 'Wrong category');
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setLog
     * @covers Robo47_Mail_Transport_Log::getLog
     */
    public function testSetLogGetLog()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);
        $log = new Zend_Log(new Robo47_Log_Writer_Mock());
        $transport->setLog($log);
        $this->assertSame($log, $transport->getLog());
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setLogCategory
     * @covers Robo47_Mail_Transport_Log::getLogCategory
     */
    public function testSetLogCategoryGetLogCategory()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);
        $this->assertEquals('mail', $transport->getLogCategory());
        $transport->setLogCategory('foo');
        $this->assertEquals('foo', $transport->getLogCategory());
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setLogPriority
     * @covers Robo47_Mail_Transport_Log::getLogPriority
     */
    public function testSetLogPriorityGetLogPriority()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);
        $this->assertEquals(Zend_Log::INFO, $transport->getLogPriority());
        $transport->setLogPriority(Zend_Log::ERR);
        $this->assertEquals(Zend_Log::ERR, $transport->getLogPriority());
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setFormatter
     * @covers Robo47_Mail_Transport_Log::getFormatter
     */
    public function testSetFormatterGetFormatter()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $formatter2 = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);
        $this->assertEquals($formatter, $transport->getFormatter());
        $transport->setFormatter($formatter2);
        $this->assertEquals($formatter2, $transport->getFormatter());
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setFormatter
     */
    public function testSetFormatterWithString()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $transport = new Robo47_Mail_Transport_Log('Robo47_Mail_Transport_Log_Formatter_Simple', $log);
        $this->assertInstanceOf('Robo47_Mail_Transport_Log_Formatter_Simple', $transport->getFormatter());
        $transport->setFormatter('Robo47_Mail_Transport_Log_Formatter_Serialize');
        $this->assertInstanceOf('Robo47_Mail_Transport_Log_Formatter_Serialize', $transport->getFormatter());
    }

    /**
     * @covers Robo47_Mail_Transport_Log::setFormatter
     */
    public function testSetFormatterWithInvalidObject()
    {
        $log = new Robo47_Log(new Zend_Log_Writer_Mock());
        $transport = new Robo47_Mail_Transport_Log('Robo47_Mail_Transport_Log_Formatter_Simple', $log);
        try {
            $transport->setFormatter(new stdClass());
            $this->fail('No Exception thrown');
        } catch (Robo47_Mail_Transport_Exception $e) {
            $this->assertEquals('formatter is not instance of Robo47_Mail_Transport_Log_Formatter_Interface', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Mail_Transport_Log::send
     */
    public function testSendMail()
    {
        $writer = new Zend_Log_Writer_Mock();
        $log = new Robo47_Log($writer);
        $formatter = new Robo47_Mail_Transport_Log_Formatter_Simple();
        $transport = new Robo47_Mail_Transport_Log($formatter, $log);

        $body = 'Foo';
        $subject = 'Baa';
        $to = 'bla@example.com';
        $from = 'foo@example.com';

        $mail = new Zend_Mail('utf8');
        $mail->setBodyText($body);
        $mail->setBodyHtml('<p>' . $body . '</p>');
        $mail->setSubject($subject);
        $mail->setFrom($from);
        $mail->addTo($to);

        $mail->send($transport);

        $this->assertEquals(1, count($writer->events), 'Wrong count of events');
        $formatted = $formatter->format($mail);
        $this->assertEquals($formatted, $writer->events[0]['message'], 'Wrong message');
    }
}