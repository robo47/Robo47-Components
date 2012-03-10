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
 * Robo47_Validate_Doctrine_NoRecordExists
 *
 * Completely based on Zend_Validate_Db_Abstract
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
abstract class Robo47_Validate_Doctrine_Abstract extends Zend_Validate_Abstract
{
    /**
     * Error constants
     */

    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => 'No record matching %value% was found',
        self::ERROR_RECORD_FOUND => 'A record matching %value% was found',
    );

    /**
     * @var string
     */
    protected $_table = '';

    /**
     * @var string
     */
    protected $_field = '';

    /**
     * @var array|string|null
     */
    protected $_exclude = null;

    /**
     *
     * @param Doctrine_Table|string $table
     * @param string $field
     * @param string|array $exclude
     */
    public function __construct($table, $field, $exclude = null)
    {
        $this->setTable($table);
        $this->setField($field);
        $this->setExclude($exclude);
    }

    /**
     * Returns the set exclude clause
     *
     * @return string|array
     */
    public function getExclude()
    {
        return $this->_exclude;
    }

    /**
     * Sets a new exclude clause
     *
     * @param string|array $exclude
     * @return Robo47_Validate_Doctrine_Abstract *Provides Flient Interface*
     */
    public function setExclude($exclude)
    {
        $this->_exclude = $exclude;

        return $this;
    }

    /**
     * Returns the set field
     *
     * @return string|array
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * Sets a new field
     *
     * @param string $field
     * @return Robo47_Validate_Doctrine_Abstract *Provides Flient Interface*
     */
    public function setField($field)
    {
        $this->_field = (string) $field;

        return $this;
    }

    /**
     * Set Table
     *
     * @param Doctrine_Table|string $table
     * @return Robo47_Validate_Doctrine_Abstract *Provides Fluent Interface*
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
     * Run query and returns matches, or null if no matches are found.
     *
     * @param  String $value
     * @return Doctrine_Collection when matches are found.
     */
    protected function _query($value)
    {
        $query = $this->getTable()->createQuery();

        $exclude = $this->getExclude();
        if (is_string($exclude) && !empty($exclude)) {
            $query->andWhere($exclude);
        } elseif (is_array($exclude) && !empty($exclude)) {
            $query->andWhere($exclude['field'] . ' != ?', $exclude['value']);
        }

        return $query->andWhere($this->getField() . ' = ?', $value)
                ->limit(1)
                ->execute();
    }

}
