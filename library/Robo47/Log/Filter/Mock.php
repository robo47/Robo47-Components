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
 * Robo47_Log_Filter_Mock
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Log_Filter_Mock extends Zend_Log_Filter_Abstract
{
    /**
     * @var bool
     */
    public $accept = true;

    /**
     * @var array|null
     */
    public $lastEvent = null;

    /**
     *
     * @var array
     */
    public $events = array();

    /**
     * @var array
     */
    public $constructorParams = array();

    /**
     *
     * @param bool $accept
     */
    public function __construct ($accept = true)
    {
        $this->accept = $accept;
        $this->constructorParams = func_get_args();
    }

    /**
     * Get Options
     *
     * @return array
     */
    public function getOptions()
    {
        return array();
    }

    /**
     * Accept
     *
     * @return boolean
     */
    public function accept($event)
    {
        $this->lastEvent = $event;
        $this->events[] = $event;
        return $this->accept;
    }

    /**
     * Construct a Zend_Log driver
     *
     * @param  array|Zend_Config $config
     * @return Robo47_Log_Filter_Mock
     */
    static public function factory($config)
    {
        return new Robo47_Log_Filter_Mock($config);
    }
}
