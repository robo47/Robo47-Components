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
 * Robo47_Application_Resource_Cache
 *
 * Resource for setting up a Zend_Cache_Core instance
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @deprecated  Mostly deprecated because of Zend_Cache_Manager
 */
class Robo47_Application_Resource_Cache
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     *
     * @var Zend_Cache
     */
    protected $_cache = array();

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_cache = $this->_setupCache($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Cache.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Cache
     *
     * @param array $config
     */
    public function _setupCache($config)
    {
        if (!isset($config['frontendName'])) {
            $message = 'Cache config doesn\'t contain frontendName';
            throw new Robo47_Application_Resource_Exception($message);
        }

        if (!isset($config['backendName'])) {
            $message = 'Cache config doesn\'t contain backendName';
            throw new Robo47_Application_Resource_Exception($message);
        }

        if (!isset($config['frontendOptions'])) {
            $config['frontendOptions'] = array();
        }

        if (!isset($config['backendOptions'])) {
            $config['backendOptions'] = array();
        }

        if (!isset($config['customFrontendNaming'])) {
            $config['customFrontendNaming'] = false;
        }

        if (!isset($config['customBackendNaming'])) {
            $config['customBackendNaming'] = false;
        }

        if (!isset($config['autoload'])) {
            $config['autoload'] = false;
        }

        $cache = Zend_Cache::factory(
            $config['frontendName'], $config['backendName'],
            $config['frontendOptions'], $config['backendOptions'],
            $config['customFrontendNaming'], $config['customBackendNaming'],
            $config['autoload']
        );

        if (isset($config['registryKey'])) {
            Zend_Registry::set($config['registryKey'], $cache);
        }

        return $cache;
    }

    /**
     * Get Cache
     *
     * @return Zend_Cache
     */
    public function getCache()
    {
        return $this->_cache;
    }

}
