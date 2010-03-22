<?php

class Robo47_Cache_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Cache');

        $suite->addTestSuite('Robo47_Cache_DoctrineAdapterTest');
        $suite->addTestSuite('Robo47_Cache_Backend_ArrayTest');

        return $suite;
    }
}