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
 * Robo47_Validate_Mock
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Validate_Mock implements Zend_Validate_Interface
{

    /**
     *
     * @var bool
     */
    public $isValid = true;
    /**
     *
     * @var array
     */
    public $errors = array();
    /**
     *
     * @var array
     */
    public $messages = array();
    /**
     *
     * @var mixed
     */
    public $lastValue = null;

    /**
     *
     * @param boolean $isValid
     * @param array $messages
     * @param array $errors
     */
    public function __construct($isValid = true, array $messages = array(),
        array $errors = array())
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->messages = $messages;
    }

    /**
     * Get Errors
     *
     * @return messages
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get Messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Is Valid
     *
     * @param mixed $value
     */
    public function isValid($value)
    {
        $this->lastValue = $value;
        return $this->isValid;
    }
}