<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Paginator_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Paginator');

        if (TESTS_ROBO47_PAGINATOR_ADAPTER_DOCTRINEQUERY) {
            $suite->addTestSuite('Robo47_Paginator_Adapter_DoctrineQueryTest');
        }
        if (TESTS_ROBO47_PAGINATOR_ADAPTER_DOCTRINETABLE) {
            $suite->addTestSuite('Robo47_Paginator_Adapter_DoctrineTableTest');
        }

        return $suite;
    }
}