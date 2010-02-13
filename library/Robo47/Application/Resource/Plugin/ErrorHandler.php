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
 * Robo47_Application_Resource_Plugin_ErrorHandler
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
class Robo47_Application_Resource_Plugin_ErrorHandler
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_Controller_Plugin_ErrorHandler
     */
    protected $_errorHandler = null;

    public function init()
    {
        $this->_errorHandler = $this->_setupErrorHandler($this->_options);
    }

    public function _setupErrorHandler($options)
    {
        $errorHandler = new Zend_Controller_Plugin_ErrorHandler($options);

        if (isset($options['module'])) {
            $errorHandler->setErrorHandlerModule($options['module']);
        }

        if (isset($options['controller'])) {
            $errorHandler->setErrorHandlerController($options['controller']);
        }

        if (isset($options['action'])) {
            $errorHandler->setErrorHandlerAction($options['action']);
        }
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin($errorHandler);

        return $errorHandler;
    }

    /**
     * @return Zend_Controller_Plugin_ErrorHandler
     */
    public function getErrorHandler()
    {
        return $this->_errorHandler;
    }
}