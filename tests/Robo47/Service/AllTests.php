<?php

class Robo47_Service_AllTests
{

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Service');

        $suite->addTestSuite('Robo47_Service_GravatarTest');
        $suite->addTestSuite('Robo47_Service_BitlyTest');

        return $suite;
    }
}