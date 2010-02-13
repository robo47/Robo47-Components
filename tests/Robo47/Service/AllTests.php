<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Service_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Service');

        if (TESTS_ROBO47_SERVICE_GRAVATAR) {
            $suite->addTestSuite('Robo47_Service_GravatarTest');
        }
        if (TESTS_ROBO47_SERVICE_BITLY) {
            $suite->addTestSuite('Robo47_Service_BitlyTest');
        }
        return $suite;
    }
}