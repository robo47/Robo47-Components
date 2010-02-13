<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Paginator_Adapter_DoctrineQueryTest extends Robo47_Paginator_Adapter_DoctrineTestCase
{

    /**
     * @return Doctrine_Query
     */
    public function getQuery()
    {
        $entry = new Robo47_Paginator_Adapter_DoctrineTestRecord();
        return $entry->getTable()->createQuery();
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineQuery::setQuery
     * @covers Robo47_Paginator_Adapter_DoctrineQuery::getQuery
     */
    public function testSetQueryGetQuery()
    {
        $query1 = new Doctrine_Query();
        $query2 = new Doctrine_Query();

        $paginator = new Robo47_Paginator_Adapter_DoctrineQuery($query1);
        $this->assertSame($query1, $paginator->getQuery());
        $fluentReturn = $paginator->setQuery($query2);
        $this->assertSame($paginator, $fluentReturn);
        $this->assertSame($query2, $paginator->getQuery());
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineQuery<extended>
     * @covers Robo47_Paginator_Adapter_DoctrineQuery::__construct
     */
    public function testConstruction()
    {
        $query1 = new Doctrine_Query();
        $paginator = new Robo47_Paginator_Adapter_DoctrineQuery($query1);
        $this->assertSame($query1, $paginator->getQuery());
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineQuery::count
     */
    public function testCount()
    {
        $this->fillTable(10);
        $paginator = new Robo47_Paginator_Adapter_DoctrineQuery($this->getQuery());
        $this->assertEquals(10, count($paginator));
    }

    /**
     * @covers Robo47_Paginator_Adapter_DoctrineQuery::getItems
     */
    public function testGetItems()
    {
        $this->fillTable(10);
        $paginator = new Robo47_Paginator_Adapter_DoctrineQuery($this->getQuery());
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