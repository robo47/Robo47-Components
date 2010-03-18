<?php
require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

class Robo47_Doctrine_AllTests extends PHPUnit_Framework_TestCase
{

    /**
     * Creates and returns this test suite
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Robo47 Components - Robo47_Doctrine');

        $suite->addTestSuite('Robo47_Doctrine_Hydrator_PopoDriverTest');

        return $suite;
    }
}