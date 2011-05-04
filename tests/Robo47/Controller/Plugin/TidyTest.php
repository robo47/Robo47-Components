<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Controller
 * @group Robo47_Controller_Plugin
 * @group Robo47_Controller_Plugin_Tidy
 */
class Robo47_Controller_Plugin_TidyTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
    }

    public function getPluginWithLogging($valid = true)
    {
        $mockWriter = new Robo47_Log_Writer_Mock();
        $log = new Robo47_Log($mockWriter);

        $response = new Zend_Controller_Response_HttpTestCase();
        $response->setRawHeader('Content-Type: text/html');
        $response->setBody('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><html><head><title>foo</title></head><body><div><a href="#foo" target="_blank">foo</a></div></body></html>');
        if (false === $valid) {
            $response->setBody('<html><head><body><div><a href="#foo" target="_blank">foo</a></div></body></html>');
        }

        $request = new Zend_Controller_Request_Http('http://www.example.com/baa/foo');

        $config = array('doctype' => 'strict');
        $filter = new Robo47_Filter_Tidy(null, $config);

        $plugin = new Robo47_Controller_Plugin_Tidy($filter, $log);
        $plugin->setResponse($response);
        $plugin->setRequest($request);
        return $plugin;
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy
     */
    public function testDefaultConstructor()
    {
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $this->assertNull($plugin->getLog(), 'Log is not null');
        $this->assertEquals('tidy', $plugin->getLogCategory(), 'LogCategory is wrong');
        $this->assertInstanceOf('Robo47_Filter_Tidy', $plugin->getTidyFilter(), 'LogCategory is wrong');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::setLog
     * @covers Robo47_Controller_Plugin_Tidy::getLog
     */
    public function testSetLogGetLog()
    {
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $log = new Zend_Log(new Robo47_Log_Writer_Mock());
        $plugin->setLog($log);
        $this->assertSame($log, $plugin->getLog());
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::setTidyFilter
     * @covers Robo47_Controller_Plugin_Tidy::getTidyFilter
     */
    public function testSetTidyFilterGetTidyFilter()
    {
        $filter = new Robo47_Filter_Tidy();
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $return = $plugin->setTidyFilter($filter);
        $this->assertSame($plugin, $return, 'No Fluent Interface');
        $this->assertSame($filter, $plugin->getTidyFilter());
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::setTidyFilter
     */
    public function setTidyFilterWithNull()
    {
        $filter = new Robo47_Filter_Tidy();
        $plugin = new Robo47_Controller_Plugin_Tidy($filter);
        $plugin->setTidyFilter(null);
        $this->assertInstanceOf('Robo47_Filter_Tidy', $plugin->getTidyFilter(), 'LogCategory is wrong');
        $this->assertNotSame($filter, $plugin->getTidyFilter(), 'Wrong filter');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::setLogCategory
     * @covers Robo47_Controller_Plugin_Tidy::getLogCategory
     */
    public function testSetLogCategoryGetLogCategory()
    {
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $this->assertEquals('tidy', $plugin->getLogCategory());
        $plugin->setLogCategory('foo');
        $this->assertEquals('foo', $plugin->getLogCategory());
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::setLogPriority
     * @covers Robo47_Controller_Plugin_Tidy::getLogPriority
     */
    public function testSetLogPriorityGetLogPriority()
    {
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $this->assertEquals(Zend_Log::INFO, $plugin->getLogPriority());
        $plugin->setLogPriority(Zend_Log::ERR);
        $this->assertEquals(Zend_Log::ERR, $plugin->getLogPriority());
    }

    public function htmlResponseProvider()
    {
        $data = array();

        $response1 = new Zend_Controller_Response_HttpTestCase();
        $response1->setHeader('Content-Type', 'text/html');
        $data[] = array ($response1, true, 'Content-Type: text/html');

        $response2 = new Zend_Controller_Response_HttpTestCase();
        $response2->setHeader('Content-Type', 'text/xml');
        $data[] = array ($response2, false, 'Content-Type: text/xml');

        $response3 = new Zend_Controller_Response_HttpTestCase();
        $response3->setRawHeader('Content-Type: text/html');
        $data[] = array ($response3, true, 'Content-Type: text/html');

        $response4 = new Zend_Controller_Response_HttpTestCase();
        $response4->setRawHeader('Content-Type: text/html; charset=utf-8');
        $data[] = array ($response4, true, 'Content-Type: text/html; charset=utf-8');

        $response5 = new Zend_Controller_Response_HttpTestCase();
        $response5->setHeader('Content-Type', 'text/html; charset=utf-8');
        $data[] = array ($response5, true, 'Content-Type: text/html; charset=utf-8');

        $response6 = new Zend_Controller_Response_HttpTestCase();
        $response6->setRawHeader('ConTent-type: TEXT/HTML; charset=utf-8');
        $data[] = array ($response6, true, 'ConTent-type: TEXT/HTML; charset=utf-8');

        $response7 = new Zend_Controller_Response_HttpTestCase();
        $response7->setRawHeader('Content-Type: text/xml');
        $data[] = array ($response7, false, 'Content-Type: text/xml');

        return $data;
    }

    /**
     * @dataProvider htmlResponseProvider
     * @covers Robo47_Controller_Plugin_Tidy::isHtmlResponse
     */
    public function testIsHtmlResponse($response, $result, $header)
    {
        $plugin = new Robo47_Controller_Plugin_Tidy();
        $this->assertEquals($result, $plugin->isHtmlResponse($response), 'Wrong return for isHtmlResponse()');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::dispatchLoopShutdown
     */
    public function testDispatchLoopShutdownNotRunsOnNonHtmlResponses()
    {
        $plugin = $this->getPluginWithLogging(false);
        $plugin->getResponse()
            ->clearAllHeaders()
            ->setHeader('Content-Type', 'text/xml');

        $plugin->dispatchLoopShutdown();

        $writers = $plugin->getLog()->getWriters();

        $this->assertEquals(0, count($writers[0]->events), 'Events logged');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::dispatchLoopShutdown
     */
    public function testLogWithInvalidCode()
    {
        $plugin = $this->getPluginWithLogging(false);

        $plugin->dispatchLoopShutdown();

        $writers = $plugin->getLog()->getWriters();

        $this->assertEquals(1, count($writers[0]->events), 'No Event logged');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::dispatchLoopShutdown
     */
    public function testLogWithValidCode()
    {
        $plugin = $this->getPluginWithLogging(true);

        $plugin->dispatchLoopShutdown();

        $writers = $plugin->getLog()->getWriters();

        $this->assertEquals(0, count($writers[0]->events), 'Event logged');
    }

    /**
     * @covers Robo47_Controller_Plugin_Tidy::dispatchLoopShutdown
     */
    public function testLogContainsUrl()
    {
        $plugin = $this->getPluginWithLogging(false);
        $plugin->dispatchLoopShutdown();

        $writers = $plugin->getLog()->getWriters();

        $this->assertEquals(1, count($writers[0]->events), 'No Event logged');
        $this->assertContains('Url: /baa/foo' . PHP_EOL, $writers[0]->events[0]['message'], 'Url not logged');
    }
// @todo test log contains tidy-message
}