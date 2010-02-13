<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Mail_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Mail');

        if (TESTS_ROBO47_MAIL_TRANSPORT_MOCKSIMPLE) {
            $suite->addTestSuite('Robo47_Mail_Transport_MockSimpleTest');
        }
        if (TESTS_ROBO47_MAIL_TRANSPORT_MULTI) {
            $suite->addTestSuite('Robo47_Mail_Transport_MultiTest');
        }
        if (TESTS_ROBO47_MAIL_TRANSPORT_LOG) {
            $suite->addTestSuite('Robo47_Mail_Transport_LogTest');
        }
        if (TESTS_ROBO47_MAIL_TRANSPORT_LOG_FORMATTER_SERIALIZE) {
            $suite->addTestSuite('Robo47_Mail_Transport_Log_Formatter_SerializeTest');
        }
        if (TESTS_ROBO47_MAIL_TRANSPORT_LOG_FORMATTER_SIMPLE) {
            $suite->addTestSuite('Robo47_Mail_Transport_Log_Formatter_SimpleTest');
        }

        return $suite;
    }
}