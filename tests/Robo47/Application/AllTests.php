<?php

class Robo47_Application_AllTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Application');

        $suite->addTestSuite('Robo47_Application_Resource_AutoloaderTest');
        $suite->addTestSuite('Robo47_Application_Resource_AutoloaderMultiTest');
        $suite->addTestSuite('Robo47_Application_Resource_CacheTest');
        $suite->addTestSuite('Robo47_Application_Resource_CacheMultiTest');
        $suite->addTestSuite('Robo47_Application_Resource_ErrorHandlerTest');
        $suite->addTestSuite('Robo47_Application_Resource_Plugin_ErrorHandlerTest');
        $suite->addTestSuite('Robo47_Application_Resource_ConfigTest');
        $suite->addTestSuite('Robo47_Application_Resource_HtmlPurifierTest');
        $suite->addTestSuite('Robo47_Application_Resource_Service_AkismetTest');
        $suite->addTestSuite('Robo47_Application_Resource_Service_GravatarTest');
        $suite->addTestSuite('Robo47_Application_Resource_Service_BitlyTest');
        $suite->addTestSuite('Robo47_Application_Resource_LogTest');
        $suite->addTestSuite('Robo47_Application_Resource_ObjectTest');
        $suite->addTestSuite('Robo47_Application_Resource_ObjectMultiTest');

        return $suite;
    }
}