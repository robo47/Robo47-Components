<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Log_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Log');

        $suite->addTestSuite('Robo47_Log_Writer_AbstractTest');
        $suite->addTestSuite('Robo47_Log_Writer_MockTest');
        $suite->addTestSuite('Robo47_Log_Writer_DoctrineTableTest');
        $suite->addTestSuite('Robo47_Log_Filter_MockTest');
        $suite->addTestSuite('Robo47_Log_Filter_ValidateProxyTest');
        $suite->addTestSuite('Robo47_Log_Filter_CategoryTest');

        return $suite;
    }
}