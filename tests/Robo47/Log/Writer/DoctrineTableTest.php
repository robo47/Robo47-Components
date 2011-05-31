<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

require_once TESTS_PATH . 'Robo47/_files/DoctrineTestCase.php';

/**
 * @group Robo47_Log
 * @group Robo47_Log_Writer
 * @group Robo47_Log_Writer_DoctrineTable
 */
class Robo47_Log_Writer_DoctrineTableTest extends Robo47_DoctrineTestCase
{

    /**
     * @var Doctrine_Table
     */
    protected $_table = null;
    /**
     * @var Doctrine_Record
     */
    protected $_model = null;
    /**
     * @var Doctrine_Table
     */
    protected $_table2 = null;
    /**
     * @var Doctrine_Record
     */
    protected $_model2 = null;
    /**
     *
     * @var Robo47_Log_Writer_DoctrineTable
     */
    protected $_writer = null;

    public function setUp()
    {
        parent::setUp();
        $this->setupTableForRecord('Robo47_Log_Writer_Doctrine_Test_Log');
        $this->setupTableForRecord('Robo47_Log_Writer_Doctrine_Test_Log2');

        $this->_model = new Robo47_Log_Writer_Doctrine_Test_Log();
        $this->_table = $this->_model->getTable();

        $this->_model2 = new Robo47_Log_Writer_Doctrine_Test_Log2();
        $this->_table2 = $this->_model2->getTable();

        $this->_writer = new Robo47_Log_Writer_DoctrineTable($this->_table, array());
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::__construct
     */
    public function testConstruct()
    {
        $mapping = array(
            'message' => 'Foo',
            'priority' => 'baa',
            'category' => 'blub',
            'timestamp' => 'baafoo',
            'priorityName' => 'Bla',
        );
        $writer = new Robo47_Log_Writer_DoctrineTable($this->_table, $mapping);
        $this->assertSame($this->_table, $writer->getTable());
        $this->assertEquals($mapping, $writer->getColumnMap());
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::setTable
     * @covers Robo47_Log_Writer_DoctrineTable::getTable
     */
    public function testSetTableGetTableWithTableInstance()
    {
        $writer = new Robo47_Log_Writer_DoctrineTable($this->_table);

        $this->assertSame($this->_table, $writer->getTable());

        $writer->setTable($this->_table2);

        $this->assertSame($this->_table2, $writer->getTable());
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::setTable
     */
    public function testSetTableGetTableWithTableName()
    {
        $writer = new Robo47_Log_Writer_DoctrineTable($this->_table);
        $writer->setTable('Robo47_Log_Writer_Doctrine_Test_Log');
        $this->assertInstanceOf('Doctrine_Table', $writer->getTable());
        $this->assertEquals('testLog', $writer->getTable()->getTableName());
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::setTable
     */
    public function testSetTableWithInvalidType()
    {
        $writer = new Robo47_Log_Writer_DoctrineTable($this->_table);
        try {
            $writer->setTable(new stdClass());
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Writer_Exception $e) {
            $this->assertEquals('table not instance of Doctrine_Table.', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::setColumnMap
     * @covers Robo47_Log_Writer_DoctrineTable::getColumnMap
     */
    public function testSetColumnMapGetColumnMap()
    {
        $mapping = array(
            'message' => 'Foo',
            'priority' => 'baa',
            'timestamp' => 'baafoo',
            'category' => 'blub',
        );

        $mappingDefault = array(
            'message' => 'message',
            'priority' => 'priority',
            'timestamp' => 'timestamp',
            'category' => 'category',
        );

        $writer = new Robo47_Log_Writer_DoctrineTable($this->_table);

        $this->assertEquals($mappingDefault, $writer->getColumnMap());

        $writer->setColumnMap($mapping);

        $this->assertEquals($mapping, $writer->getColumnMap());
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::_write
     */
    public function testWrite()
    {

        $this->assertEquals(0, $this->_table->count());

        $date = date('c');
        $event = array(
            'message' => 'Foo',
            'priority' => 0,
            'category' => 'bla',
            'timestamp' => $date,
        );
        $this->_writer->write($event);

        $this->assertEquals(1, $this->_table->count());

        $entry = $this->_table
            ->createQuery()
            ->select()
            ->execute()
            ->getFirst();

        $this->assertEquals($event['message'], $entry->message);
        $this->assertEquals($event['priority'], $entry->priority);
        $this->assertEquals($event['category'], $entry->category);
        $this->assertEquals($event['timestamp'], $entry->timestamp);
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::_write
     */
    public function testWriteWithChangedColumnMap()
    {
        $mapping = array(
            'message' => 'foo',
            'priority' => 'baa',
            'category' => 'blub',
            'timestamp' => 'baafoo',
        );

        $this->_writer->setTable($this->_table2);
        $this->_writer->setColumnMap($mapping);

        $this->assertEquals(0, $this->_table2->count());

        $date = date('c');
        $event = array(
            'message' => 'Foo',
            'priority' => 0,
            'category' => 'bla',
            'timestamp' => $date,
        );
        $this->_writer->write($event);

        $this->assertEquals(1, $this->_table2->count());

        $entry = $this->_table2
            ->createQuery()
            ->select()
            ->execute()
            ->getFirst();

        $this->assertEquals($event['message'], $entry->foo);
        $this->assertEquals($event['priority'], $entry->baa);
        $this->assertEquals($event['category'], $entry->blub);
        $this->assertEquals($event['timestamp'], $entry->baafoo);
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::shutdown
     */
    public function testShutdown()
    {
        $this->_writer->shutdown();

        $this->assertNull($this->_writer->getTable());
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::getOptions
     */
    public function testGetOptions()
    {
        $options = $this->_writer->getOptions();
        $this->assertArrayHasKey('columnMap', $options, 'Options misses key columnMap');
        $this->assertArrayHasKey('table', $options, 'Options misses key table');
        $this->assertSame($options['table'], $this->_writer->getTable(), 'table is wrong');
        $this->assertSame($options['columnMap'], $this->_writer->getColumnMap(), 'columnMap is wrong');
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::factory
     */
    public function testFactory()
    {
        $config = array(
            'table' => $this->_table,
            'columnMap' => array(
                'message' => 'Foo',
                'priority' => 'baa',
                'category' => 'blub',
                'timestamp' => 'baafoo',
                'priorityName' => 'Bla',
            ),
        );

        $writer = Robo47_Log_Writer_DoctrineTable::factory($config);

        $this->assertInstanceOf('Robo47_Log_Writer_DoctrineTable', $writer, 'Wrong datatype from factory');

        $this->assertEquals($config['columnMap'], $writer->getColumnMap(), 'ColumnMap is wrong');
        $this->assertEquals($config['table'], $writer->getTable(), 'Table is wrong');
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::factory
     */
    public function testFactoryWithZendConfig()
    {
        $config = array(
            'table' => $this->_table,
            'columnMap' => array(
                'message' => 'Foo',
                'priority' => 'baa',
                'category' => 'blub',
                'timestamp' => 'baafoo',
                'priorityName' => 'Bla',
            ),
        );
        $config = new Zend_Config($config);

        $writer = Robo47_Log_Writer_DoctrineTable::factory($config);

        $config = $config->toArray();

        $this->assertInstanceOf('Robo47_Log_Writer_DoctrineTable', $writer, 'Wrong datatype from factory');

        $this->assertEquals($config['columnMap'], $writer->getColumnMap(), 'ColumnMap is wrong');
        $this->assertEquals($config['table'], $writer->getTable(), 'Table is wrong');
    }

    /**
     * @covers Robo47_Log_Writer_DoctrineTable::factory
     * @covers Robo47_Log_Writer_Exception
     */
    public function testFactoryWithoutTableThrowsException()
    {
        $config = array();

        try {
            $writer = Robo47_Log_Writer_DoctrineTable::factory($config);
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Writer_Exception $e) {
            $this->assertEquals('No table defined for Robo47_Log_Writer_DoctrineTable', $e->getMessage(), 'Wrong Exception message');
        }
    }
}