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
 * Robo47_Application_Resource_Autoloader
 *
 * Resource for setting up an additional autoloaders (for example for
 * ezComponents or HTMLPurifier)
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Autoloader
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Zend_Loader_Autoloader_Interface
     */
    protected $_autoloader = null;
    
    public function init()
    {
        if (!empty($this->_options)) {
            $this->_autoloader = $this->_setupAutoloader($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Autoloader.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Autoloader
     *
     * @see Zend_Loader_Autoloader
     * @param string $name
     * @param array $config
     */
    public function _setupAutoloader($options)
    {
        if (!isset($options['classname'])) {
            $message = 'Autoloader config doesn\'t contain classname';
            throw new Robo47_Application_Resource_Exception($message);
        }

        if (!isset($options['prefix'])) {
            $message = 'Autoloader config doesn\'t contain prefix';
            throw new Robo47_Application_Resource_Exception($message);
        }

        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $classname = $options['classname'];
        $prefix = $options['prefix'];
        $autoLoaderInstance = new $classname();
        $autoLoader->pushAutoloader($autoLoaderInstance, $prefix);
        return $autoLoaderInstance;
    }

    /**
     * Get Autoloader
     *
     * @return Zend_Loader_Autoloader_Interface
     */
    public function getAutoloader()
    {
        return $this->_autoloader;
    }
}
