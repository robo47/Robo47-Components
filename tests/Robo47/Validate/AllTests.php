<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Validate_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Validate');

        $suite->addTestSuite('Robo47_Validate_UriTest');
        $suite->addTestSuite('Robo47_Validate_StringContainsTest');
        $suite->addTestSuite('Robo47_Validate_StringNotContainsTest');
        $suite->addTestSuite('Robo47_Validate_MaxLineLengthTest');
        $suite->addTestSuite('Robo47_Validate_MockTest');
        $suite->addTestSuite('Robo47_Validate_Doctrine_AbstractTest');
        $suite->addTestSuite('Robo47_Validate_Doctrine_RecordExistsTest');
        $suite->addTestSuite('Robo47_Validate_Doctrine_NoRecordExistsTest');

        return $suite;
    }
}