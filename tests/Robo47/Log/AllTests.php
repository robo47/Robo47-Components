<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Log_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Log');

        if (TESTS_ROBO47_LOG_WRITER_ABSTRACT) {
            $suite->addTestSuite('Robo47_Log_Writer_AbstractTest');
        }
        if (TESTS_ROBO47_LOG_WRITER_MOCK) {
            $suite->addTestSuite('Robo47_Log_Writer_MockTest');
        }
        if (TESTS_ROBO47_LOG_WRITER_DOCTRINETABLE) {
            $suite->addTestSuite('Robo47_Log_Writer_DoctrineTableTest');
        }
        if (TESTS_ROBO47_LOG_FILTER_MOCK) {
            $suite->addTestSuite('Robo47_Log_Filter_MockTest');
        }
        if (TESTS_ROBO47_LOG_FILTER_VALIDATEPROXY) {
            $suite->addTestSuite('Robo47_Log_Filter_ValidateProxyTest');
        }
        if (TESTS_ROBO47_LOG_FILTER_CATEGORY) {
            $suite->addTestSuite('Robo47_Log_Filter_CategoryTest');
        }
        return $suite;
    }
}