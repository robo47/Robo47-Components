<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @group Robo47_Mail
 * @group Robo47_Mail_Transport
 * @group Robo47_Mail_Transport_MockSimple
 */
class Robo47_Mail_Transport_MockSimpleTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Robo47_Mail_Transport_MockSimple
     */
    protected $_transport = null;
    
    public function setUp()
    {
        $this->_transport = new Robo47_Mail_Transport_MockSimple();
        Zend_Mail::setDefaultTransport($this->_transport);
    }
    
    public function tearDown()
    {
        unset($this->_transport);
        Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail());
    }

    /**
     * @covers Robo47_Mail_Transport_MockSimple<extended>
     */
    public function testConstruct()
    {
        $transport = new Robo47_Mail_Transport_MockSimple();
    }

    /**
     * @covers Robo47_Mail_Transport_MockSimple::_sendMail
     */
    public function testSendMail()
    {
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

        $mail->send();

        $this->assertEquals(1, $this->_transport->count);

        $this->assertArrayHasKey('mail', $this->_transport->mails[0]);
        $this->assertArrayHasKey('subject', $this->_transport->mails[0]);
        $this->assertArrayHasKey('from', $this->_transport->mails[0]);

        $this->assertSame($mail, $this->_transport->mails[0]['mail']);
        $this->assertEquals($subject, $this->_transport->mails[0]['subject']);
        $this->assertEquals($from, $this->_transport->mails[0]['from']);
    }
}