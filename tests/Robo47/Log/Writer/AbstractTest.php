<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @todo test with multiple filters
 */
class Robo47_Log_Writer_AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Robo47_Log_Writer_Mock
     */
    protected $_writer = null;

    public function setUp()
    {
        $this->_writer = new Robo47_Log_Writer_Mock();
    }

    public function tearDown()
    {
        $this->_writer = null;
    }

    /**
     * @covers Robo47_Log_Writer_Abstract
     * @covers Robo47_Log_Writer_Abstract<extended>
     */
    public function testDefaultConstruct()
    {
        $writer = new Robo47_Log_Writer_Mock();
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::write
     */
    public function testWrite()
    {
        $this->assertEquals(0, count($this->_writer->events));

        $this->_writer->write(array('foo' => 'bla'));

        $this->assertEquals(1, count($this->_writer->events));

        $this->assertArrayHasKey(0, $this->_writer->events);
        $this->assertArrayHasKey('foo', $this->_writer->events[0]);
        $this->assertEquals('bla', $this->_writer->events[0]['foo']);
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::write
     */
    public function testWriteWithFilterAccepting()
    {
        $filter = new Robo47_Log_Filter_Mock(true);
        $this->_writer->addFilter($filter);

        $this->_writer->write(array('foo' => 'bla'));

        $this->assertEquals(1, count($this->_writer->events));
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::write
     */
    public function testWriteWithFilterNotAccepting()
    {
        $filter = new Robo47_Log_Filter_Mock(false);
        $this->_writer->addFilter($filter);

        $this->_writer->write(array('foo' => 'bla'));

        $this->assertEquals(0, count($this->_writer->events));
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::addFilter
     * @covers Robo47_Log_Writer_Abstract::getFilters
     */
    public function testAddFilter()
    {
        $filter = new Robo47_Log_Filter_Mock();

        $filters = $this->_writer->getFilters();
        $this->assertEquals(0, count($filters));

        $this->_writer->addFilter($filter);

        $filters = $this->_writer->getFilters();
        $this->assertEquals(1, count($filters));

        $this->assertContains($filter, $filters);
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::removeFilter
     */
    public function testRemoveFilterByClass()
    {
        $filter = new Robo47_Log_Filter_Mock();
        $filter2 = new Robo47_Log_Filter_Category(array());
        $this->_writer->addFilter($filter);
        $this->_writer->addFilter($filter2);

        $filters = $this->_writer->getFilters();
        $this->assertEquals(2, count($filters));

        $this->_writer->removeFilter('Robo47_Log_Filter_Category');

        $filters = $this->_writer->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertNotContains($filter2, $filters);
        $this->assertContains($filter, $filters);
    }

    /**
     * @covers Robo47_Log_Writer_Abstract::removeFilter
     */
    public function testRemoveFilterByInstance()
    {
        $filter = new Robo47_Log_Filter_Mock();
        $filter2 = new Robo47_Log_Filter_Category(array());
        $this->_writer->addFilter($filter);
        $this->_writer->addFilter($filter2);

        $filters = $this->_writer->getFilters();
        $this->assertEquals(2, count($filters));

        $this->_writer->removeFilter($filter2);

        $filters = $this->_writer->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertNotContains($filter2, $filters);
        $this->assertContains($filter, $filters);
    }
}