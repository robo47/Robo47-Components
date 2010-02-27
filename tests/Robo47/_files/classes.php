<?php

class Robo47_Paginator_Adapter_DoctrineTestRecord extends Doctrine_Record
{
    
    public function setTableDefinition()
    {
        $this->setTableName('testPagination');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('message', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
    }
    
    public function setUp()
    {
        parent::setUp();
    }
}

class Robo47_Log_Writer_Doctrine_Test_Log extends Doctrine_Record
{
    
    public function setTableDefinition()
    {
        $this->setTableName('testLog');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('message', 'string', 2147483647, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '2147483647',
        ));
        $this->hasColumn('category', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('timestamp', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('priority', 'string', 255, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'length' => '8',
        ));
    }
    
    public function setUp()
    {
        parent::setUp();
    }
}

class Robo47_Log_Writer_Doctrine_Test_Log2 extends Doctrine_Record
{
    
    public function setTableDefinition()
    {
        $this->setTableName('testLog2');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('foo', 'string', 2147483647, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '2147483647',
        ));
        $this->hasColumn('baa', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('baafoo', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('blub', 'string', 255, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'length' => '8',
        ));
    }
    
    public function setUp()
    {
        parent::setUp();
    }
}