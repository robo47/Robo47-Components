<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Controller
 * @group Robo47_Controller_Plugin
 * @group Robo47_Controller_Plugin_Title
 */
class Robo47_Controller_Plugin_TitleTest extends PHPUnit_Framework_TestCase
{
    
    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
    }

    /**
     * @covers Robo47_Controller_Plugin_Title
     */
    public function testConstructor()
    {
        $view = new Zend_View();
        $append = 'foo';
        $prepend = 'baa';
        $plugin = new Robo47_Controller_Plugin_Title($view, $append, $prepend);

        $this->assertSame($view, $plugin->getView());

        $this->assertSame($prepend, $plugin->getPrepend());

        $this->assertSame($append, $plugin->getAppend());
    }

    /**
     * @covers Robo47_Controller_Plugin_Title::setView
     * @covers Robo47_Controller_Plugin_Title::getView
     */
    public function testSetViewGetView()
    {
        $view = new Zend_View();
        $plugin = new Robo47_Controller_Plugin_Title($view);

        $this->assertSame($view, $plugin->getView());

        $view2 = new Zend_View();
        $plugin->setView($view2);

        $this->assertSame($view2, $plugin->getView());
    }

    /**
     * @covers Robo47_Controller_Plugin_Title::setAppend
     * @covers Robo47_Controller_Plugin_Title::getAppend
     */
    public function testSetAppendGetAppend()
    {
        $view = new Zend_View();
        $append = 'foo';
        $plugin = new Robo47_Controller_Plugin_Title($view, $append);

        $this->assertEquals('foo', $plugin->getAppend());

        $plugin->setAppend('baa');

        $this->assertEquals('baa', $plugin->getAppend());
    }

    /**
     * @covers Robo47_Controller_Plugin_Title::setPrepend
     * @covers Robo47_Controller_Plugin_Title::getPrepend
     */
    public function testSetPrependGetPrepend()
    {
        $view = new Zend_View();
        $prepend = 'foo';
        $plugin = new Robo47_Controller_Plugin_Title($view, '', $prepend);

        $this->assertEquals('foo', $plugin->getPrepend());

        $plugin->setPrepend('baa');

        $this->assertEquals('baa', $plugin->getPrepend());
    }

    /**
     * @covers Robo47_Controller_Plugin_Title::postDispatch
     */
    public function testPostDispatch()
    {
        $request = new Zend_Controller_Request_Http('http://www.domain.tld');
        $view = new Zend_View();
        $append = 'foo';
        $prepend = 'baa';
        $plugin = new Robo47_Controller_Plugin_Title($view, $append, $prepend);

        $view->headTitle('blub');

        $plugin->postDispatch($request);

        $this->assertEquals('<title>baablubfoo</title>', (string) $view->headTitle());
    }
}