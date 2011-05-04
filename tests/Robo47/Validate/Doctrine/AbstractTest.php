<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

require_once TESTS_PATH . 'Robo47/_files/DoctrineTestCase.php';

class Robo47_Validate_Doctrine_NonAbstract
extends Robo47_Validate_Doctrine_Abstract
{

    /**
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        return false;
    }

    /**
     * @param string $value
     * @return Doctrine_Collection
     */
    public function queryDummy($value)
    {
        return $this->_query($value);
    }
}

/**
 * @group Robo47_Validate
 * @group Robo47_Validate_Doctrine
 * @group Robo47_Validate_Doctrine_Abstract
 */
class Robo47_Validate_Doctrine_AbstractTest extends Robo47_DoctrineTestCase
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

    /**
     * @return Doctrine_Table
     */
    public function getTable()
    {
        $entry = new Robo47_Paginator_Adapter_DoctrineTestRecord();
        return $entry->getTable();
    }

    public function testDefaultConstructor()
    {
        $table = $this->getTable();
        $validator = new Robo47_Validate_Doctrine_NonAbstract($table, 'someField');
        $this->assertSame($table, $validator->getTable());
        $this->assertEquals('someField', $validator->getField());
        $this->assertEquals(null, $validator->getExclude());
    }

    /**
     * @covers Robo47_Validate_Doctrine_Abstract::setField
     * @covers Robo47_Validate_Doctrine_Abstract::getField
     */
    public function testGetFieldSetField()
    {
        $validator = new Robo47_Validate_Doctrine_NonAbstract($this->getTable(), 'someField');
        $this->assertEquals('someField', $validator->getField());
        $validator->setField('anotherField');
        $this->assertEquals('anotherField', $validator->getField());
    }

    /**
     * @covers Robo47_Validate_Doctrine_Abstract::setExclude
     * @covers Robo47_Validate_Doctrine_Abstract::getExclude
     */
    public function testSetExcludeGetExclude()
    {
        $validator = new Robo47_Validate_Doctrine_NonAbstract($this->getTable(), 'someField', 'exclude');
        $this->assertEquals('exclude', $validator->getExclude());
        $validator->setExclude('exclude something');
        $this->assertEquals('exclude something', $validator->getExclude());
    }

    /**
     * @covers Robo47_Validate_Doctrine_Abstract::setTable
     * @covers Robo47_Validate_Doctrine_Abstract::getTable
     */
    public function testSetTableGetTable()
    {
        $table1 = $this->getTable();
        $table2 = new Doctrine_Table('baa', $this->getDoctrineConnection());

        $validator = new Robo47_Validate_Doctrine_NonAbstract($table1, 'field');
        $this->assertSame($table1, $validator->getTable());
        $fluentReturn = $validator->setTable($table2);
        $this->assertSame($validator, $fluentReturn);
        $this->assertSame($table2, $validator->getTable());
    }

    /**
     * @covers Robo47_Validate_Doctrine_Abstract::setTable
     */
    public function testSetTableGetTableWithTableName()
    {
        $validator = new Robo47_Validate_Doctrine_NonAbstract($this->getTable(), 'field');
        $validator->setTable('Robo47_Log_Writer_Doctrine_Test_Log');
        $this->assertInstanceOf('Doctrine_Table', $validator->getTable());
        $this->assertEquals('testLog', $validator->getTable()->getTableName());
    }

    /**
     * @covers Robo47_Validate_Doctrine_Abstract::setTable
     */
    public function testSetTableWithInvalidType()
    {
        $validator = new Robo47_Validate_Doctrine_NonAbstract($this->getTable(), 'field');
        try {
            $validator->setTable(new stdClass());
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Writer_Exception $e) {
            $this->assertEquals('table not instance of Doctrine_Table.', $e->getMessage(), 'Wrong Exception message');
        }
    }

    public function queryProvider()
    {
        $data = array();

        $records = array(
            array('message' => 'blub'),
            array('message' => 'bla'),
        );

        $data[] = array($records, 'bla', null, 1);
        $data[] = array($records, 'bla', array('field' => 'message', 'value' => 'bla'), 0);
        $data[] = array($records, 'bla', "message != 'bla'", 0);
        $data[] = array($records, 'bla', array('field' => 'message', 'value' => 'foo'), 1);
        $data[] = array($records, 'bla', "message != 'foo'", 1);

        return $data;
    }

    /**
     *
     * @param Doctrine_Table $table
     * @param array $data
     */
    public function create(Doctrine_Table $table, $data)
    {
        foreach ($data as $row) {
            $table->create($row)
                ->save();
        }
    }

    /**
     * @dataProvider queryProvider
     * @covers Robo47_Validate_Doctrine_Abstract::_query
     */
    public function testQuery($data, $value, $exclude, $expectedResult)
    {
        $table = $this->getTable();
        $this->create($table, $data);
        $validator = new Robo47_Validate_Doctrine_NonAbstract($table, 'message', $exclude);
        $result = $validator->queryDummy($value);
        $this->assertEquals($expectedResult, count($result));
    }
}