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
 * Robo47_Log_Filter_Message
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Log_Filter_ValidateProxy extends Zend_Log_Filter_Abstract
{

    /**
     *
     * @var Zend_Validate_Interface
     */
    protected $_validator = null;

    /**
     *
     * @var string|null
     */
    protected $_key = null;

    /**
     *
     * @var bool
     */
    protected $_not = false;

    /**
     *
     * @param Zend_Validate_Interface|string $validator
     * @param string|null $key
     * @param bool $not
     */
    public function __construct($validator, $key = null, $not = false)
    {
        $this->setValidator($validator);
        $this->setKey($key);
        $this->setNot($not);
    }

    /**
     * Get Validator
     *
     * @return Zend_Validate_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    /**
     * Get Not
     * @return bool
     */
    public function getNot()
    {
        return $this->_not;
    }

    /**
     * Get Key
     *
     * @return string|null
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Set Not
     *
     * @param bool $not
     * @return Robo47_Log_Filter_ValidateProxy *Provides Fluent Interface*
     */
    public function setNot($not)
    {
        $not = (bool) $not;
        $this->_not = $not;

        return $this;
    }

    /**
     * Set Key
     *
     * @param string|null $key
     * @return Robo47_Log_Filter_ValidateProxy *Provides Fluent Interface*
     */
    public function setKey($key = null)
    {
        $this->_key = $key;

        return $this;
    }

    /**
     * Set Validator
     *
     * @param Zend_Validate_Interface|string $validator
     * @return Robo47_Log_Filter_ValidateProxy  *Provides Fluent Interface*
     */
    public function setValidator($validator)
    {
        if (is_string($validator)) {
            $validator = new $validator;
        }
        if (!$validator instanceof Zend_Validate_Interface) {
            $message = 'Validator is not instance of Zend_Validate_Interface';
            throw new Robo47_Log_Filter_Exception($message);
        }
        $this->_validator = $validator;

        return $this;
    }

    /**
     * Get Options
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            'not' => $this->getNot(),
            'validator' => $this->getValidator(),
            'key' => $this->getKey(),
        );
    }

    /**
     *
     * @param array $event
     * @return bool
     */
    public function accept($event)
    {
        if (null === $this->_key) {
            $validationData = $event;
        } elseif (isset($event[$this->_key])) {
            $validationData = $event[$this->_key];
        } else {
            $message = 'key "' . $this->_key . '" not found in event';
            throw new Robo47_Log_Filter_Exception($message);
        }

        if (true === $this->_not) {
            return !$this->_validator->isValid($validationData);
        } else {
            return $this->_validator->isValid($validationData);
        }
    }

    /**
     * Construct a Zend_Log driver
     *
     * @param  array|Zend_Config $config
     * @return Robo47_Log_Filter_ValidateProxy
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(
            array(
            'validator' => null,
            'key' => null,
            'not' => false,
            ), $config
        );

        return new Robo47_Log_Filter_ValidateProxy(
            $config['validator'],
            $config['key'],
            $config['not']
        );
    }

}
