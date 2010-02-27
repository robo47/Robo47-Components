<?php

require_once dirname(__FILE__) . '/../../../../TestHelper.php';

class Robo47_Application_Resource_Service_GravatarTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_Service_Gravatar<extended>
     * @covers Robo47_Application_Resource_Service_Gravatar::init
     * @covers Robo47_Application_Resource_Service_Gravatar::_setupService
     * @covers Robo47_Application_Resource_Service_Gravatar::getService
     */
    public function testInit()
    {
        $options = array(
            'rating'        => Robo47_Service_Gravatar::RATING_X,
            'size'          => 123,
            'default'       => 'http://www.example.com',
            'cache'         => 'My_Cache',
            'useSSL'        => true,
            'cachePrefix'   => 'foobaa',
            'registryKey'   => 'Zend_Service_Akismet',
        );
        $cache = Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array(), array(), array());
        Zend_Registry::set('My_Cache', $cache);

        $resource = new Robo47_Application_Resource_Service_Gravatar($options);
        $resource->init();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']), 'Service was not saved to registry');

        $gravatar = Zend_Registry::get($options['registryKey']);
        /* @var $gravatar Robo47_Service_Gravatar */

        $this->assertEquals($options['rating'], $gravatar->getRating(), 'Wrong Rating');
        $this->assertEquals($options['size'], $gravatar->getSize(), 'Wrong size');
        $this->assertEquals($options['default'], $gravatar->getDefault(), 'Wrong default');
        $this->assertEquals($options['cachePrefix'], $gravatar->getCachePrefix(), 'Wrong Cache Prefix');
        $this->assertEquals($options['useSSL'], $gravatar->usesSSL(), 'Wrong usesSSL');
        $this->assertSame($cache, $gravatar->getCache(), 'Wrong Cache');

        $this->assertSame($gravatar, $resource->getService(), 'getService() returns other object');
    }

    /**
     * @covers Robo47_Application_Resource_Service_Gravatar::init
     */
    public function testInitWithoutOptions()
    {
        $resource = new Robo47_Application_Resource_Service_Gravatar(array());
        try {
            $resource->init();
            $this->fail('No Exception thrown');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Service_Gravatar.', $e->getMessage(), 'Wrong Exception message');
        }
    }
}