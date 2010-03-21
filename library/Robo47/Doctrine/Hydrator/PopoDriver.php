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
 * Robo47_Doctrine_Hydrator_PopoDriver
 *
 * Popo = Plain Old PHP Object-Hydrator
 *
 * @package     Robo47
 * @subpackage  Convert
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Doctrine_Hydrator_PopoDriver extends Doctrine_Hydrator_RecordDriver
{

    /**
     * Name of the variable containing the classtype
     *
     * If null, no type set
     *
     * @var string
     */
    protected static $_typename = '__type';

    /**
     * Name of the Popo-base-class
     *
     * @var string
     */
    protected static $_classname = 'stdClass';

    /**
     *
     * @param string $classname
     */
    public static function setDefaultClassname($classname)
    {
        if (!is_string($classname))  {
            $message = 'Invalid type for $classname: ' .
                Robo47_Core::getType($typename);
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        self::$_classname = $classname;
    }

    /**
     *
     * @param string|null $typename
     */
    public static function setDefaultTypename($typename)
    {
        if(null !== $typename && !is_string($typename)) {
            $message = 'Invalid type for $typename: ' .
                Robo47_Core::getType($typename);
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        self::$_typename = $typename;
    }

    /**
     * @return string|null
     */
    public static function getDefaultTypename()
    {
        return self::$_typename;
    }

    /**
     * @return string
     */
    public static function getDefaultClassname()
    {
        return self::$_classname;
    }

    /**
     * @var PDOStatement $stmt
     */
    public function hydrateResultSet($stmt)
    {
        $data = parent::hydrateResultSet($stmt);
        /* @var $data Doctrine_Collection */
        $newData = array();
        foreach ($data as $record) {
            /* @var $record Doctrine_Record */
            $newData[] = $this->_createPopoFromRecord($record);
        }
        
        $data->free(true);
        return $newData;
    }

    /**
     *
     * @param Doctrine_Record $record
     * @return object
     */
    protected function _createPopoFromRecord(Doctrine_Record $record)
    {
        $popo = $this->_createEmptyPopo(self::getDefaultClassname());
        $this->_setDataFromRecord($popo, $record);
        $this->_setTypeFromRecord($popo, $record);
        $this->_setReferencesFromRecord($popo, $record);
        return $popo;
    }

    /**
     * @return object
     */
    protected function _createEmptyPopo($classname)
    {
        if (!class_exists($classname)) {
            $message = 'Class ' . $classname . ' not found';
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        return new $classname;
    }

    /**
     * Sets the data from the record to the Popo
     *
     * Sets the Data of the Record
     * @param object $popo
     * @param Doctrine_Record $record
     */
    protected function _setDataFromRecord($popo, Doctrine_Record $record)
    {
        $data = $record->getData();
        foreach ($data as $key => $value) {
            $popo->{$key} = $value;
        }
    }

    /**
     * Sets the type of the popo into a variable (defaults to __type)
     *
     * @param object $popo
     * @param Doctrine_Record $record
     */
    protected function _setTypeFromRecord($popo, Doctrine_Record $record)
    {
        $typename = self::getDefaultTypename();
        if (null !== $typename) {
            $popo->{$typename} = get_class($record);
        }
    }

    /**
     * Create Popo from a Record
     *
     * @param Doctrine_Record $record
     * @return object
     */
    protected function _setReferencesFromRecord($popo, Doctrine_Record $record)
    {
        $references = $record->getReferences();

        foreach ($references as $relationName => $rel) {
            $relation = $this->_getRelation($record, $relationName);

            if ($relation->isOneToOne()) {
                $this->_setOneToOneRelationFromRecord($popo, $record, $relationName);
            } else {
                $this->_setManyRelationFromRecord($popo, $record, $relationName);
            }
        }
    }

    /**
     * @param object $popo
     * @param Doctrine_Record $record
     * @param string $relationName
     */
    protected function _setOneToOneRelationFromRecord($popo, Doctrine_Record $record, $relationName)
    {
        $subRecord = $record[$relationName];
        // empty relation my be null
        if ($subRecord instanceof Doctrine_Record) {
                $popo->{$relationName} = $this->_createPopoFromRecord($subRecord);
        }
    }

    /**
     * @param object $popo
     * @param Doctrine_Record $record
     * @param string $relationName
     */
    protected function _setManyRelationFromRecord($popo, Doctrine_Record $record, $relationName)
    {
        $popo->{$relationName} = array();
        $relationCollection = $record[$relationName];
        /* @var $relationCollection Doctrine_Collection */
        foreach ($relationCollection as $subRecord) {
            $popo->{$relationName}[] = $this->_createPopoFromRecord($subRecord);
        }
        // Delete completely empty relations (can be empty if recursion is too deep)
        if (empty($popo->{$relationName})) {
            unset($popo->{$relationName});
        }
    }

    /**
     *
     * @param Doctrine_Record $record
     * @param string $relationName
     * @return Doctrine_Relation
     */
    protected function _getRelation(Doctrine_Record $record, $relationName)
    {
        $relations = $record->getTable()->getRelations();
        return $relations[$relationName];
    }
}