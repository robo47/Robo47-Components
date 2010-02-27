<?php

require_once 'TestHelper.php';

class AllTests
{

    /**
     * Regular suite
     *
     * All tests except those that require output buffering.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components');

        $suite->addTest(Robo47_AllTests::suite());

        return $suite;
    }
}