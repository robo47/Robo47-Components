<?php

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components');

        $suite->addTest(Robo47_AllTests::suite());

        return $suite;
    }
}