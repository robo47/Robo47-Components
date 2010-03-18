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
 * Robo47_Application_Resource_Service_Akismet
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Service_Akismet
extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Zend_Service_Akismet
     */
    protected $_service = null;
    
    public function init()
    {
        if (!empty($this->_options)) {
            $this->_service = $this->_setupService($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Service_Akismet.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup Akismet
     *
     * @param array $options
     * @return Zend_Service_Akismet
     */
    protected function _setupService($options)
    {
        if (!isset($options['apiKey'])) {
            $message = 'option "apiKey" not found for Service_Akismet';
            throw new Robo47_Application_Resource_Exception($message);
        }
        if (!isset($options['blog'])) {
            $message = 'option "blog" not found for Service_Akismet';
            throw new Robo47_Application_Resource_Exception($message);
        }

        $akismet = new Zend_Service_Akismet(
            $options['apiKey'],
            $options['blog']
        );

        if (isset($options['charset'])) {
            $akismet->setCharset($options['charset']);
        }

        if (isset($options['userAgent'])) {
            $akismet->setUserAgent($options['userAgent']);
        }

        if (isset($options['port'])) {
            // casting needed because of is_int in Zend_Service_Akismet::setPort
            $akismet->setPort((int) $options['port']);
        }

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $akismet);
        }

        return $akismet;
    }

    /**
     * Get Akismet
     *
     * @return Zend_Service_Akismet
     */
    public function getService()
    {
        return $this->_service;
    }
}
