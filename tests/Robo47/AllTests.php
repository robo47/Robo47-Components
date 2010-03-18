<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'TestHelper.php';

class Robo47_AllTests
{

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47');

        // important auto-loader-tests need to be run before the other ones
        if (TESTS_ROBO47_LOADER) {
            $suite->addTest(Robo47_Loader_AllTests::suite());
        }
        if (TESTS_ROBO47_APPLICATION) {
            $suite->addTest(Robo47_Application_AllTests::suite());
        }
        if (TESTS_ROBO47_AUTH) {
            $suite->addTest(Robo47_Auth_AllTests::suite());
        }
        if (TESTS_ROBO47_CACHE) {
            $suite->addTest(Robo47_Cache_AllTests::suite());
        }
        if (TESTS_ROBO47_CORE) {
            $suite->addTestSuite('Robo47_CoreTest');
        }
        if (TESTS_ROBO47_CONTROLLER) {
            $suite->addTest(Robo47_Controller_AllTests::suite());
        }
        if (TESTS_ROBO47_CONVERT) {
            $suite->addTestSuite('Robo47_ConvertTest');
        }
        if (TESTS_ROBO47_CURL) {
            $suite->addTestSuite('Robo47_CurlTest');
            $suite->addTest(Robo47_Curl_AllTests::suite());
        }
        if (TESTS_ROBO47_EXCEPTION) {
            $suite->addTestSuite('Robo47_ExceptionTest');
        }
        if (TESTS_ROBO47_EXIFTOOL) {
            $suite->addTestSuite('Robo47_ExiftoolTest');
        }
        if (TESTS_ROBO47_ERROREXCEPTION) {
            $suite->addTestSuite('Robo47_ErrorExceptionTest');
        }
        if (TESTS_ROBO47_ERRORHANDLER) {
            $suite->addTestSuite('Robo47_ErrorHandlerTest');
        }
        if (TESTS_ROBO47_FILTER) {
            $suite->addTest(Robo47_Filter_AllTests::suite());
        }
        if (TESTS_ROBO47_FORM) {
            $suite->addTest(Robo47_Form_AllTests::suite());
        }
        if (TESTS_ROBO47_LOG) {
            $suite->addTestSuite('Robo47_LogTest');
            $suite->addTest(Robo47_Log_AllTests::suite());
        }
        if (TESTS_ROBO47_MAIL) {
            $suite->addTest(Robo47_Mail_AllTests::suite());
        }
        if (TESTS_ROBO47_MOCK) {
            $suite->addTestSuite('Robo47_MockTest');
        }
        if (TESTS_ROBO47_PAGINATOR) {
            $suite->addTest(Robo47_Paginator_AllTests::suite());
        }
        if (TESTS_ROBO47_SERVICE) {
            $suite->addTest(Robo47_Service_AllTests::suite());
        }
        if (TESTS_ROBO47_VALIDATE) {
            $suite->addTest(Robo47_Validate_AllTests::suite());
        }
        if (TESTS_ROBO47_VIEW) {
            $suite->addTest(Robo47_View_AllTests::suite());
        }
        return $suite;
    }
}