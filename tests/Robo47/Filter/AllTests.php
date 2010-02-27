<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Filter_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Filter');
        if (TESTS_ROBO47_FILTER_HTMLPURIFIER) {
            $suite->addTestSuite('Robo47_Filter_HtmlPurifierTest');
        }
        if (TESTS_ROBO47_FILTER_SANITIZEURL) {
            $suite->addTestSuite('Robo47_Filter_SanitizeUrlTest');
        }
        if (TESTS_ROBO47_FILTER_URLSCHEME) {
            $suite->addTestSuite('Robo47_Filter_UrlSchemeTest');
        }
        if (TESTS_ROBO47_FILTER_TIDY) {
            $suite->addTestSuite('Robo47_Filter_TidyTest');
        }

        return $suite;
    }
}