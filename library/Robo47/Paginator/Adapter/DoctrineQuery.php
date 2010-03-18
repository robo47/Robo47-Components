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
 * Robo47_Paginator_Adapter_DoctrineQuery
 *
 * Paginator for Doctrine_Query
 *
 * @package     Robo47
 * @subpackage  Paginator
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Paginator_Adapter_DoctrineQuery
implements Zend_Paginator_Adapter_Interface
{

    /**
     * @var Doctrine_Query
     */
    protected $_query;

    /**
     *
     * @param Doctrine_Query $query
     */
    public function __construct(Doctrine_Query $query)
    {
        $this->setQuery($query);
    }

    /**
     *
     * @param Doctrine_Query $query
     * @return Robo47_Paginator_Adapter_DoctrineQuery *Provides Fluent Interface*
     */
    public function setQuery(Doctrine_Query $query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     *
     * @return Doctrine_Query
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Returns the total number of rows in the collection.
     *
     * Implements SPL::Countable::count()
     *
     * @return integer
     */
    public function count()
    {
        return $this->_query->count();
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $data = $this->_query
            ->limit($itemCountPerPage)
            ->offset($offset)
            ->execute();
        if ($data instanceof Doctrine_Collection) {
            return $data->getData();
        } elseif(is_array($data)) {
            return $data;
        } else {
            $message = 'Unexpected datatype for getItems(): ' .
                        Robo47_Core::getType($data);
            throw new Robo47_Paginator_Adapter_Exception($message);
        }
    }
}
