<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Form_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Form');

        if (TESTS_ROBO47_FORM_DECORATOR_INFO) {
            $suite->addTestSuite('Robo47_Form_Decorator_InfoTest');
        }
        if (TESTS_ROBO47_FORM_ELEMENT_CKEDITOR) {
            $suite->addTestSuite('Robo47_Form_Element_CkeditorTest');
        }
        return $suite;
    }
}