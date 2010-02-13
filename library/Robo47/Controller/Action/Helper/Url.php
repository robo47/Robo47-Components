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
 * Robo47_Controller_Action_Helper_Url
 *
 *
 * @package     Robo47
 * @subpackage  Controller
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Controller_Action_Helper_Url
extends Zend_Controller_Action_Helper_Url
{

    /**
     * @var Zend_Controller_Router_Interface
     */
    protected $_router = null;

    /**
     * @param Zend_Controller_Router_Interface $router
     */
    public function __construct(Zend_Controller_Router_Interface $router = null)
    {
        $this->setRouter($router);
    }

    /**
     * Set Router
     *
     * @param Zend_Controller_Router_Interface $router
     * @return Robo47_Controller_Action_Helper_Url *Provides Fluent Interface*
     */
    public function setRouter(Zend_Controller_Router_Interface $router = null)
    {
        if (null === $router) {
            $router = Zend_Controller_Front::getInstance()->getRouter();
        }
        $this->_router = $router;
        return $this;
    }

    /**
     * Get Router
     *
     * @return Zend_Controller_Router_Interface
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * Assembles a URL based on a given route
     *
     * This method will typically be used for more complex operations, as it
     * ties into the route objects registered with the router.
     *
     * @param  array   $urlOptions
     * @param  mixed   $name
     * @param  boolean $reset
     * @param  boolean $encode
     * @return string Url for the link href attribute.
     */
    public function url($urlOptions = array(), $name = null, $reset = false,
                        $encode = true)
    {
        return $this->_router->assemble($urlOptions, $name, $reset, $encode);
    }
}
