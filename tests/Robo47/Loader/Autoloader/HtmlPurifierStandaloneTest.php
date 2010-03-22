<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * Needs to run in seperate processes because of possible class-name
 * conflictions with normale htmlpurifier-loader
 *
 * @runTestsInSeparateProcesses
 *
 * @group Robo47_Loader
 * @group Robo47_Loader_Autoloader
 * @group Robo47_Loader_Autoloader_HtmlPurifierStandalone
 */
class Robo47_Loader_Autoloader_HtmlPurifierStandaloneTest extends PHPUnit_Framework_TestCase
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
        $reflection = new ReflectionClass('Robo47_Loader_Autoloader_HtmlPurifierStandalone');
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains('Zend_Loader_Autoloader_Interface', $interfaces);
    }

    /**
     * @covers Robo47_Loader_Autoloader_HtmlPurifierStandalone
     */
    public function testConstruct()
    {
        $this->assertFalse(class_exists('HTMLPurifier_Bootstrap', false), 'class HTMLPurifier_Bootstrap already is loaded');
        $loader = new Robo47_Loader_Autoloader_HtmlPurifierStandalone();
        $this->assertTrue(class_exists('HTMLPurifier_Bootstrap', false), 'class HTMLPurifier_Bootstrap was not loaded');
    }

    /**
     * Load a class NOT existing in the Standalone-php
     * @covers Robo47_Loader_Autoloader_HtmlPurifierStandalone::autoload
     */
    public function testAutoload()
    {
        $this->assertFalse(class_exists('HTMLPurifier_Printer', false), 'class HTMLPurifier already is loaded');
        $autoloader = new Robo47_Loader_Autoloader_HtmlPurifierStandalone();
        $autoloader->autoload('HTMLPurifier_Printer');
        $this->assertTrue(class_exists('HTMLPurifier_Printer', false), 'class HTMLPurifier was not loaded');
    }
}