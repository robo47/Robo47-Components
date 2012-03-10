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
 * Robo47_Application_Resource_ObjectMulti
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_ObjectMulti extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var array
     */
    protected $_objects = array();

    /**
     *
     */
    public function init()
    {
        if (!empty($this->_options)) {
            foreach ($this->_options as $name => $options) {
                $resource = new Robo47_Application_Resource_Object($options);
                $resource->init();
                $this->_objects[$name] = $resource->getObject();
            }
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_ObjectMulti.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Returns an object
     *
     * @return object
     */
    public function getObject($name)
    {
        if (isset($this->_objects[$name])) {
            return $this->_objects[$name];
        } else {
            $message = 'Object \'' . $name . '\' doesn\'t exist';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Returns an array with all objects
     *
     * @return array
     */
    public function getObjects()
    {
        return $this->_objects;
    }

}
