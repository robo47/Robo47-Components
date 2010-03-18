<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @group Robo47_Application
 * @group Robo47_Application_Resource
 * @group Robo47_Application_Resource_Config
 */
class Robo47_Application_Resource_ConfigTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_Config<extended>
     * @covers Robo47_Application_Resource_Config::init
     * @covers Robo47_Application_Resource_Config::_setupConfig
     * @covers Robo47_Application_Resource_Config::getConfig
     */
    public function testInit()
    {
        $options = array(
            'config'        => array('foo' => 'bar'),
            'registryKey'   => 'Zend_Config',
        );

        $resource = new Robo47_Application_Resource_Config($options);
        $resource->init();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']));

        $config = Zend_Registry::get($options['registryKey']);
        /* @var $config Zend_Config */


        $this->assertType('Zend_Config', $config);
        $this->assertEquals($options['config'], $config->toArray());
    }

    /**
     * @covers Robo47_Application_Resource_Config::init
     * @covers Robo47_Application_Resource_Config::_setupConfig
     * @covers Robo47_Application_Resource_Config::getConfig
     */
    public function testInitWithoutRegistryKey()
    {
        $options = array(
            'config'        => array('foo' => 'bar'),
        );

        $resource = new Robo47_Application_Resource_Config($options);
        $resource->init();

        $config = $resource->getConfig();
        /* @var $config Zend_Config */

        $this->assertType('Zend_Config', $config);
        $this->assertEquals($options['config'], $config->toArray());
    }

    /**
     * @covers Robo47_Application_Resource_Config::init
     * @covers Robo47_Application_Resource_Config::_setupConfig
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutConfigdata()
    {
        $resource = new Robo47_Application_Resource_Config(array('registryKey' => 'Foo'));

        try {
            $resource->init();
            $this->fail('no exception thrown on init without config');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('No data for Zend_Config found', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Config::init
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutConfig()
    {
        $resource = new Robo47_Application_Resource_Config(array());

        try {
            $resource->init();
            $this->fail('no exception thrown on init without config');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Config.', $e->getMessage());
        }
    }
}