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
 * Robo47_Application_Resource_CacheMulti
 *
 * Resource for setting up multiple Zend_Cache_Core instances
 *
 * @package     Robo47
 * @subpackage  Application
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @deprecated  Mostly deprecated because of Zend_Cache_Manager
 */
class Robo47_Application_Resource_CacheMulti extends Zend_Application_Resource_ResourceAbstract
{

    /**
     *
     * @var array|Zend_Cache[]
     */
    protected $_caches = array();

    public function init()
    {
        if (!empty($this->_options)) {
            foreach ($this->_options as $name => $options) {
                $resource = new Robo47_Application_Resource_Cache($options);
                $resource->init();
                $this->_caches[$name] = $resource->getCache();
            }
        } else {
            $message = 'Empty options in resource ' .
                'Robo47_Application_Resource_CacheMulti.';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Get Cache
     *
     * @param string $name
     * @return Zend_Cache_Core
     */
    public function getCache($name)
    {
        if (isset($this->_caches[$name])) {
            return $this->_caches[$name];
        } else {
            $message = 'Cache \'' . $name . '\' doesn\'t exist';
            throw new Robo47_Application_Resource_Exception($message);
        }
    }

    /**
     * Get Caches
     *
     * @return array
     */
    public function getCaches()
    {
        return $this->_caches;
    }

}
