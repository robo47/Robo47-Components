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
 * Robo47_Application_Resource_ErrorHandler
 *
 * Resource for setting up an ErrorHandler
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Application_Resource_ErrorHandler
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     *
     * @var Robo47_ErrorHandler
     */
    protected $_errorHandler = null;

    public function init()
    {
        if (!empty($this->_options)) {
            $this->_errorHandler = $this->_setupErrorHandler($this->_options);
        } else {
            $message = 'Empty options in resource ' .
                       'Robo47_Application_Resource_ErrorHandler.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Setup errorHandler
     *
     * @param array $options
     */
    protected function _setupErrorHandler($options)
    {
        $errorHandler = new Robo47_ErrorHandler();

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $errorHandler);
        }
        if (isset($options['setLogFromRegistryKey'])) {
            $log = Zend_Registry::get($options['setLogFromRegistryKey']);
            $errorHandler->setLog($log);
        }
        if (isset($options['logCategory'])) {
            $errorHandler->setLogCategory($options['logCategory']);
        }
        if (isset($options['errorPriorityMapping'])) {
            $errorPriorityMapping = $options['errorPriorityMapping'];
            $errorHandler->setErrorPriorityMapping($errorPriorityMapping);
        }
        if (isset($options['registerAsErrorHandler'])) {
            $errorHandler->registerAsErrorHandler();
        }
        return $errorHandler;
    }

    /**
     * Get ErrorHandler
     *
     * @return Robo47_ErrorHandler
     */
    public function getErrorHandler()
    {
        return $this->_errorHandler;
    }
}
