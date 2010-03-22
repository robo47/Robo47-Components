<?php

class Robo47_Curl_AllTests extends PHPUnit_Framework_TestCase
{

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Curl');

        $suite->addTestSuite('Robo47_Curl_MultiTest');

        return $suite;
    }
}