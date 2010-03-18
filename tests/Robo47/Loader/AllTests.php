<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Loader_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_View');

        $suite->addTestSuite('Robo47_Loader_Autoloader_EzcTest');
        $suite->addTestSuite('Robo47_Loader_Autoloader_HtmlPurifierTest');
        $suite->addTestSuite('Robo47_Loader_Autoloader_HtmlPurifierStandaloneTest');

        return $suite;
    }
}