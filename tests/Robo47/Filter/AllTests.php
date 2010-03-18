<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Filter_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Filter');

        $suite->addTestSuite('Robo47_Filter_HtmlPurifierTest');
        $suite->addTestSuite('Robo47_Filter_SanitizeUrlTest');
        $suite->addTestSuite('Robo47_Filter_UrlSchemeTest');
        $suite->addTestSuite('Robo47_Filter_TidyTest');

        return $suite;
    }
}