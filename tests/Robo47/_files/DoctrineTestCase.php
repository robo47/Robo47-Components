<?php

require_once 'classes.php';

class Robo47_DoctrineTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $_doctrineDSN = 'sqlite://:memory:';
    /**
     * @var string
     */
    protected $_doctrineConnectionName = 'testing';
    /**
     * @var Doctrine_Connection
     */
    protected $_doctrineConnection = null;
    
    public function setUp()
    {
        $connection = Doctrine_Manager::getInstance()->connection(
                $this->_doctrineDSN,
                $this->_doctrineConnectionName
        );

        $this->_doctrineConnection = $connection;
    }

    public function setupTableForRecord($recordName)
    {
        $record = new $recordName;
        /* @var $record Doctrine_Record */
        $table = $record->getTable();
        $this->_doctrineConnection->export->createTable(
            $table->getTablename(),
            $table->getColumns()
        );
    }
    
    public function tearDown()
    {
        Doctrine_Manager::getInstance()
            ->getConnection($this->_doctrineConnectionName)
            ->close();

        Doctrine_Manager::getInstance()
            ->reset();

        Doctrine_Manager::getInstance()
            ->resetInstance();
    }
}