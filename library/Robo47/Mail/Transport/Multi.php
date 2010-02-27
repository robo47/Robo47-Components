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
 * Robo47_Mail_Transport_Multi
 *
 * @package     Robo47
 * @subpackage  Mail
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Mail_Transport_Multi extends Zend_Mail_Transport_Abstract
{

    /**
     * Array with all Transports
     *
     * @var array
     */
    protected $_transports = array();

    /**
     * @param array|Zend_Mail_Transport_Abstract $transports
     */
    public function __construct($transports = array())
    {
        $this->setTransports($transports);
    }

    /**
     * Set Transports
     *
     * @param array|Zend_Mail_Transport_Abstract $transports
     * @return Robo47_Mail_Transport_Multi *Provides Fluent Interface*
     */
    public function setTransports($transports)
    {
        if (is_array($transports)) {
            foreach ($transports as $transport) {
                $this->addTransport($transport);
            }
        } else {
            $this->addTransport($transports);
        }
        return $this;
    }

    /**
     * Add Transport
     *
     * @param Zend_Mail_Transport_Abstract $transport
     * @return Robo47_Mail_Transport_Multi *Provides Fluent Interface*
     */
    public function addTransport(Zend_Mail_Transport_Abstract $transport)
    {
        $this->_transports[] = $transport;
        return $this;
    }

    /**
     * Get Transports
     *
     * @return array
     */
    public function getTransports()
    {
        return $this->_transports;
    }

    /**
     * Remove Transport
     *
     * @param string|mixed $class
     * @return Robo47_Mail_Transport_Multi *Provides Fluent Interface*
     */
    public function removeTransport($class)
    {
        foreach ($this->_transports as $key => $transport) {
            if (is_string($class)) {
                if ($transport instanceof $class) {
                    unset($this->_transports[$key]);
                }
            } elseif (is_object($class)) {
                if ($class === $transport) {
                    unset($this->_transports[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * Send a mail using this transport
     *
     * @param  Zend_Mail $mail
     * @access public
     * @return void
     * @throws Zend_Mail_Transport_Exception if mail is empty
     */
    public function send(Zend_Mail $mail)
    {
        foreach ($this->_transports as $transport) {
            /* @var $transport Zend_Mail_Transport_Abstract */
            $transport->send($mail);
        }
    }

    /**
     * Not Really needed ... empty just to fullfil the requirements of the
     * parent class
     */
    protected function _sendMail()
    {
        
    }
}
