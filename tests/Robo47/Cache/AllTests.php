<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Cache_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Cache');

        if (TESTS_ROBO47_CACHE_DOCTRINEADAPTER) {
            $suite->addTestSuite('Robo47_Cache_DoctrineAdapterTest');
        }
        if (TESTS_ROBO47_CACHE_BACKEND_ARRAY) {
            $suite->addTestSuite('Robo47_Cache_Backend_ArrayTest');
        }
        return $suite;
    }
}