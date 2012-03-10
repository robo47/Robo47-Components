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
 * Robo47_Application_Resource_Object
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Object
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var object
     */
    protected $_object = null;

    /**
     *
     */
    public function init()
    {
        if (!empty($this->_options)) {
            $this->_object = $this->_setupObject($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Object.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup object based on classname, params, variables and calling methods
     *
     * @param array $options
     * @returns object
     */
    protected function _setupObject($options)
    {
        if (!isset($options['classname'])) {
            $message = 'no classname found for Object resource';
            throw new Robo47_Application_Resource_Exception($message);
        }

        if (!isset($options['params'])) {
            $object = $this->_initObject($options['classname']);
        } else {
            $object = $this->_initObject(
                $options['classname'], $options['params']
            );
        }

        if (isset($options['variables'])) {
            $this->_setVariables($object, $options['variables']);
        }

        if (isset($options['staticVariables'])) {
            $this->_setStaticVariables(
                $options['classname'], $options['staticVariables']
            );
        }

        if (isset($options['functions'])) {
            $this->_callFunctions($object, $options['functions']);
        }

        if (isset($options['staticFunctions'])) {
            $this->_callFunctions(
                $options['classname'], $options['staticFunctions']
            );
        }

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $object);
        }

        return $object;
    }

    /**
     * Set static variables on a class
     *
     * @param string $classname
     * @param string $variable
     * @param mixed $value
     */
    protected function _setStaticVariables($classname, $variables)
    {
        foreach ($variables as $name => $value) {
            $this->_setStaticVariable($classname, $name, $value);
        }

        return $this;
    }

    /**
     * Set static variable on a class
     *
     * @param string $classname
     * @param string $name
     * @param mixed $value
     * @return Robo47_Application_Resource_Object *Provides Fluent Interface*
     */
    protected function _setStaticVariable($classname, $name, $value)
    {
        $ref = new ReflectionClass($classname);
        $ref->setStaticPropertyValue($name, $value);

        return $this;
    }

    /**
     * Init Object
     *
     * @param string $class
     * @param array $params
     * @return object
     */
    protected function _initObject($class, array $params = array())
    {
        $ref = new Zend_Reflection_Class($class);
        if (!empty($params)) {
            return $ref->newInstanceArgs($params);
        } else {
            return $ref->newInstance();
        }
    }

    /**
     * Sets variables on an object
     *
     * @param object $object
     * @param array $variables
     * @return Robo47_Application_Resource_Object *Provides Fluent Interface*
     */
    protected function _setVariables($object, array $variables = array())
    {
        foreach ($variables as $variable => $value) {
            $this->_setVariable($object, $variable, $value);
        }

        return $this;
    }

    /**
     * Set a variable on an object
     *
     * @param object $object
     * @param string $name
     * @param mixed $value
     * @return Robo47_Application_Resource_Object *Provides Fluent Interface*
     */
    protected function _setVariable($object, $name, $value)
    {
        $object->{$name} = $value;

        return $this;
    }

    /**
     * Calls functions on a object
     *
     * @param object $object
     * @param array $functions
     * @return Robo47_Application_Resource_Object *Provides Fluent Interface*
     */
    protected function _callFunctions($object, array $functions = array())
    {
        foreach ($functions as $function) {
            if (isset($function[0]) &&
                is_string($function[0])) {

                $func = $function[0];
                $params = array();
                if (isset($function[1])) {
                    $params = $function[1];
                }
                if (is_array($params)) {
                    $this->_callFunction($object, $func, $params);
                } else {
                    $message = 'invalid parameters for function ' . $func;
                    throw new Robo47_Application_Resource_Exception($message);
                }
            } else {
                $message = 'no valid functionname found on function';
                throw new Robo47_Application_Resource_Exception($message);
            }
        }

        return $this;
    }

    /**
     * Calls a function on a object with params
     *
     * @param object|string $object
     * @param string $name
     * @param array $params
     * @return Robo47_Application_Resource_Object *Provides Fluent Interface*
     */
    protected function _callFunction($object, $name, array $params = array())
    {
        call_user_func_array(array($object, $name), $params);

        return $this;
    }

    /**
     * Get Object
     *
     * @return object
     */
    public function getObject()
    {
        return $this->_object;
    }

}
