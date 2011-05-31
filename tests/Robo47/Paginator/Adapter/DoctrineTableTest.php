<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Paginator
 * @group Robo47_Paginator_Adapter
 * @group Robo47_Paginator_Adapter_DoctrineTable
 */
class Robo47_Paginator_Adapter_DoctrineTableTest extends Robo47_Paginator_Adapter_DoctrineTestCase
{

    /**
     * @return Doctrine_Table
     */
    public function getTable()
    {
        $entry = new Robo47_Paginator_Adapter_DoctrineTestRecord();
        return $entry->getTable();
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::setTable
     * @covers Robo47_Paginator_Adapter_DoctrineTable::getTable
     */
    public function testSetTableGetTable()
    {
        $table1 = $this->getTable();
        $table2 = new Doctrine_Table('baa', $this->getDoctrineConnection());

        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($table1);
        $this->assertSame($table1, $paginator->getTable());
        $fluentReturn = $paginator->setTable($table2);
        $this->assertSame($paginator, $fluentReturn);
        $this->assertSame($table2, $paginator->getTable());
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::setTable
     */
    public function testSetTableGetTableWithTableName()
    {
        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($this->getTable());
        $paginator->setTable('Robo47_Log_Writer_Doctrine_Test_Log');
        $this->assertInstanceOf('Doctrine_Table', $paginator->getTable());
        $this->assertEquals('testLog', $paginator->getTable()->getTableName());
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::setTable
     */
    public function testSetTableWithInvalidType()
    {
        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($this->getTable());
        try {
            $paginator->setTable(new stdClass());
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Writer_Exception $e) {
            $this->assertEquals('table not instance of Doctrine_Table.', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::__construct
     */
    public function testConstruction()
    {
        $table1 = new Doctrine_Table('foo', $this->getDoctrineConnection());
        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($table1);
        $this->assertSame($table1, $paginator->getTable());
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::count
     */
    public function testCount()
    {
        $this->fillTable(10);
        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($this->getTable());
        $this->assertEquals(10, count($paginator));
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineTable::getItems
     */
    public function testGetItems()
    {
        $this->fillTable(10);
        $paginator = new Robo47_Paginator_Adapter_DoctrineTable($this->getTable());
        $this->assertEquals(10, count($paginator));

        $elements = $paginator->getItems(0, 2);
        $this->assertEquals(2, count($elements));
        $this->assertEquals('entry 0', $elements[0]->message);
        $this->assertEquals('entry 1', $elements[1]->message);

        $elements = $paginator->getItems(3, 4);
        $this->assertEquals(4, count($elements));
        $this->assertEquals('entry 3', $elements[0]->message);
        $this->assertEquals('entry 4', $elements[1]->message);
        $this->assertEquals('entry 5', $elements[2]->message);
        $this->assertEquals('entry 6', $elements[3]->message);

        $elements = $paginator->getItems(0, 10);
        $this->assertEquals(10, count($elements));
        for ($i = 0; $i < 10; $i++) {
            $this->assertEquals('entry ' . $i, $elements[$i]->message);
        }

        $elements = $paginator->getItems(10, 0);
        $this->assertEquals(0, count($elements));
    }
}