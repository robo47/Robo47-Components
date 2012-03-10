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
 * Robo47_Filter_UrlScheme
 *
 * Checks if an url has a scheme like http/https/ftp/ftps
 *
 * @package     Robo47
 * @subpackage  Filter
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Filter_UrlScheme implements Zend_Filter_Interface
{

    /**
     *
     * @var array
     */
    protected $_schemes = array(
        'http',
        'https',
        'ftp',
        'ftps'
    );

    /**
     *
     * @var string
     */
    protected $_defaultScheme = 'http';

    /**
     *
     * @var bool
     */
    protected $_trim = true;

    /**
     *
     * @param string $defaultScheme
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Set Options
     *
     * @param array|Zend_Config $options
     * @return Robo47_Filter_UrlScheme *Provides Fluent Interface*
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'trim':
                    $this->setTrim($value);
                    break;
                case 'defaultScheme':
                    $this->setDefaultScheme($value);
                    break;
                case 'schemes':
                    $this->setSchemes($value);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    /**
     * Set Schemes
     *
     * @param array $schemes
     * @return Robo47_Filter_UrlScheme *Provides Fluent Interface*
     */
    public function setSchemes(array $schemes)
    {
        $this->_schemes = $schemes;

        return $this;
    }

    /**
     * Get Schemes
     *
     * @return array
     */
    public function getSchemes()
    {
        return $this->_schemes;
    }

    /**
     * Set default Scheme
     * @param string $defaultScheme
     * @return Robo47_Filter_UrlScheme *Provides Fluent Interface*
     */
    public function setDefaultScheme($defaultScheme)
    {
        $this->_defaultScheme = $defaultScheme;

        return $this;
    }

    /**
     * Get default Scheme
     *
     * @return string
     */
    public function getDefaultScheme()
    {
        return $this->_defaultScheme;
    }

    /**
     * Set Trim
     *
     * @param bool $flag
     * @return Robo47_Filter_UrlScheme *Provides Fluent Interface*
     */
    public function setTrim($flag = true)
    {
        $this->_trim = (bool) $flag;

        return $this;
    }

    /**
     * Get Trim
     *
     * @return bool
     */
    public function getTrim()
    {
        return $this->_trim;
    }

    /**
     * Filter
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if ($this->getTrim()) {
            $value = trim($value);
        }
        if (empty($value)) {
            return '';
        }
        $schemeFound = false;
        $schemes = $this->getSchemes();
        foreach ($schemes as $scheme) {
            if (0 === strpos($value, $scheme . '://')) {
                $schemeFound = true;
            }
        }
        if (!$schemeFound) {
            $value = $this->getDefaultScheme() .
                '://' . $value;
        }

        return $value;
    }

}
