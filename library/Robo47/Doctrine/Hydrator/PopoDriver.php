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
 * Robo47_Doctrine_Hydrator_PopoWithTypeDriver
 *
 * Plain Old PHP Object-Hydrator
 *
 * @package     Robo47
 * @subpackage  Convert
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Doctrine_Hydrator_PopoDriver extends Doctrine_Hydrator_RecordDriver//Doctrine_Hydrator_Graph

{

    /**
     * Name of the variable containing the classtype
     *
     * If null, no type set
     *
     * @var string
     */
    public static $typeName = '__type';
    /**
     * Name of the variable containing the classtype
     *
     * If null, no type set
     *
     * @var string
     */
    public static $classname = 'stdClass';

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
            $newData[] = $this->_createPopo($record);
        }
        $data->free(true);
        return $newData;
    }

    /**
     *
     * @param Doctrine_Record $record
     * @return object
     */
    protected function _createPopo(Doctrine_Record $record)
    {
        $object = $this->_getDataFromRecord($record);

        $references = $record->getReferences();

        foreach ($references as $name => $rel) {
            $relation = $this->_getRelation($record, $name);

            if ($relation->isOneToOne()) {
                $subRecord = $record[$name];
                if ($subRecord instanceof Doctrine_Record) {
                    $object->{$name} = $this->_createPopo($subRecord);
                }
            } else {
                $object->{$name} = array();
                foreach ($record[$name] as $subRecord) {
                    if ($subRecord instanceof Doctrine_Record) {
                        $object->{$name}[] = $this->_createPopo($subRecord);
                    }

                }
                if (empty($object->{$name})) {
                    unset($object->{$name});
                }
            }
        }
        return $object;
    }

    /**
     *
     * @param Doctrine_Record $record
     * @param string $name
     * @return Doctrine_Relation
     */
    protected function _getRelation($record, $name)
    {
            $relations = $record->getTable()->getRelations();
            return $relations[$name];
    }

    /**
     *
     * @param Doctrine_Record $record
     * @return object
     */
    protected function _getDataFromRecord($record)
    {
        $data = $record->getData();
        $object = new self::$classname;

        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }

        if (null !== self::$typeName) {
            $object->{self::$typeName} = get_class($record);
        }
        return $object;
    }
}