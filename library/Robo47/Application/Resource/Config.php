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
 * Robo47_Application_Resource_Config
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Config
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Zend_Config
     */
    protected $_config = null;

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_config = $this->_setupConfig($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Config.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Config
     *
     * @param array $options
     */
    protected function _setupConfig($options)
    {
        if (!isset($options['config'])) {
            $message = 'No data for Zend_Config found';
            throw new Robo47_Application_Resource_Exception($message);
        }
        $config = new Zend_Config($options['config']);

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $config);
        }
        return $config;
    }

    /**
     * Get Config
     *
     * @return Zend_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }
}
