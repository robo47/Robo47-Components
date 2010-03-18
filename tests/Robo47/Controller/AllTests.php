<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Controller_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Controller');

        $suite->addTestSuite('Robo47_Controller_Plugin_TitleTest');
        $suite->addTestSuite('Robo47_Controller_Plugin_TidyTest');
        $suite->addTestSuite('Robo47_Controller_Action_Helper_UrlTest');

        return $suite;
    }
}