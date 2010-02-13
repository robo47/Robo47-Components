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
 * Robo47_Log_Writer_Mock
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Log_Writer_Mock extends Robo47_Log_Writer_Abstract
{

    /**
     * Array of logged events.
     *
     * @var array
     */
    public $events = array();

    /**
     * True if shutdown() was called.
     *
     * @var boolean
     */
    public $shutdown = false;

    /**
     * Array with all arguments of the constructor
     *
     * @var array
     */
    public $constructorParams = array();

    /**
     *
     */
    public function __construct()
    {
        $this->constructorParams = func_get_args();
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function _write($event)
    {
        $this->events[] = $event;
    }

    /**
     * Record shutdown
     *
     * @return void
     */
    public function shutdown()
    {
        $this->shutdown = true;
    }

    /**
     * Create a new instance of Zend_Log_Writer_Mock
     *
     * @param  array|Zend_Config $config
     * @return Robo47_Log_Writer_Mock
     */
    static public function factory($config)
    {
        return new Robo47_Log_Writer_Mock($config);
    }
}
