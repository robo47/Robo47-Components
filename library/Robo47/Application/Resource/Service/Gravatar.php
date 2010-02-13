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
 * Robo47_Application_Resource_Service_Gravatar
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Service_Gravatar
extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Robo47_Service_Gravatar
     */
    protected  $_service = null;

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_service = $this->_setupService($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                       'Robo47_Application_Resource_Service_Gravatar.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Gravatar
     *
     * @param array $options
     * @return Robo47_Service_Gravatar
     */
    protected function _setupService($options)
    {
        $gravatar = new Robo47_Service_Gravatar();

        if (isset($options['rating'])) {
            $gravatar->setRating($options['rating']);
        }

        if (isset($options['size'])) {
            $gravatar->setSize($options['size']);
        }

        if (isset($options['default'])) {
            $gravatar->setDefault($options['default']);
        }

        if (isset($options['cachePrefix'])) {
            $gravatar->setCachePrefix($options['cachePrefix']);
        }

        if (isset($options['useSSL'])) {
            $gravatar->useSSL($options['useSSL']);
        }

        if (isset($options['cache'])) {
            $gravatar->setCache($options['cache']);
        }

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $gravatar);
        }

        return $gravatar;
    }

    /**
     * Get Gravatar
     *
     * @return Robo47_Service_Gravatar
     */
    public function getService()
    {
        return $this->_service;
    }
}
