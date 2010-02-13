<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_View_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_View');

        if (TESTS_ROBO47_VIEW_HELPER_ANCHOR) {
            $suite->addTestSuite('Robo47_View_Helper_AnchorTest');
        }
        if (TESTS_ROBO47_VIEW_HELPER_CDN) {
            $suite->addTestSuite('Robo47_View_Helper_CdnTest');
        }
        if (TESTS_ROBO47_VIEW_HELPER_GLOBALS) {
            $suite->addTestSuite('Robo47_View_Helper_GlobalsTest');
        }
        if (TESTS_ROBO47_VIEW_HELPER_CKEDITOR) {
            $suite->addTestSuite('Robo47_View_Helper_CkeditorTest');
        }
        if (TESTS_ROBO47_VIEW_HELPER_URL) {
            $suite->addTestSuite('Robo47_View_Helper_UrlTest');
        }
        if (TESTS_ROBO47_VIEW_HELPER_GRAVATAR) {
            $suite->addTestSuite('Robo47_View_Helper_GravatarTest');
        }

        return $suite;
    }
}