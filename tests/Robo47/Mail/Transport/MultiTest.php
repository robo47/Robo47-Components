<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Mail
 * @group Robo47_Mail_Transport
 * @group Robo47_Mail_Transport_Multi
 */
class Robo47_Mail_Transport_MultiTest extends PHPUnit_Framework_TestCase
{
    
    public function tearDown()
    {
        Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail());
    }

    /**
     * @covers Robo47_Mail_Transport_Multi
     */
    public function testDefaultConstructor()
    {
        $transport = new Robo47_Mail_Transport_Multi();
        $this->assertEquals(array(), $transport->getTransports());
    }

    /**
     * @covers Robo47_Mail_Transport_Multi
     */
    public function testConstruct()
    {
        $t1 = new Robo47_Mail_Transport_MockSimple();
        $t2 = new Robo47_Mail_Transport_MockSimple();
        $transport = new Robo47_Mail_Transport_Multi(array($t1, $t2));
        $this->assertEquals(2, count($transport->getTransports()));
        $this->assertContains($t1, $transport->getTransports(), 'Transport#1 not found in array');
        $this->assertContains($t2, $transport->getTransports(), 'Transport#2 not found in array');
    }
    
    public function testSetTransportsGetTransports()
    {
        $t1 = new Robo47_Mail_Transport_MockSimple();
        $t2 = new Robo47_Mail_Transport_MockSimple();
        $t3 = new Robo47_Mail_Transport_MockSimple();
        $transport = new Robo47_Mail_Transport_Multi();

        $this->assertEquals(0, count($transport->getTransports()));

        $transport->setTransports(array($t1, $t2));

        $this->assertEquals(2, count($transport->getTransports()));

        $this->assertContains($t1, $transport->getTransports(), 'Transport#1 not found in array');
        $this->assertContains($t2, $transport->getTransports(), 'Transport#2 not found in array');

        $transport->setTransports($t3);

        $this->assertEquals(3, count($transport->getTransports()));

        $this->assertContains($t3, $transport->getTransports(), 'Transport#3 not found in array');
    }
    
    public function testAddTransport()
    {
        $t1 = new Robo47_Mail_Transport_MockSimple();
        $t2 = new Robo47_Mail_Transport_MockSimple();
        $transport = new Robo47_Mail_Transport_Multi();
        $transport->addTransport($t1);
        $this->assertEquals(1, count($transport->getTransports()));
        $this->assertContains($t1, $transport->getTransports(), 'Transport#1 not found in array');
        $transport->addTransport($t2);
        $this->assertEquals(2, count($transport->getTransports()));
        $this->assertContains($t1, $transport->getTransports(), 'Transport#1 not found in array');
        $this->assertContains($t2, $transport->getTransports(), 'Transport#2 not found in array');
    }
    
    public function testRemoveTransport()
    {
        $t1 = new Robo47_Mail_Transport_MockSimple();
        $t2 = new Robo47_Mail_Transport_MockSimple();
        $transport = new Robo47_Mail_Transport_Multi(array($t1, $t2));
        $this->assertEquals(2, count($transport->getTransports()));
        $transport->removeTransport($t2);
        $this->assertEquals(1, count($transport->getTransports()));
        $this->assertContains($t1, $transport->getTransports(), 'Transport#1 not found in array');

        $transport->removeTransport('Robo47_Mail_Transport_MockSimple');
        $this->assertEquals(0, count($transport->getTransports()));
    }

    /**
     * @covers Robo47_Mail_Transport_Multi::send
     */
    public function testSendMail()
    {
        $transportMock1 = new Robo47_Mail_Transport_MockSimple();
        $transportMock2 = new Robo47_Mail_Transport_MockSimple();
        $transport = new Robo47_Mail_Transport_Multi();
        $transport->addTransport($transportMock1);
        $transport->addTransport($transportMock2);

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

        $this->assertEquals(1, $transportMock1->count, 'No Message on Transport#1');
        $this->assertSame($mail, $transportMock1->mails[0]['mail'], 'Wrong Mail on Transport');

        $this->assertEquals(1, $transportMock2->count, 'No Message on Transport#2');
        $this->assertSame($mail, $transportMock2->mails[0]['mail'], 'Wrong Mail on Transport');
    }
}