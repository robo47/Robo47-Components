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
    /**
     * @var array
     */
    protected $_tablesToCreate = array();
    
    public function setUp()
    {
        $connection = Doctrine_Manager::getInstance()->connection(
                $this->_doctrineDSN,
                $this->_doctrineConnectionName
        );

        $this->_doctrineConnection = $connection;

        foreach ($this->_tablesToCreate as $table => $definition) {
            $connection->export->createTable($table, $definition);
        }
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