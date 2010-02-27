<?php

require_once dirname(__FILE__) . '/../../../../TestHelper.php';

class Robo47_Application_Resource_Service_AkismetTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_Service_Akismet<extended>
     * @covers Robo47_Application_Resource_Service_Akismet::init
     * @covers Robo47_Application_Resource_Service_Akismet::_setupService
     * @covers Robo47_Application_Resource_Service_Akismet::getService
     */
    public function testInit()
    {
        $options = array(
            'apiKey'        => '12345',
            'blog'          => 'http://www.example.com',
            'charset'       => 'utf-8',
            'port'          => 1234,
            'userAgent'     => 'Robo47 Components/' . Robo47_Core::VERSION . ' | Akismet/1.11',
            'registryKey'   => 'Zend_Service_Akismet',
        );

        $resource = new Robo47_Application_Resource_Service_Akismet($options);
        $resource->init();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']));

        $akismet = Zend_Registry::get($options['registryKey']);
        /* @var $akismet Zend_Service_Akismet */

        $this->assertEquals($options['apiKey'], $akismet->getApiKey());
        $this->assertEquals($options['blog'], $akismet->getBlogUrl());
        $this->assertEquals($options['port'], $akismet->getPort());
        $this->assertEquals($options['userAgent'], $akismet->getUserAgent());
        $this->assertEquals($options['charset'], $akismet->getCharset());

        $this->assertSame($akismet, $resource->getService());
    }

    /**
     * @covers Robo47_Application_Resource_Service_Akismet::init
     * @covers Robo47_Application_Resource_Service_Akismet::_setupService
     * @covers Robo47_Application_Resource_Service_Akismet::getService
     */
    public function testInitWithoutRegistry()
    {
        $options = array(
            'apiKey'        => '12345',
            'blog'          => 'http://www.example.com',
            'charset'       => 'utf-8',
            'port'          => 1234,
            'userAgent'     => 'Robo47 Components/' . Robo47_Core::VERSION . ' | Akismet/1.11',
        );

        $resource = new Robo47_Application_Resource_Service_Akismet($options);
        $resource->init();

        $akismet = $resource->getService();
        /* @var $akismet Zend_Service_Akismet */

        $this->assertEquals($options['apiKey'], $akismet->getApiKey());
        $this->assertEquals($options['blog'], $akismet->getBlogUrl());
        $this->assertEquals($options['port'], $akismet->getPort());
        $this->assertEquals($options['userAgent'], $akismet->getUserAgent());
        $this->assertEquals($options['charset'], $akismet->getCharset());
    }

    /**
     * @covers Robo47_Application_Resource_Service_Akismet::init
     * @covers Robo47_Application_Resource_Service_Akismet::_setupService
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutBlog()
    {
        $options = array(
            'apiKey'        => '12345',
        );

        $resource = new Robo47_Application_Resource_Service_Akismet($options);

        try {
            $resource->init();
            $this->fail('no exception thrown on init without blog');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('option "blog" not found for Service_Akismet', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Service_Akismet::init
     * @covers Robo47_Application_Resource_Service_Akismet::_setupService
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutApiKey()
    {
        $options = array(
            'blog'        => 'http://example.com',
        );

        $resource = new Robo47_Application_Resource_Service_Akismet($options);

        try {
            $resource->init();
            $this->fail('no exception thrown on init without apiKey');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('option "apiKey" not found for Service_Akismet', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Service_Akismet::init
     */
    public function testInitWithoutOptions()
    {
        $resource = new Robo47_Application_Resource_Service_Akismet(array());
        try {
            $resource->init();
            $this->fail('No Exception thrown');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Service_Akismet.', $e->getMessage(), 'Wrong Exception message');
        }
    }
}