<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

function dummyApplicationErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

/**
 * @todo test without registerAsErrorHandler, without Log ...
 */
class Robo47_Application_Resource_ErrorHandlerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->application = new Zend_Application('testing');
        $this->bootstrap   = new Zend_Application_Bootstrap_Bootstrap($this->application);
    }

    public function tearDown()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
        Zend_Registry::_unsetInstance();
        restore_error_handler();
    }

    /**
     * @covers Robo47_Application_Resource_ErrorHandler<extended>
     * @covers Robo47_Application_Resource_ErrorHandler::init
     * @covers Robo47_Application_Resource_ErrorHandler::_setupErrorHandler
     * @covers Robo47_Application_Resource_ErrorHandler::getErrorHandler
     */
    public function testInit()
    {
        $options = array(
            'registerAsErrorHandler' => true,
            'setLogFromRegistryKey' => 'Robo47_Log',
            'logCategory'   => 'foo',
            'errorPriorityMapping' => array(E_ERROR => 5, 'unknown' => 3),
            'registryKey' => 'Robo47_ErrorHandler',
        );

        $log = new Robo47_Log();
        Zend_Registry::set('Robo47_Log', $log);

        $resource = new Robo47_Application_Resource_ErrorHandler($options);
        $resource->init();

        $errorHandler = $resource->getErrorHandler();
        $oldHandler = set_error_handler('dummyApplicationErrorHandler');
        $this->assertNotNull($oldHandler);
        $this->assertSame($log, $errorHandler->getLog());

        $this->assertSame($options['logCategory'], $errorHandler->getLogCategory());
        $this->assertEquals($options['errorPriorityMapping'], $errorHandler->getErrorPriorityMapping());
        $this->assertTrue(Zend_Registry::isRegistered('Robo47_ErrorHandler'));
        $this->assertSame($errorHandler, Zend_Registry::get('Robo47_ErrorHandler'));
    }

    /**
     * @covers Robo47_Application_Resource_ErrorHandler::init
     */
    public function testInitWithEmptyOptions()
    {
        $options = array();
        $resource = new Robo47_Application_Resource_ErrorHandler($options);
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_ErrorHandler.', $e->getMessage());
        }
    }

}