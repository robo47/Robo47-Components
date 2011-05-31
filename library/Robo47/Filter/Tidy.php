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
 * Robo47_Filter_Tidy
 *
 * Filter using Tidy to clean up html code
 *
 * @package     Robo47
 * @subpackage  Filter
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Filter_Tidy implements Zend_Filter_Interface
{

    /**
     * The Tidy instance
     * @var Tidy
     */
    protected $_tidy = null;
    /**
     * The array with configuration for tidy
     *
     * @var array
     */
    protected $_config = array();
    /**
     * The used encoding
     * @var string
     */
    protected $_encoding = 'utf8';
    /**
     * Default Tidy
     *
     * @var Tidy
     */
    protected static $_defaultTidy;
    /**
     * Array with allowed encodings, key is possible encodings, value is the
     * allowed value which gets set
     *
     * @var array
     */
    protected $encodings = array(
        'utf-8' => 'utf8',
        'utf8' => 'utf8',
        'ascii' => 'ascii',
        'latin0' => 'latin0',
        'latin1' => 'latin1',
        'raw' => 'raw',
        'utf8' => 'utf8',
        'iso2022' => 'iso2022',
        'mac' => 'mac',
        'win1252' => 'win1252',
        'ibm858' => 'ibm858',
        'utf16' => 'utf16',
        'utf16le' => 'utf16le',
        'utf16be' => 'utf16be',
        'big5' => 'big5',
        'shiftjis' => 'shiftjis'
    );

    /**
     *
     * @throws Robo47_Filter_Exception
     * @param Tidy|string        $tidy
     * @param array|Zend_Config  $config
     * @param string             $encoding
     */
    public function __construct($tidy = null, $config = null, $encoding = 'utf8')
    {
        $this->setTidy($tidy);
        $this->setConfig($config);
        $this->setEncoding($encoding);
    }

    /**
     * Set Config
     *
     * @param array|Zend_Config
     * @return Robo47_Filter_Tidy *Provides Fluent Interface*
     */
    public function setConfig($config = null)
    {
        if (null !== $config) {
            if ($config instanceof Zend_Config) {
                $config = $config->toArray();
            }
            $this->_config = $config;
        }
        return $this;
    }

    /**
     * Get Config
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Set Encoding
     *
     * @throws Robo47_Filter_Exception
     * @param string $encoding
     * @return Robo47_Filter_Tidy *Provides Fluent Interface*
     */
    public function setEncoding($encoding)
    {
        $encoding = strtolower($encoding);
        if (isset($this->encodings[$encoding])) {
            $this->_encoding = $this->encodings[$encoding];
        } else {
            $message = 'Unknown encoding: ' . $encoding;
            throw new Robo47_Filter_Exception($message);
        }
        return $this;
    }

    /**
     * Set Tidy
     *
     * @throws Robo47_Filter_Exception
     * @param Tidy|string $tidy
     */
    public function setTidy($tidy = null)
    {
        if (null === $tidy) {
            if (null === self::$_defaultTidy) {
                $tidy = new Tidy();
            } else {
                $tidy = self::$_defaultTidy;
            }
        } elseif (is_string($tidy)) {
            $tidy = $this->_tidyFromRegistry($tidy);
        }

        if (!$tidy instanceof Tidy) {
            $message = 'Tidy is no instance of class Tidy';
            throw new Robo47_Filter_Exception($message);
        }
        $this->_tidy = $tidy;

        return $this;
    }

    /**
     * Get tidy from Registry if found
     *
     * @throws Robo47_Filter_Exception
     * @param string $key
     * @return mixed
     */
    protected function _tidyFromRegistry($key)
    {
        if (Zend_Registry::isRegistered($key)) {
            return Zend_Registry::get($key);
        } else {
            $message = 'Registry key "' . $key .
                '" for Tidy is not registered.';
            throw new Robo47_Filter_Exception($message);
        }
    }

    /**
     * Get Tidy
     *
     * @return Tidy
     */
    public function getTidy()
    {
        return $this->_tidy;
    }

    /**
     * Set default Tidy
     *
     * @param Tidy $tidy
     */
    public static function setDefaultTidy(Tidy $tidy = null)
    {
        self::$_defaultTidy = $tidy;
    }

    /**
     * Get Encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Get default Tidy
     *
     * @return Tidy
     */
    public static function getDefaultTidy()
    {
        return self::$_defaultTidy;
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
        $this->_tidy->parseString(
            $value, $this->getConfig(), $this->getEncoding()
        );
        $this->_tidy->cleanRepair();
        return (string) $this->_tidy;
    }

}
