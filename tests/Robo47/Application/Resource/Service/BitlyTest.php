<?php

require_once dirname(__FILE__) . '/../../../../TestHelper.php';

class Robo47_Application_Resource_Service_BitlyTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_Service_Bitly<extended>
     * @covers Robo47_Application_Resource_Service_Bitly::init
     * @covers Robo47_Application_Resource_Service_Bitly::_setupService
     * @covers Robo47_Application_Resource_Service_Bitly::getService
     */
    public function testInit()
    {
        $options = array(
            'apiKey'        => 'someApiKey',
            'login'         => 'someLogin',
            'format'        => 'json',
            'version'       => '2.0.1',
            'callback'      => 'aCallback',
            'registryKey'   => 'Robo47_Service_Bitly',
        );

        $resource = new Robo47_Application_Resource_Service_Bitly($options);
        $resource->init();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']));

        $bitly = Zend_Registry::get($options['registryKey']);
        /* @var $bitly Robo47_Service_Bitly */

        $this->assertEquals($options['apiKey'], $bitly->getApiKey());
        $this->assertEquals($options['login'], $bitly->getLogin());
        $this->assertEquals($options['callback'], $bitly->getCallback());
        $this->assertEquals($options['format'], $bitly->getFormat());
        $this->assertEquals($options['version'], $bitly->getVersion());

        $this->assertSame($bitly, $resource->getService());
    }

    /**
     * @covers Robo47_Application_Resource_Service_Bitly::init
     * @covers Robo47_Application_Resource_Service_Bitly::_setupService
     */
    public function testInitWithoutLogin()
    {
        $options = array(
            'apiKey'        => 'someApiKey',
        );

        $resource = new Robo47_Application_Resource_Service_Bitly($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown');
        } catch(Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('No login provided', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Application_Resource_Service_Bitly::init
     * @covers Robo47_Application_Resource_Service_Bitly::_setupService
     */
    public function testInitWithoutApiKey()
    {
        $options = array(
            'login'        => 'someLogin',
        );

        $resource = new Robo47_Application_Resource_Service_Bitly($options);
        try {
            $resource->init();
            $this->fail('No Exception thrown');
        } catch(Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('No apiKey provided', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Application_Resource_Service_Bitly::init
     */
    public function testInitWithoutOptions()
    {
        $resource = new Robo47_Application_Resource_Service_Bitly(array());
        try {
            $resource->init();
            $this->fail('No Exception thrown');
        } catch(Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Service_Bitly.', $e->getMessage(), 'Wrong Exception message');
        }
    }
}