<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Auth_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Auth');

        $suite->addTestSuite('Robo47_Auth_Adapter_ArrayTest');

        return $suite;
    }
}