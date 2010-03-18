<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * Needs to run in seperate processes because of classes loaded
 * by other tests
 *
 * @runTestsInSeparateProcesses
 */
class Robo47_Loader_Autoloader_EzcTest extends PHPUnit_Framework_TestCase
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
        $reflection = new ReflectionClass('Robo47_Loader_Autoloader_Ezc');
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains('Zend_Loader_Autoloader_Interface', $interfaces);
    }

    /**
     * @covers Robo47_Loader_Autoloader_Ezc<extended>
     * @covers Robo47_Loader_Autoloader_Ezc::__construct
     */
    public function testConstruct()
    {
        $this->assertFalse(class_exists('ezcBase', false), 'class ezcBase already is loaded');
        $loader = new Robo47_Loader_Autoloader_Ezc();
        $this->assertTrue(class_exists('ezcBase', false), 'class ezcBase was not loaded');
    }

    /**
     * @covers Robo47_Loader_Autoloader_Ezc::autoload
     */
    public function testAutoload()
    {
        $this->assertFalse(class_exists('ezcGraph', false), 'class ezcGraph already is loaded');
        $autoloader = new Robo47_Loader_Autoloader_Ezc();
        $autoloader->autoload('ezcGraph');
        $this->assertTrue(class_exists('ezcGraph', false), 'class ezcGraph was not loaded');
    }
}