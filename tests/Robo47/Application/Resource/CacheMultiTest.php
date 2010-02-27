<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Application_Resource_CacheMultiTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_CacheMulti::getCache
     * @covers Robo47_Application_Resource_Exception
     */
    public function testGetNonExistingCache()
    {
        $options = array(
            'default' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
        );

        $resource = new Robo47_Application_Resource_CacheMulti($options);
        $resource->init();
        try {
            $cache = $resource->getCache('nonExistingCache');
            $this->fail('Getting non-existing Cache should throw Exception');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Cache \'nonExistingCache\' doesn\'t exist', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti<extended>
     * @covers Robo47_Application_Resource_CacheMulti::init
     * @covers Robo47_Application_Resource_CacheMulti::getCache
     */
    public function testInitSingleCacheWithMultiArray()
    {
        $options = array(
            'default' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
        );

        $resource = new Robo47_Application_Resource_CacheMulti($options);
        $resource->init();
        $this->assertType('Zend_Cache_Core', $resource->getCache('default'));
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache('default')->getBackend());
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti::init
     * @covers Robo47_Application_Resource_CacheMulti::getCache
     * @covers Robo47_Application_Resource_CacheMulti::getCaches
     */
    public function testInitMultiCacheWithMultiArray()
    {
        $options = array(
            'default' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
            'baa' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
            'foo' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
        );


        $resource = new Robo47_Application_Resource_CacheMulti($options);
        $resource->init();
        $this->assertType('Zend_Cache_Core', $resource->getCache('default'));
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache('default')->getBackend());

        $this->assertType('Zend_Cache_Core', $resource->getCache('baa'));
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache('baa')->getBackend());

        $this->assertType('Zend_Cache_Core', $resource->getCache('foo'));
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache('foo')->getBackend());

        $this->assertEquals(3, count($resource->getCaches()));

        $this->assertContains($resource->getCache('default'), $resource->getCaches());
        $this->assertContains($resource->getCache('baa'), $resource->getCaches());
        $this->assertContains($resource->getCache('foo'), $resource->getCaches());
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti::init
     */
    public function testSaveToRegistryy()
    {
        $options = array(
            'default' => array(
                'frontendName' => 'Core',
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
                'registryKey' => 'cache_default',
            ),
        );


        $resource = new Robo47_Application_Resource_CacheMulti($options);
        $resource->init();
        $this->assertType('Zend_Cache_Core', $resource->getCache('default'));
        $this->assertType('Robo47_Cache_Backend_Array', $resource->getCache('default')->getBackend());

        $this->assertTrue(Zend_Registry::isRegistered('cache_default'), 'Key cache_default was not registered in the Registry');
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti::init
     */
    public function testInitWithoutFrontendName()
    {
        $options = array(
            'default' => array(
                'backendName' => 'Robo47_Cache_Backend_Array',
                'customBackendNaming' => true,
            ),
        );

        $resource = new Robo47_Application_Resource_CacheMulti($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown with options without frontendName');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Cache config doesn\'t contain frontendName', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti::init
     */
    public function testInitWithoutBackendName()
    {
        $options = array(
            'default' => array(
                'frontendName' => 'Core',
            ),
        );

        $resource = new Robo47_Application_Resource_CacheMulti($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown with options without backendName');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Cache config doesn\'t contain backendName', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_CacheMulti::init
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_CacheMulti(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_CacheMulti.', $e->getMessage());
        }
    }
}