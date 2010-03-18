<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_View
 * @group Robo47_View_Helper
 * @group Robo47_View_Helper_Anchor
 */
class Robo47_View_Helper_AnchorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_View_Helper_Anchor
     */
    protected $_helper = null;
    /**
     * @var Zend_View
     */
    protected $_view = null;
    
    public function setUp()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
        $this->_helper = new Robo47_View_Helper_Anchor($this->getRouter());
        $this->_helper->setView($this->getView());
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
    
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = new Zend_View();
            // doctype needed because of usage of cdata in HeadScript-Helper
            $this->_view->doctype('XHTML1_STRICT');
        }
        return $this->_view;
    }

    /**
     * @covers Robo47_View_Helper_Anchor<extended>
     * @covers Robo47_View_Helper_Anchor::__construct
     */
    public function testDefaultConstruct()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $this->_helper = new Robo47_View_Helper_Anchor();
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Anchor::__construct
     */
    public function testConstructorWithRouter()
    {
        $router = $this->getRouter();
        $this->_helper = new Robo47_View_Helper_Anchor($router);
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Anchor::getRouter
     * @covers Robo47_View_Helper_Anchor::setRouter
     */
    public function testSetRouterGetRouter()
    {

        $this->_helper = new Robo47_View_Helper_Anchor(null);
        $router = new Zend_Controller_Router_Rewrite();
        $this->_helper->setRouter($router);
        $this->assertSame($router, $this->_helper->getRouter());
    }

    /**
     * @covers Robo47_View_Helper_Anchor::anchor
     */
    public function testAnchor()
    {
        $this->_helper->setRouter($this->getRouter());
        $anchor = $this->_helper->anchor(array(), 'foo', 'Bla');

        $this->assertEquals('<a href="/foo/bar">Bla</a>', $anchor);
    }

    /**
     * @covers Robo47_View_Helper_Anchor::anchor
     */
    public function testAnchorWithParams()
    {
        $this->_helper->setRouter($this->getRouter());
        $anchor = $this->_helper->anchor(array(), 'foo', 'Bla', array('style' => 'color: red;'));

        $this->assertEquals('<a href="/foo/bar" style="color: red;">Bla</a>', $anchor);
    }
}