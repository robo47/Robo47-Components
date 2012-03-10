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
 * Robo47_Log
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @uses Zend_Log
 * @todo        Factory support for options, categories, saving
 *              instance into zend_registry ...
 */
class Robo47_Log extends Zend_Log
{

    /**
     * @var string
     */
    protected $_defaultCategory = null;

    /**
     * @param array|Zend_Config $config
     * @return Robo47_Log
     */
    static public function factory($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config) || empty($config)) {
            $message = 'Configuration must be an array or ' .
                'instance of Zend_Config';
            throw new Robo47_Log_Exception($message);
        }

        $log = new Robo47_Log();

        if (!is_array(current($config))) {
            $log->addWriter(current($config));
        } else {
            foreach ($config as $writer) {
                $log->addWriter($writer);
            }
        }

        return $log;
    }

    /**
     * Get Writers
     *
     * @return array|Zend_Log_Writer_Interface[]
     */
    public function getWriters()
    {
        return $this->_writers;
    }

    /**
     * Removes a writer
     *
     * Accepts the Writer or the classname as string
     *
     * @param string|mixed $class
     * @return Robo47_Log *Provides Fluent Interface*
     */
    public function removeWriter($class)
    {
        foreach ($this->_writers as $key => $writer) {
            if (is_string($class)) {
                if ($writer instanceof $class) {
                    unset($this->_writers[$key]);
                }
            } elseif (is_object($class)) {
                if ($writer === $class) {
                    unset($this->_writers[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * Removes a Filter
     *
     * Accepts the Filter or the classname as string
     *
     * @param string|Robo47_Log_Filter_Interface $class
     * @return Robo47_Log *Provides Fluent Interface*
     */
    public function removeFilter($class)
    {
        foreach ($this->_filters as $key => $filter) {
            if (is_string($class)) {
                if ($filter instanceof $class) {
                    unset($this->_filters[$key]);
                }
            } elseif (is_object($class)) {
                if ($filter === $class) {
                    unset($this->_filters[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * Get Filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Log
     *
     * @param string  $message
     * @param integer $priority
     * @param mixed   $extras
     */
    public function log($message, $priority, $extras = null)
    {
        if (!isset($extras['category']) && $this->_defaultCategory !== null) {
            $extras['category'] = $this->getDefaultCategory();
        }
        parent::log($message, $priority, $extras);
    }

    /**
     * Get Priorities
     *
     * @return array
     */
    public function getPriorities()
    {
        return $this->_priorities;
    }

    /**
     * Set default Category
     *
     * @param string $category
     * @return Robo47_Log *Provides Fluent Interface*
     */
    public function setDefaultCategory($category = null)
    {
        $this->_defaultCategory = $category;

        return $this;
    }

    /**
     * Get default Category
     *
     * @return string
     */
    public function getDefaultCategory()
    {
        return $this->_defaultCategory;
    }

}
