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
 * Robo47_Auth_Adapter_Array
 *
 * @package     Robo47
 * @subpackage  Auth
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Auth_Adapter_Array implements Zend_Auth_Adapter_Interface
{

    /**
     * Username
     *
     * @var string
     */
    protected $_username = '';

    /**
     * Password
     *
     * @var string
     */
    protected $_password = '';

    /**
     * Array with valid user-data
     *
     * @var array
     */
    protected $_userdata = array();

    /**
     * @param string $username
     * @param string $password
     * @param array  $userData
     */
    public function __construct($username = '', $password = '',
        array $userData = array())
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setUserData($userData);
    }

    /**
     *
     * @param string $username
     * @return Robo47_Auth_Adapter_Array *Provides Fluent Interface*
     */
    public function setUsername($username)
    {
        $this->_username = $username;

        return $this;
    }

    /**
     *
     * @param string $password
     * @return Robo47_Auth_Adapter_Array *Provides Fluent Interface*
     */
    public function setPassword($password)
    {
        $this->_password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param array $userdata Array with index -> username, value = password
     * @return Robo47_Auth_Adapter_Array *Provides Fluent Interface*
     */
    public function setUserData(array $userData = array())
    {
        $this->_userdata = $userData;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getUserData()
    {
        return $this->_userdata;
    }

    /**
     * @param string $username
     * @param string $password
     * @return boolean
     */
    protected function _isAllowedUser($username, $password)
    {
        return ($username == $this->_username && $password == $this->_password);
    }

    /**
     * @param integer $result
     * @return Zend_Auth_Result
     */
    protected function _getResultObject($result)
    {
        return new Zend_Auth_Result($result, $this->_username);
    }

    /**
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        foreach ($this->_userdata as $username => $password) {
            if ($this->_isAllowedUser($username, $password)) {
                return $this->_getResultObject(Zend_Auth_Result::SUCCESS);
            }
        }

        return $this->_getResultObject(Zend_Auth_Result::FAILURE);
    }

}
