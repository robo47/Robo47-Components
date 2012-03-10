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
 * Robo47_Paginator_Adapter_DoctrineTable
 *
 * Paginator for Doctrine_Table which uses the Doctrine_Table::createQuery()
 *
 * @package     Robo47
 * @subpackage  Paginator
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Paginator_Adapter_DoctrineTable extends Robo47_Paginator_Adapter_DoctrineQuery
{

    /**
     * @var Doctrine_Query
     */
    protected $_query;

    /**
     * @var Doctrine_Table
     */
    protected $_table;

    /**
     *
     * @param Doctrine_Table|string $table
     */
    public function __construct($table)
    {
        $this->setTable($table);
    }

    /**
     * Set Table
     *
     * @param Doctrine_Table|string $table
     * @return Robo47_Paginator_Adapter_DoctrineTable *Provides Fluent Interface*
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
        $this->setQuery($table->createQuery()->select());

        return $this;
    }

    /**
     *
     * @return Doctrine_Table
     */
    public function getTable()
    {
        return $this->_table;
    }

}
