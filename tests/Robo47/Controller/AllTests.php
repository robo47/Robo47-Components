<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Controller_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Controller');

        if (TESTS_ROBO47_CONTROLLER_PLUGIN_TITLE) {
            $suite->addTestSuite('Robo47_Controller_Plugin_TitleTest');
        }
        if (TESTS_ROBO47_CONTROLLER_PLUGIN_TIDY) {
            $suite->addTestSuite('Robo47_Controller_Plugin_TidyTest');
        }
        if (TESTS_ROBO47_CONTROLLER_ACTION_HELPER_URL) {
            $suite->addTestSuite('Robo47_Controller_Action_Helper_UrlTest');
        }
        return $suite;
    }
}