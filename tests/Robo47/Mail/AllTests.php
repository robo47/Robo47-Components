<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Mail_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Mail');

        $suite->addTestSuite('Robo47_Mail_Transport_MockSimpleTest');
        $suite->addTestSuite('Robo47_Mail_Transport_MultiTest');
        $suite->addTestSuite('Robo47_Mail_Transport_LogTest');
        $suite->addTestSuite('Robo47_Mail_Transport_Log_Formatter_SerializeTest');
        $suite->addTestSuite('Robo47_Mail_Transport_Log_Formatter_SimpleTest');

        return $suite;
    }
}