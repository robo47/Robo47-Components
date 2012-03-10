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
 * Robo47_View_Helper_Anchor
 *
 * @package     Robo47
 * @subpackage  View
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_View_Helper_Anchor extends Zend_View_Helper_HtmlElement
{

    /**
     * @var Zend_Controller_Router_Interface
     */
    protected $_router = null;

    /**
     *
     * @param Zend_Controller_Router_Interface $router
     */
    public function __construct(Zend_Controller_Router_Interface $router = null)
    {
        $this->setRouter($router);
    }

    /**
     * Sets the used Router
     *
     * @param Zend_Controller_Router_Interface $router
     * @return Robo47_View_Helper_Anchor *Provides Fluent Interface*
     */
    public function setRouter(Zend_Controller_Router_Interface $router = null)
    {
        if (null === $router) {
            $this->_router = Zend_Controller_Front::getInstance()->getRouter();
        } else {
            $this->_router = $router;
        }

        return $this;
    }

    /**
     * Returns the used Router
     *
     * @return Zend_Controller_Router_Interface
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * Generates an anchor for a given Route
     *
     * @param  array    $urlOptions Options passed to the assemble method of the
     *                              Route object.
     * @param  mixed    $name       The name of a Route to use.
     * @param  string   $linkname   The name of the Link
     * @param  array    $params     Additional html-attributes for the anchor
     * @param  bool     $reset      Whether or not to reset the route defaults
     *                              with those provided
     * @param  boolean  $encode
     * @return string   Complete anchor
     */
    public function anchor(array $urlOptions, $name, $linkname,
        $params = array(), $reset = false, $encode = true)
    {
        $router = $this->_router;
        $href = $router->assemble($urlOptions, $name, $reset, $encode);
        $link = '<a href="' . $href . '"';
        $link .= $this->_htmlAttribs($params);
        $link .= '>' . $linkname . '</a>';

        return $link;
    }

}
