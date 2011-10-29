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
 * Robo47_Application_Resource_AutoloaderMulti
 *
 * Resource for setting up additional autoloaders (for example for ezComponents
 * or HTMLPurifier)
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_AutoloaderMulti
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     *
     * @var array
     */
    protected $_autoloaders = null;

    public function init()
    {
        if (!empty($this->_options)) {
            foreach ($this->_options as $name => $opts) {
                $resource = new Robo47_Application_Resource_Autoloader($opts);
                $resource->init();
                $this->_autoloaders[$name] = $resource->getAutoloader();
            }
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_AutoloaderMulti.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Get Autoloader
     *
     * @param string $name
     * @return Zend_Loader_Autoloader_Interface
     */
    public function getAutoloader($name)
    {
        if (isset($this->_autoloaders[$name])) {
            return $this->_autoloaders[$name];
        } else {
            $message = 'Autoloader \'' . $name . '\' doesn\'t exist';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Get Autoloaders
     *
     * @return array
     */
    public function getAutoloaders()
    {
        return $this->_autoloaders;
    }
}
