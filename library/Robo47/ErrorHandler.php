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
 * Robo47_ErrorHandler
 *
 * @package     Robo47
 * @subpackage  ErrorHandler
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_ErrorHandler
{
    /**
     *
     * @var callback
     */
    protected $_oldErrorHandler = null;

    /**
     *
     * @var bool
     */
    protected $_isErrorHandler = false;

    /**
     * Log
     *
     * @var Zend_Log
     */
    protected $_log = null;

    /**
     * Log Category
     *
     * @var string
     */
    protected $_logCategory = 'errorHandler';

    /**
     * Mapping for Priorities to logging
     *
     * @var array
     */
    protected $_errorPriorityMapping = array(
        E_ERROR             => 3, // Zend_Log::ERR
        E_WARNING           => 4, // Zend_Log::WARN
        E_NOTICE            => 5, // Zend_Log::NOTICE
        E_USER_ERROR        => 3, // Zend_Log::ERR
        E_USER_WARNING      => 4, // Zend_Log::WARN
        E_USER_NOTICE       => 5, // Zend_Log::NOTICE
        E_CORE_ERROR        => 3, // Zend_Log::ERR
        E_CORE_WARNING      => 4, // Zend_Log::WARN
        E_STRICT            => 3, // Zend_Log::ERR
        E_RECOVERABLE_ERROR => 3, // Zend_Log::ERR
        'unknown'           => 0, // Zend_Log::EMERG

    );

    /**
     * @param Zend_Log $log
     * @param string $category
     */
    public function __construct($log = null, $category = 'errorHandler')
    {
        $this->setLog($log);
        $this->setLogCategory($category);
    }

    /**
     * Set Log
     *
     * @param Zend_Log $log
     * @return Robo47_ErrorHandler *Provides Fluent Interface*
     */
    public function setLog(Zend_Log $log = null)
    {
        $this->_log = $log;
        return $this;
    }

    /**
     * Get Log
     *
     * @return Zend_Log
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * Set Log Category
     *
     * @param string $category
     * @return Robo47_ErrorHandler *Provides Fluent Interface*
     */
    public function setLogCategory($category)
    {
        $this->_logCategory = $category;
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
     * Get error priority Mapping
     *
     * @param integer $error
     * @return integer
     */
    protected function _getErrorsPriority($error)
    {
        if (isset($this->_errorPriorityMapping[$error])) {
            return $this->_errorPriorityMapping[$error];
        } else {
            return $this->_errorPriorityMapping['unknown'];
        }
    }

    /**
     * Set error priority Mapping
     *
     * @param array $errorPriorityMapping
     * @return Robo47_ErrorHandler *Provides Fluent Interface*
     */
    public function setErrorPriorityMapping(array $errorPriorityMapping)
    {
        $this->_errorPriorityMapping = $errorPriorityMapping;
        if (!isset($this->_errorPriorityMapping['unknown'])) {
            $this->_errorPriorityMapping['unknown'] = 0;
        }
        return $this;
    }

    /**
     * Get error priority Mapping
     *
     * @return array
     */
    public function getErrorPriorityMapping()
    {
        return $this->_errorPriorityMapping;
    }

    /**
     * Log error
     *
     * @param integer $errno
     * @param string  $errstr
     * @param string  $errfile
     * @param integer $errline
     */
    protected function _logError($errno, $errstr, $errfile, $errline)
    {
        if (null !== $this->getLog()) {
            $priority = $this->_getErrorsPriority($errno);
            $message = $errstr . ' in ' . $errfile . ':' . $errline;
            $this->getLog()->log(
                $message,
                $priority,
                array('category' => $this->getLogCategory())
            );
        }
        $displayErrors = ini_get('display_errors');
        ini_set('display_errors', 'Off');
        if (ini_get('log_errors')) {
            $path = ini_get('error_log');
            if (is_writeable(dirname($path))) {
                $message = sprintf(
                    "PHP %s:  %s in %s on line %d",
                    $errno,
                    $errstr,
                    $errfile,
                    $errline
                );
                error_log($message, 0);
            }
        }
        ini_set('display_errors', $displayErrors);
    }

    /**
     * Register ErrorHandler
     *
     * @return Robo47_ErrorHandler *Provides Fluent Interface*
     */
    public function registerAsErrorHandler()
    {
        $handler = array($this, 'handleError');
        $errorLevel = E_ALL | E_STRICT;
        $this->_oldErrorHandler = set_error_handler($handler, $errorLevel);
        $this->_isErrorHandler = true;
        return $this;
    }

    /**
     * Get old ErrorHandler
     *
     * @return callback
     */
    public function getOldErrorHandler()
    {
        return $this->_oldErrorHandler;
    }

    /**
     * Unregister ErrorHandler
     *
     * @return Robo47_ErrorHandler *Provides Fluent Interface*
     */
    public function unregisterAsErrorHandler()
    {
        if ($this->_isErrorHandler) {
            set_error_handler($this->_oldErrorHandler);
            $this->_isErrorHandler = false;
        }
        return $this;
    }

    /**
     * Handle Error
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // suppress errors using @
        if (error_reporting() == 0) {
            return;
        }
        $this->_logError($errno, $errstr, $errfile, $errline);
        throw new Robo47_ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
