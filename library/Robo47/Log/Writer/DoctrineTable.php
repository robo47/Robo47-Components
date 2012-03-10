<?php

/**
 * Robo47 Components
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://robo47.net/licenses/new-bsd-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to robo47[at]robo47[dot]net so I can send you a copy immediately.
 *
 * @category   Robo47
 * @package    Robo47
 * @copyright  Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license    http://robo47.net/licenses/new-bsd-license New BSD License
 */

/**
 * Robo47_Log_Writer_Mock
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Log_Writer_DoctrineTable extends Robo47_Log_Writer_Abstract
{

    /**
     * Mapping-Array
     *
     * Array for mapping columns from event to table
     *
     * @var array
     */
    protected $_columnMap = array(
        'message' => 'message',
        'priority' => 'priority',
        'category' => 'category',
        'timestamp' => 'timestamp',
    );

    /**
     * Used Table to which logs get written
     *
     * @var Doctrine_Table
     */
    protected $_table = null;

    /**
     *
     * @param Doctrine_Table|string $table
     * @param array $columnMap
     */
    public function __construct($table, array $columnMap = array())
    {
        $this->setTable($table);
        $this->setColumnMap($columnMap);
    }

    /**
     * Set Table
     *
     * @param Doctrine_Table|string $table
     * @return Robo47_Log_Writer_DoctrineTable *Provides Fluent Interface*
     */
    public function setTable($table)
    {

        if (is_string($table)) {
            $table = Doctrine_Core::getTable($table);
        }
        if (!$table instanceof Doctrine_Table) {
            $message = 'table not instance of Doctrine_Table.';
            throw new Robo47_Log_Writer_Exception($message);
        }
        $this->_table = $table;

        return $this;
    }

    /**
     * Get Table
     *
     * @return Doctrine_Table
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Set ColumnMap
     *
     * @param array $columnMap
     * @return Robo47_Log_Writer_DoctrineTable *Provides Fluent Interface*
     */
    public function setColumnMap(array $columnMap)
    {
        if (count($columnMap) > 0) {
            $this->_columnMap = $columnMap;
        }

        return $this;
    }

    /**
     * Get ColumnMap
     *
     * @return array
     */
    public function getColumnMap()
    {
        return $this->_columnMap;
    }

    /**
     * Get Options
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            'columnMap' => $this->getColumnMap(),
            'table' => $this->getTable(),
        );
    }

    /**
     * Write Event to database
     *
     * @param  array  $event
     */
    public function _write($event)
    {
        $entry = $this->_table->create(array());
        foreach ($this->_columnMap as $eventIndex => $tableColumn) {
            $entry->$tableColumn = $event[$eventIndex];
        }
        $entry->save();
    }

    /**
     * Shutdown
     */
    public function shutdown()
    {
        $this->_table = null;
    }

    /**
     * Construct a Zend_Log driver
     *
     * @param  array|Zend_Config $config
     * @return Robo47_Log_Writer_DoctrineTable
     */
    static public function factory($config)
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }
        if (!isset($config['table'])) {
            $message = 'No table defined for Robo47_Log_Writer_DoctrineTable';
            throw new Robo47_Log_Writer_Exception($message);
        }
        $writer = new Robo47_Log_Writer_DoctrineTable($config['table']);
        if (isset($config['columnMap'])) {
            $writer->setColumnMap($config['columnMap']);
        }

        return $writer;
    }

}
