<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Loader_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_View');

        if (TESTS_ROBO47_LOADER_AUTOLOADER_EZC) {
            $suite->addTestSuite('Robo47_Loader_Autoloader_EzcTest');
        }
        if (TESTS_ROBO47_LOADER_AUTOLOADER_HTMLPURIFIER) {
            $suite->addTestSuite('Robo47_Loader_Autoloader_HTMLPurifierTest');
        }

        return $suite;
    }
}