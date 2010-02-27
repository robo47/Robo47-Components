<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Application_Resource_AutoloaderTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {

        Zend_Loader_Autoloader::resetInstance();
        Zend_Loader_Autoloader::getInstance()
            ->setFallbackAutoloader(true);
    }
    
    public function tearDown()
    {
        Zend_Loader_Autoloader::resetInstance();
        Zend_Loader_Autoloader::getInstance()
            ->setFallbackAutoloader(true);
    }

    /**
     * @covers Robo47_Application_Resource_Autoloader<extended>
     * @covers Robo47_Application_Resource_Autoloader::init
     * @covers Robo47_Application_Resource_Autoloader::_setupAutoloader
     * @covers Robo47_Application_Resource_Autoloader::getAutoloader
     */
    public function testInitSingle()
    {
        $options = array(
            'classname' => 'Robo47_Loader_Autoloader_Ezc',
            'prefix'    => 'ezc',
        );

        $resource = new Robo47_Application_Resource_Autoloader($options);
        $resource->init();

        $this->assertType('Robo47_Loader_Autoloader_Ezc', $resource->getAutoloader());

        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $this->assertEquals(1, count($autoLoader->getNamespaceAutoloaders('ezc')));

        $ezc = $autoLoader->getNamespaceAutoloaders('ezc');
        $this->assertTrue($ezc[0] instanceof Robo47_Loader_Autoloader_Ezc);
    }

    /**
     * @covers Robo47_Application_Resource_Autoloader::init
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_Autoloader(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Autoloader.', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Autoloader::_setupAutoloader
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutClassname()
    {
        $options = array(
            'prefix'    => 'htmlpurifier',
        );

        $resource = new Robo47_Application_Resource_Autoloader($options);

        try {
            $resource->init();
            $this->fail('No exception thrown on missing classname');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Autoloader config doesn\'t contain classname', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Autoloader::_setupAutoloader
     */
    public function testInitWithoutPrefix()
    {
        $options = array(
            'classname' => 'Robo47_Loader_Autoloader_HTMLPurifier',
        );

        $resource = new Robo47_Application_Resource_Autoloader($options);

        try {
            $resource->init();
            $this->fail('No exception thrown on missing prefix');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Autoloader config doesn\'t contain prefix', $e->getMessage());
        }
    }
}