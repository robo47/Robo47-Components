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
 * Robo47_View_Helper_Cdn
 *
 * @package     Robo47
 * @subpackage  View
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_View_Helper_Cdn extends Zend_View_Helper_Abstract
{

    /**
     * @var string
     */
    protected $_cdn = '';

    /**
     * Inits object
     *
     * @param string $cdn
     */
    public function __construct($cdn = '')
    {
        $this->setCdn($cdn);
    }

    /**
     * Returns itself or if path is set CDN-url + path
     *
     * @return Robo47_View_Helper_Cdn|string
     */
    public function Cdn($path = null)
    {
        if (null === $path) {
            return $this;
        } else {
            return $this->_cdn . $path;
        }
    }

    /**
     *
     * @param string $cdn
     * @return Robo47_View_Helper_Cdn *provides fluent interface*
     */
    public function setCdn($cdn)
    {
        $this->_cdn = (string) $cdn;
        return $this;
    }

    /**
     * Returns the cdn-string
     *
     * @return string
     */
    public function getCdn()
    {
        return $this->_cdn;
    }

    /**
     * Returns the cdn-string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->_cdn;
    }
}