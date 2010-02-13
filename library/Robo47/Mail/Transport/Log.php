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
 * Robo47_Mail_Transport_Log
 *
 * @package     Robo47
 * @subpackage  Mail
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Mail_Transport_Log extends Zend_Mail_Transport_Abstract
{
    /**
     *
     * @param Robo47_Mail_Transport_Log_Formatter_Interface|string $formatter
     * @param Zend_Log $log
     * @param integer $logPriority
     * @param string $logCategory
     */
    public function __construct($formatter, Zend_Log $log,
                                $logPriority = Zend_Log::INFO,
                                $logCategory = 'mail')
    {
        $this->setFormatter($formatter);
        $this->setLog($log);
        $this->setLogPriority($logPriority);
        $this->setLogCategory($logCategory);
    }

    /**
     * Set Log
     *
     * @param Zend_Log $log
     * @return Robo47_Mail_Transport_Log *Provides Fluent Interface*
     */
    public function setLog(Zend_Log $log = null)
    {
        $this->_log = $log;
        return $this;
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

    /**
     * Set Log Category
     *
     * @param string $category
     * @return Robo47_Mail_Transport_Log *Provides Fluent Interface*
     */
    public function setLogCategory($logCategory)
    {
        $this->_logCategory = $logCategory;
        return $this;
    }

    /**
     * Get Log Category
     *
     * @return string
     */
    public function getLogCategory()
    {
        return $this->_logCategory;
    }

    /**
     * Set Log Category
     *
     * @param integer $logPriority
     * @return Robo47_Mail_Transport_Log *Provides Fluent Interface*
     */
    public function setLogPriority($logPriority)
    {
        $this->_logPriority = (int)$logPriority;
        return $this;
    }

    /**
     * Get Log Category
     *
     * @return integer
     */
    public function getLogPriority()
    {
        return $this->_logPriority;
    }

    /**
     * Set Formatter
     * 
     * @param Robo47_Mail_Transport_Log_Formatter_Interface|string $formatter
     * @return Robo47_Mail_Transport_Log *Provides Fluent Interface*
     */
    public function setFormatter($formatter)
    {
        if(is_string($formatter)) {
            $formatter = new $formatter;
        }
        if (!$formatter instanceof Robo47_Mail_Transport_Log_Formatter_Interface) {
            $message = 'formatter is not instance of ' .
                       'Robo47_Mail_Transport_Log_Formatter_Interface';
            throw new Robo47_Mail_Transport_Exception($message);
        }
        $this->_formatter = $formatter;
        return $this;
    }

    /**
     * Get Formatter
     *
     * @return Robo47_Mail_Transport_Log_Formatter_Interface
     */
    public function getFormatter()
    {
        return $this->_formatter;
    }

    /**
     * Logs the Mail
     * 
     * @param Zend_Mail $mail
     */
    public function send(Zend_Mail $mail)
    {
        $message = $this->getFormatter()->format($mail);
        $this->getLog()->log(
            $message,
            $this->getLogPriority(),
            array('category' => $this->getLogCategory())
        );
    }

    /**
     * Not Really needed ... empty just to fullfil the requirements of the
     * parent class
     */
    protected function _sendMail()
    {
    }
}