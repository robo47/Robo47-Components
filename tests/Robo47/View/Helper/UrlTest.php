<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @group Robo47_View
 * @group Robo47_View_Helper
 * @group Robo47_View_Helper_Url
 */
class Robo47_View_Helper_UrlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_View_Helper_Url
     */
    protected $_helper = null;
    
    public function setUp()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
        $this->_helper = new Robo47_View_Helper_Url($this->getRouter());
    }
    
    public function tearDown()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
        $this->_helper = null;
    }
    
    public function getRouter()
    {
        $router = new Zend_Controller_Router_Rewrite();
        $router->addRoute('foo', new Zend_Controller_Router_Route_Static('/foo/bar', array()));
        return $router;
    }

    /**
     * @covers Robo47_View_Helper_Url<extended>
     * @covers Robo47_View_Helper_Url::__construct
     */
    public function testConstructorWithRouter()
    {
        $router = $this->getRouter();
        $this->_helper = new Robo47_View_Helper_Url($router);
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Url::__construct
     */
    public function testConstructorWithoutRouter()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $this->_helper = new Robo47_View_Helper_Url();
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Url::getRouter
     * @covers Robo47_View_Helper_Url::setRouter
     */
    public function testSetRouterGetRouter()
    {
        $this->_helper = new Robo47_View_Helper_Url(null);
        $router = new Zend_Controller_Router_Rewrite();
        $this->_helper->setRouter($router);
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Url::url
     */
    public function testUrl()
    {
        $this->_helper->setRouter($this->getRouter());
        $url = $this->_helper->url(array(), 'foo');
        $this->assertEquals('/foo/bar', $url);
    }
}