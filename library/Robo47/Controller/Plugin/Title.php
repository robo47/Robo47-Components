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
 * Robo47_Controller_Plugin_Title
 *
 * Plugin which can append or prepend strings to the title
 * after the dispatch-process (postDispatch())
 *
 * @package     Robo47
 * @subpackage  Controller
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @uses        Zend_View_Helper_HeadTitle
 */
class Robo47_Controller_Plugin_Title extends Zend_Controller_Plugin_Abstract
{
    /**
     * String to append to the title
     *
     * @var string
     */
    protected $_append  = '';

    /**
     * String to prepend to the title
     *
     * @var string
     */
    protected $_prepend = '';

    /**
     * The used View
     * 
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     *
     * @param Zend_View_Interface $view
     * @param string $append
     * @param string $prepend
     */
    public function __construct(Zend_View_Interface $view,
                                $append  = '',
                                $prepend = '')
    {
        $this->setAppend($append);
        $this->setPrepend($prepend);
        $this->setView($view);
    }

    /**
     * Get View
     *
     * @return Zend_View_Interface
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * Set View
     *
     * @param Zend_View_Interface $view
     * @return Robo47_Controller_Plugin_Title *Provides Fluent Interface*
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Set Append
     *
     * @param string $value
     * @return Robo47_Controller_Plugin_Title *Provides Fluent Interface*
     */
    public function setAppend($value = null)
    {
        $this->_append = $value;
        return $this;
    }

    /**
     * Set Prepend
     *
     * @param string $value
     * @return Robo47_Controller_Plugin_Title *Provides Fluent Interface*
     */
    public function setPrepend($value = null)
    {
        $this->_prepend = $value;
        return $this;
    }

    /**
     * Get Prepend
     *
     * @return string
     */
    public function getPrepend()
    {
        return $this->_prepend;
    }

    /**
     * Get Append
     *
     * @return string
     */
    public function getAppend()
    {
        return $this->_append;
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $headTitle = $this->_view->headTitle();
        $headTitle->headTitle($this->_prepend, 'PREPEND');
        $headTitle->headTitle($this->_append, 'APPEND');
    }
}
