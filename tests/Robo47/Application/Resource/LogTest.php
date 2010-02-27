<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Application_Resource_LogTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
        Zend_Registry::_unsetInstance();
    }
    
    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
    }

    /**
     * @covers Robo47_Application_Resource_Log<extended>
     * @covers Robo47_Application_Resource_Log::init
     * @covers Robo47_Application_Resource_Log::_setupLog
     * @covers Robo47_Application_Resource_Log::getLog
     */
    public function testInit()
    {
        $options = array(
            array(
                'writerName' => 'Mock',
                'writerNamespace' => 'Robo47_Log_Writer',
                'writerParams' => array(
                ),
            ),
            'registryKey' => 'Foo_Baa',
        );

        $resource = new Robo47_Application_Resource_Log($options);
        $resource->init();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']), 'No Entry in Log');
        $log = Zend_Registry::get($options['registryKey']);
        $this->assertType('Robo47_Log', $log, 'Log is not Robo47_Log');
        $this->assertSame($resource->getLog(), $log, 'Not Same Logs');
    }

    /**
     * @covers Robo47_Application_Resource_Log::init
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutConfig()
    {
        $resource = new Robo47_Application_Resource_Log(array());

        try {
            $resource->init();
            $this->fail('no exception thrown on init without config');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Log.', $e->getMessage());
        }
    }
}