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
 * Robo47_Application_Resource_Service_Bitly
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_Service_Bitly extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Robo47_Service_Bitly
     */
    protected $_service = null;

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_service = $this->_setupService($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_Service_Bitly.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * @param array $options
     * @return Robo47_Service_Bitly
     */
    protected function _setupService($options)
    {
        if (!isset($options['login'])) {
            $message = 'No login provided';
            throw new Robo47_Application_Resource_Exception($message);
        }

        if (!isset($options['apiKey'])) {
            $message = 'No apiKey provided';
            throw new Robo47_Application_Resource_Exception($message);
        }

        $bitly = new Robo47_Service_Bitly(
                $options['login'],
                $options['apiKey']
        );

        if (isset($options['format'])) {
            $bitly->setFormat($options['format']);
        }

        if (isset($options['resultFormat'])) {
            $bitly->setResultFormat($options['resultFormat']);
        }

        if (isset($options['callback'])) {
            $bitly->setCallback($options['callback']);
        }

        if (isset($options['version'])) {
            $bitly->setVersion($options['version']);
        }

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $bitly);
        }

        return $bitly;
    }

    /**
     *
     * @return Robo47_Service_Bitly
     */
    public function getService()
    {
        return $this->_service;
    }

}
