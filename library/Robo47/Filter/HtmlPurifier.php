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
 * Robo47_Filter_HtmlPurifier
 *
 * Filter using HtmlPurifier to clean up html code
 *
 * @package     Robo47
 * @subpackage  Filter
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Filter_HtmlPurifier implements Zend_Filter_Interface
{
    /**
     *
     * @var HtmlPurifier
     */
    protected $_purifier = null;

    /**
     *
     * @var HtmlPurifier
     */
    protected static $_defaultPurifier;

    /**
     *
     * @param HtmlPurifier|string $purifier
     */
    public function __construct($purifier = null)
    {
        $this->setPurifier($purifier);
    }

    /**
     *
     * @param HtmlPurifier|string $purifier
     */
    public function setPurifier($purifier = null)
    {
        if (null === $purifier) {
            if (null === self::$_defaultPurifier) {
                $purifier = new HtmlPurifier();
            } else {
                $purifier = self::$_defaultPurifier;
            }
        } elseif (is_string($purifier)) {
            $purifier = $this->_purifierFromRegistry($purifier);
        }
        if (!$purifier instanceof HtmlPurifier) {
            $message = 'purifier is no instance of class HtmlPurifier';
            throw new Robo47_Filter_Exception($message);
        }
        $this->_purifier = $purifier;
        return $this;
    }

    /**
     * Get HtmlPurifier from Registry if found
     *
     * @param string $key
     * @return mixed
     */
    protected function _purifierFromRegistry($key)
    {
        if (Zend_Registry::isRegistered($key)) {
            return Zend_Registry::get($key);
        } else {
            $message = 'Registry key "' . $key .
                       '" for HtmlPurifier is not registered.';
            throw new Robo47_Filter_Exception($message);
        }
    }

    /**
     * Get Purifier
     *
     * @return HtmlPurifier
     */
    public function getPurifier()
    {
        return $this->_purifier;
    }

    /**
     * Set default Purifier
     *
     * @param HtmlPurifier $purifier
     */
    public static function setDefaultPurifier(HtmlPurifier $purifier = null)
    {
        self::$_defaultPurifier = $purifier;
    }

    /**
     * Get default Purifier
     *
     * @return HtmlPurifier
     */
    public static function getDefaultPurifier()
    {
        return self::$_defaultPurifier;
    }

    /**
     * Filter
     *
     * @see Zend_Filter_Interface::filter
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return $this->_purifier->purify($value);
    }
}
