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

    /**
     * Creates the Table for a specific Record
     * 
     * @param string $recordName
     */
    public function setupTableForRecord($recordName)
    {
        $record = new $recordName;
        /* @var $record Doctrine_Record */
        $table = $record->getTable();
        /* @var $table Doctrine_Table */
        $connction = $table->getConnection();
        
        $connction->export->createTable(
            $table->getTablename(),
            $table->getColumns()
        );
    }

    /**
     * @return Doctrine_Connection
     */
    public function getDoctrineConnection()
    {
        return $this->_doctrineConnection;
    }

    
    public function tearDown()
    {
        $manager = Doctrine_Manager::getInstance();
        $manager->getConnection($this->getDoctrineConnection()->getName())
                ->close();
        $manager->reset();
        $manager->resetInstance();
    }
}