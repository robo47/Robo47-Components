<?php

class Robo47_Paginator_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Paginator');

        $suite->addTestSuite('Robo47_Paginator_Adapter_DoctrineQueryTest');
        $suite->addTestSuite('Robo47_Paginator_Adapter_DoctrineTableTest');

        return $suite;
    }
}