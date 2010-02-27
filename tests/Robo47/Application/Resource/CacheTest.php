<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

// @todo test frontend/backend-options -> how ?
class Robo47_Application_Resource_CacheTest extends PHPUnit_Framework_TestCase
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
    
    public function initProvider()
    {
        $data = array();

        $options1 = array(
            'frontendName' => 'Core',
            'backendName' => 'File',
        );

        $options2 = array(
            'frontendName' => 'Core',
            'backendName' => 'File',
            'frontendOptions' => array(),
            'backendOptions' => array(),
            'customFrontendNaming' => false,
            'customBackendNaming' => false,
            'autoload' => false,
        );

        $options3 = array(
            'frontendName' => 'Core',
            'backendName' => 'Robo47_Cache_Backend_Array',
            'frontendOptions' => array(),
            'backendOptions' => array(),
            'customFrontendNaming' => false,
            'customBackendNaming' => true,
            'autoload' => false,
        );



        $data[] = array($options1, 'Zend_Cache_Core', 'Zend_Cache_Backend_File');
        $data[] = array($options2, 'Zend_Cache_Core', 'Zend_Cache_Backend_File');
        $data[] = array($options3, 'Zend_Cache_Core', 'Robo47_Cache_Backend_Array');

        return $data;
    }

    /**
     * @covers Robo47_Application_Resource_Cache<extended>
     * @covers Robo47_Application_Resource_Cache::init
     * @covers Robo47_Application_Resource_Cache::_setupCache
     * @covers Robo47_Application_Resource_Cache::getCache
     * @dataProvider initProvider
     */
    public function testInit($options, $frontendType, $backendType)
    {
        $resource = new Robo47_Application_Resource_Cache($options);
        $resource->init();
        $this->assertType($frontendType, $resource->getCache(), 'Cache Frontend is wrong');
        $this->assertType($backendType, $resource->getCache()->getBackend(), 'Cache Backend is wrong');
    }

    /**
     * @covers Robo47_Application_Resource_Cache::init
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_Cache(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Cache.', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Cache::init
     * @covers Robo47_Application_Resource_Cache::_setupCache
     */
    public function testSaveToRegistryy()
    {
        $options = array(
            'frontendName' => 'Core',
            'backendName' => 'Robo47_Cache_Backend_Array',
            'customBackendNaming' => true,
            'registryKey' => 'cache_default',
        );


        $resource = new Robo47_Application_Resource_Cache($options);
        $resource->init();
        $this->assertType('Zend_Cache_Core', $resource->getCache());
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache()->getBackend());

        $this->assertTrue(Zend_Registry::isRegistered('cache_default'), 'Key cache_default was not registered in the Registry');
    }

    /**
     * @covers Robo47_Application_Resource_Cache::init
     * @covers Robo47_Application_Resource_Cache::_setupCache
     */
    public function testInitWithoutFrontendName()
    {
        $options = array(
            'backendName' => 'Robo47_Cache_Backend_Array',
        );

        $resource = new Robo47_Application_Resource_Cache($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown with options without frontendName');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Cache config doesn\'t contain frontendName', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Cache::init
     * @covers Robo47_Application_Resource_Cache::_setupCache
     */
    public function testInitWithoutBackendName()
    {
        $options = array(
            'frontendName' => 'Robo47_Cache_Backend_Array',
        );

        $resource = new Robo47_Application_Resource_Cache($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown with options without backendName');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Cache config doesn\'t contain backendName', $e->getMessage());
        }
    }
}