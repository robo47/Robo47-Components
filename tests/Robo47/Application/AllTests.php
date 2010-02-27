<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Application_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Application');

        if (TESTS_ROBO47_APPLICATION_RESOURCE_AUTOLOADER) {
            $suite->addTestSuite('Robo47_Application_Resource_AutoloaderTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_AUTOLOADERMULTI) {
            $suite->addTestSuite('Robo47_Application_Resource_AutoloaderMultiTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_CACHE) {
            $suite->addTestSuite('Robo47_Application_Resource_CacheTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_CACHEMULTI) {
            $suite->addTestSuite('Robo47_Application_Resource_CacheMultiTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_ERRORHANDLER) {
            $suite->addTestSuite('Robo47_Application_Resource_ErrorHandlerTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_PLUGIN_ERRORHANDLER) {
            $suite->addTestSuite('Robo47_Application_Resource_Plugin_ErrorHandlerTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_CONFIG) {
            $suite->addTestSuite('Robo47_Application_Resource_ConfigTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_HTMLPURIFIER) {
            $suite->addTestSuite('Robo47_Application_Resource_HtmlPurifierTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_SERVICE_AKISMET) {
            $suite->addTestSuite('Robo47_Application_Resource_Service_AkismetTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_SERVICE_GRAVATAR) {
            $suite->addTestSuite('Robo47_Application_Resource_Service_GravatarTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_SERVICE_BITLY) {
            $suite->addTestSuite('Robo47_Application_Resource_Service_BitlyTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_LOG) {
            $suite->addTestSuite('Robo47_Application_Resource_LogTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_OBJECT) {
            $suite->addTestSuite('Robo47_Application_Resource_ObjectTest');
        }
        if (TESTS_ROBO47_APPLICATION_RESOURCE_OBJECTMULTI) {
            $suite->addTestSuite('Robo47_Application_Resource_ObjectMultiTest');
        }
        return $suite;
    }
}