<?php
require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Curl_AllTests extends PHPUnit_Framework_TestCase
{

    /**
     * Creates and returns this test suite
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Curl');

        $suite->addTestSuite('Robo47_Curl_MultiTest');

        return $suite;
    }
}