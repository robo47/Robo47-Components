<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

require_once TESTS_PATH . 'Robo47/_files/DoctrineTestCase.php';

class Robo47_Paginator_Adapter_DoctrineTestCase extends Robo47_DoctrineTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->setupTableForRecord('Robo47_Paginator_Adapter_DoctrineTestRecord');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function fillTable($number)
    {
        for ($i = 0; $i < $number; $i++) {
            $entry = new Robo47_Paginator_Adapter_DoctrineTestRecord();
            $entry->message = 'entry ' . $i;
            $entry->save();
        }
    }
}