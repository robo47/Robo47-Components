<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Application
 * @group Robo47_Application_Resource
 * @group Robo47_Application_Resource_AutoloaderMulti
 */
class Robo47_Application_Resource_AutoloaderMultiTest extends PHPUnit_Framework_TestCase
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
     * @covers Robo47_Application_Resource_AutoloaderMulti<extended>
     * @covers Robo47_Application_Resource_AutoloaderMulti::init
     * @covers Robo47_Application_Resource_AutoloaderMulti::getAutoloader
     * @covers Robo47_Application_Resource_AutoloaderMulti::getAutoloaders
     */
    public function testInitMulti()
    {
        $options = array(
            'htmlpurifier' => array(
                'classname' => 'Robo47_Loader_Autoloader_HtmlPurifier',
                'prefix' => 'htmlpurifier',
            ),
            'ezc' => array(
                'classname' => 'Robo47_Loader_Autoloader_Ezc',
                'prefix' => 'ezc',
            )
        );

        $resource = new Robo47_Application_Resource_AutoloaderMulti($options);
        $resource->init();

        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $this->assertEquals(1, count($autoLoader->getNamespaceAutoloaders('ezc')), 'No ezc autoloader found');
        $this->assertEquals(1, count($autoLoader->getNamespaceAutoloaders('htmlpurifier')), 'No htmlpurifier autoloader found');

        $ezc = $autoLoader->getNamespaceAutoloaders('ezc');
        $this->assertInstanceOf('Robo47_Loader_Autoloader_Ezc', $ezc[0]);

        $htmlpurifier = $autoLoader->getNamespaceAutoloaders('htmlpurifier');
        $this->assertInstanceOf('Robo47_Loader_Autoloader_HtmlPurifier', $htmlpurifier[0]);

        $this->assertInstanceOf('Robo47_Loader_Autoloader_HtmlPurifier', $resource->getAutoloader('htmlpurifier'));
        $this->assertInstanceOf('Robo47_Loader_Autoloader_Ezc', $resource->getAutoloader('ezc'));

        $this->assertEquals(2, count($resource->getAutoloaders()));

        $this->assertContains($resource->getAutoloader('ezc'), $resource->getAutoloaders());
        $this->assertContains($resource->getAutoloader('htmlpurifier'), $resource->getAutoloaders());
    }

    /**
     * @covers Robo47_Application_Resource_AutoloaderMulti::getAutoloader
     * @covers Robo47_Application_Resource_Exception
     */
    public function testGetNonExistingCache()
    {
        $options = array(
            'ezc' => array(
                'classname' => 'Robo47_Loader_Autoloader_Ezc',
                'prefix' => 'ezc',
            )
        );

        $resource = new Robo47_Application_Resource_AutoloaderMulti($options);
        $resource->init();
        try {
            $autoloader = $resource->getAutoloader('nonExistingCache');
            $this->fail('Getting non-existing Cache should throw Exception');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Autoloader \'nonExistingCache\' doesn\'t exist', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_AutoloaderMulti::init
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_AutoloaderMulti(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_AutoloaderMulti.', $e->getMessage());
        }
    }
}