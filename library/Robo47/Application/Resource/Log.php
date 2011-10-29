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
 * Robo47_Application_Resource_Log
 *
 * Resource for setting up an Robo47_Log Instance
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Log
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Robo47_Log
     */
    protected $_log = null;

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_log = $this->_setupLog($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Log.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Log
     *
     * @param array $options
     * @return Robo47_Log
     */
    protected function _setupLog($options)
    {
        $registryKey = null;
        if (isset($options['registryKey'])) {
            $registryKey = $options['registryKey'];
            unset($options['registryKey']);
        }
        $log = Robo47_Log::factory($options);

        if (null !== $registryKey) {
            Zend_Registry::set($registryKey, $log);
        }
        return $log;
    }

    /**
     * Get Log
     *
     * @return Robo47_Log
     */
    public function getLog()
    {
        return $this->_log;
    }
}
