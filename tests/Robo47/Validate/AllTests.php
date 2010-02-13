<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Validate_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Validate');

        if (TESTS_ROBO47_VALIDATE_URI) {
            $suite->addTestSuite('Robo47_Validate_UriTest');
        }
        if (TESTS_ROBO47_VALIDATE_STRINGCONTAINS) {
            $suite->addTestSuite('Robo47_Validate_StringContainsTest');
        }
        if (TESTS_ROBO47_VALIDATE_STRINGNOTCONTAINS) {
            $suite->addTestSuite('Robo47_Validate_StringNotContainsTest');
        }
        if (TESTS_ROBO47_VALIDATE_MAXLINELENGTH) {
            $suite->addTestSuite('Robo47_Validate_MaxLineLengthTest');
        }
        if (TESTS_ROBO47_VALIDATE_MOCK) {
            $suite->addTestSuite('Robo47_Validate_MockTest');
        }
        if (TESTS_ROBO47_VALIDATE_DOCTRINE_ABSTRACT) {
            $suite->addTestSuite('Robo47_Validate_Doctrine_AbstractTest');
        }
        if (TESTS_ROBO47_VALIDATE_DOCTRINE_RECORDEXISTS) {
            $suite->addTestSuite('Robo47_Validate_Doctrine_RecordExistsTest');
        }
        if (TESTS_ROBO47_VALIDATE_DOCTRINE_NORECORDEXISTS) {
            $suite->addTestSuite('Robo47_Validate_Doctrine_NoRecordExistsTest');
        }
        return $suite;
    }
}