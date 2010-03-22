<?php

class Robo47_Doctrine_AllTests extends PHPUnit_Framework_TestCase
{

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Doctrine');

        $suite->addTestSuite('Robo47_Doctrine_Hydrator_PopoDriverTest');

        return $suite;
    }
}