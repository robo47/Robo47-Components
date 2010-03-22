<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * Needs to run in seperate processes because of possible class-name
 * conflictions with standalone htmlpurifier-loader and classes loaded
 * by other tests
 *
 * @runTestsInSeparateProcesses
 *
 * @group Robo47_Loader
 * @group Robo47_Loader_Autoloader
 * @group Robo47_Loader_Autoloader_HtmlPurifier
 */
class Robo47_Loader_Autoloader_HtmlPurifierTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
    }
    
    public function tearDown()
    {
        Zend_Loader_Autoloader::resetInstance();
    }
    
    public function testClass()
    {
        $reflection = new ReflectionClass('Robo47_Loader_Autoloader_HtmlPurifier');
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains('Zend_Loader_Autoloader_Interface', $interfaces);
    }

    /**
     * @covers Robo47_Loader_Autoloader_HtmlPurifier
     */
    public function testConstruct()
    {
        $this->assertFalse(class_exists('HTMLPurifier_Bootstrap', false), 'class HTMLPurifier_Bootstrap already is loaded');
        $loader = new Robo47_Loader_Autoloader_HtmlPurifier();
        $this->assertTrue(class_exists('HTMLPurifier_Bootstrap', false), 'class HTMLPurifier_Bootstrap was not loaded');
    }

    /**
     * @covers Robo47_Loader_Autoloader_HtmlPurifier::autoload
     */
    public function testAutoload()
    {
        $this->assertFalse(class_exists('HTMLPurifier', false), 'class HTMLPurifier already is loaded');
        $autoloader = new Robo47_Loader_Autoloader_HtmlPurifier();
        $autoloader->autoload('HTMLPurifier');
        $this->assertTrue(class_exists('HTMLPurifier', false), 'class HTMLPurifier was not loaded');
    }
}