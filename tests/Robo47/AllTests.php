<?php

require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . 'TestHelper.php';

class Robo47_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47');

        // important auto-loader-tests need to be run before the other ones
        $suite->addTest(Robo47_Loader_AllTests::suite());
        $suite->addTest(Robo47_Application_AllTests::suite());
        $suite->addTest(Robo47_Auth_AllTests::suite());
        $suite->addTest(Robo47_Cache_AllTests::suite());
        $suite->addTestSuite('Robo47_CoreTest');
        $suite->addTest(Robo47_Controller_AllTests::suite());
        $suite->addTestSuite('Robo47_ConvertTest');
        $suite->addTestSuite('Robo47_CurlTest');
        $suite->addTest(Robo47_Curl_AllTests::suite());
        $suite->addTestSuite('Robo47_ExceptionTest');
        $suite->addTestSuite('Robo47_ExiftoolTest');
        $suite->addTestSuite('Robo47_ErrorExceptionTest');
        $suite->addTestSuite('Robo47_ErrorHandlerTest');
        $suite->addTest(Robo47_Filter_AllTests::suite());
        $suite->addTest(Robo47_Form_AllTests::suite());
        $suite->addTestSuite('Robo47_LogTest');
        $suite->addTest(Robo47_Log_AllTests::suite());
        $suite->addTest(Robo47_Mail_AllTests::suite());
        $suite->addTestSuite('Robo47_MockTest');
        $suite->addTest(Robo47_Paginator_AllTests::suite());
        $suite->addTest(Robo47_Service_AllTests::suite());
        $suite->addTest(Robo47_Validate_AllTests::suite());
        $suite->addTest(Robo47_View_AllTests::suite());

        return $suite;
    }
}