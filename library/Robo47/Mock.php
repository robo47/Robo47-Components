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
 * Robo47_Mock
 *
 * Class for Unittests to test __get/__set/__call
 * No static-support yet (needs php 5.3 at least with __callStatic)
 *
 * @package     Robo47
 * @subpackage  Mock
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Mock
{

    /**
     *
     * @var array
     */
    protected $_data = array();
    /**
     *
     * @var array
     */
    public $mockData = array(
        'call' => array(),
        'get' => array(),
        'set' => array(),
    );

    /**
     *
     */
    public function __construct()
    {
        $args = func_get_args();
        $this->_logCall('__construct', $args);
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $this->_logCall($name, $arguments);
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     */
    protected function _logCall($name, $arguments)
    {
        $this->mockData['call'][] = array($name, $arguments);
    }

    /**
     *
     * @param string $name
     */
    protected function _logGet($name)
    {
        $this->mockData['get'][] = $name;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    protected function _logSet($name, $value)
    {
        $this->mockData['set'][] = array($name, $value);
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
        $this->_logSet($name, $value);
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $this->_logGet($name);
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return null;
    }
}