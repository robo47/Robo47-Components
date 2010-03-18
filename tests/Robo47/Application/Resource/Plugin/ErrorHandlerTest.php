<?php

require_once dirname(__FILE__ ) . '/../../../../TestHelper.php';

/**
 * @todo test with only module, only controller, only action and without all of them
 * @group Robo47_Application
 * @group Robo47_Application_Resource
 * @group Robo47_Application_Resource_Plugin
 * @group Robo47_Application_Resource_Plugin_ErrorHandler
 */
class Robo47_Application_Resource_Plugin_ErrorHandlerTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
    }
    
    public function tearDown()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
        Zend_Registry::_unsetInstance();
    }

    /**
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler<extended>
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::init
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::_setupErrorHandler
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::getErrorHandler
     */
    public function testInit()
    {
        $options = array(
            'module' => 'default',
            'controller' => 'Foo',
            'action' => 'Baa',
        );

        $resource = new Robo47_Application_Resource_Plugin_ErrorHandler($options);
        $resource->init();

        $fc = Zend_Controller_Front::getInstance();
        $errorHandler = $fc->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        /* @var $errorHandler Zend_Controller_Plugin_ErrorHandler */
        $this->assertEquals('default', $errorHandler->getErrorHandlerModule());
        $this->assertEquals('Foo', $errorHandler->getErrorHandlerController());
        $this->assertEquals('Baa', $errorHandler->getErrorHandlerAction());
        $this->assertType('Zend_Controller_Plugin_ErrorHandler', $resource->getErrorHandler());
        $this->assertSame($errorHandler, $resource->getErrorHandler());
    }

    /**
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::init
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::_setupErrorHandler
     * @covers Robo47_Application_Resource_Plugin_ErrorHandler::getErrorHandler
     */
    public function testInitWithoutConfig()
    {
        $resource = new Robo47_Application_Resource_Plugin_ErrorHandler(array());
        $resource->init();

        $fc = Zend_Controller_Front::getInstance();
        $errorHandler = $fc->getPlugin('Zend_Controller_Plugin_ErrorHandler');

        /* @var $errorHandler Zend_Controller_Plugin_ErrorHandler */
        $this->assertEquals(Zend_Controller_Front::getInstance()->getDispatcher()->getDefaultModule(), $errorHandler->getErrorHandlerModule());
        $this->assertEquals('error', $errorHandler->getErrorHandlerController());
        $this->assertEquals('error', $errorHandler->getErrorHandlerAction());
        $this->assertType('Zend_Controller_Plugin_ErrorHandler', $resource->getErrorHandler());
        $this->assertSame($errorHandler, $resource->getErrorHandler());
    }
}