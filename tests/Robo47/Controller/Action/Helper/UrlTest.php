<?php
require_once dirname(__FILE__) . '/../../../../TestHelper.php';

class Robo47_Controller_Action_Helper_UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Robo47_Controller_Action_Helper_Url<extended>
     * @covers Robo47_Controller_Action_Helper_Url::__construct
     */
    public function testDefaultConstruct()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $urlHelper = new Robo47_Controller_Action_Helper_Url();
        $this->assertSame($router, $urlHelper->getRouter());
    }

    /**
     * @covers Robo47_Controller_Action_Helper_Url::setRouter
     * @covers Robo47_Controller_Action_Helper_Url::getRouter
     */
    public function testSetRouterGetRouter()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router2 = new Zend_Controller_Router_Rewrite();
        $router3 = new Zend_Controller_Router_Rewrite();
        $urlHelper = new Robo47_Controller_Action_Helper_Url($router2);
        $this->assertSame($router2, $urlHelper->getRouter(), 'Wrong Router');
        $return = $urlHelper->setRouter($router3);
        $this->assertSame($urlHelper, $return, 'No Fluent Interface');
        $this->assertSame($router3, $urlHelper->getRouter(), 'Wrong Router');
        $return = $urlHelper->setRouter(null);
        $this->assertSame($router, $urlHelper->getRouter(), 'Wrong Router');
    }

    /**
     * @covers Robo47_Controller_Action_Helper_Url::url
     */
    public function testUrl()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute(
            'user',
            new Zend_Controller_Router_Route('blub/:username',
                                             array('controller' => 'user',
                                                   'action'     => 'info'))
        );

        $router2 = clone $router;
        $router2->addRoute(
            'user',
            new Zend_Controller_Router_Route('user/:username',
                                             array('controller' => 'user',
                                                   'action'     => 'info'))
        );
        $urlHelper = new Robo47_Controller_Action_Helper_Url($router2);
        $url = $urlHelper->url(array('username' => 'foo'), 'user');
        $this->assertEquals('/user/foo', $url, 'Wrong router used');
    }
}