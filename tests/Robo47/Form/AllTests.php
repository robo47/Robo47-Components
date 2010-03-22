<?php

class Robo47_Form_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Form');

        $suite->addTestSuite('Robo47_Form_Decorator_InfoTest');
        $suite->addTestSuite('Robo47_Form_Element_CkeditorTest');

        return $suite;
    }
}