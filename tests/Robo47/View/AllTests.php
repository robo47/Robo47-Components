<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_View_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_View');

        $suite->addTestSuite('Robo47_View_Helper_AnchorTest');
        $suite->addTestSuite('Robo47_View_Helper_CdnTest');
        $suite->addTestSuite('Robo47_View_Helper_GlobalsTest');
        $suite->addTestSuite('Robo47_View_Helper_CkeditorTest');
        $suite->addTestSuite('Robo47_View_Helper_UrlTest');
        $suite->addTestSuite('Robo47_View_Helper_GravatarTest');

        return $suite;
    }
}