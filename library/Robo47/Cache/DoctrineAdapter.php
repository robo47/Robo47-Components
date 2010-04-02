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
 * Robo47_Cache_DoctrineAdapter
 *
 * An Adapter for using a Zend_Cache-Instance as Query or Result-Cache
 * for Doctrine.
 * Offers an additional prefix for its entries for usage within prefix-based
 * cache-structure (for example when using one Zend_Cache for a whole
 * application)
 *
 * @uses        Doctrine_Cache_Interface
 * @package     Robo47
 * @subpackage  Cache
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Cache_DoctrineAdapter extends Doctrine_Cache_Driver
{

    /**
     * Instance of the Cache
     *
     * @var Zend_Cache_Core
     */
    protected $_cache = null;
    /**
     * Prefix used with all keys
     *
     * @param string
     */
    protected $_prefix = '';
    /**
     * Tags which are added to each entry
     *
     * @var array
     */
    protected $_tags = array();

    /**
     * @param Zend_Cache_Core|string $cache
     * @param string $prefix
     * @param array $tags
     */
    public function __construct($cache, $prefix = '', $tags = array())
    {
        $this->setCache($cache);
        $this->setPrefix($prefix);
        $this->setTags($tags);
    }

    /**
     * Get Prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Set Prefix
     *
     * @param string $prefix
     * @return Robo47_Cache_DoctrineAdapter *Provides Fluent Interface*
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

    /**
     * Get Cache
     *
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * Set Tags
     *
     * @param array $tags
     * @return Robo47_Cache_DoctrineAdapter *Provides Fluent Interface*
     */
    public function setTags(array $tags = array())
    {
        $this->_tags = $tags;
        return $this;
    }

    /**
     * Get Tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Get Cache From Registry
     *
     * @param string $key
     */
    protected function _cacheFromRegistry($key)
    {
        if (Zend_Registry::isRegistered($key)) {
            return Zend_Registry::get($key);
        } else {
            $message = 'Registry key "' . $key .
                '" for Cache is not registered.';
            throw new Robo47_Cache_Exception($message);
        }
    }

    /**
     * Set Cache
     *
     * @param Zend_Cache_Core|string $cache
     * @return Robo47_Cache_DoctrineAdapter *Provides Fluent Interface*
     */
    public function setCache($cache)
    {
        if (is_string($cache)) {
            $cache = $this->_cacheFromRegistry($cache);
        }
        if (!$cache instanceof Zend_Cache_Core) {
            $message = 'Cache is not instance of Zend_Cache_Core';
            throw new Robo47_Cache_Exception($message);
        }
        $this->_cache = $cache;
        return $this;
    }

    /**
     * Fetch a cache record from this cache driver instance
     *
     * @param string $id cache id
     * @param boolean $testCacheValidity if set to false, the cache validity
     *                                  won't be tested
     * @return mixed  Returns either the cached data or false
     */
    protected function _doFetch($id, $testCacheValidity = true)
    {
        return $this->_cache->load($this->_prefix . $id, $testCacheValidity);
    }

    /**
     * Test if a cache record exists for the passed id
     *
     * @param string $id cache id
     * @return mixed false (a cache is not available) or "last modified"
     *               timestamp (int) of the available cache record
     */
    protected function _doContains($id)
    {
        return $this->_cache->test($this->_prefix . $id);
    }

    /**
     * Save a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::save()
     *
     * @param string $id        cache id
     * @param string $data      data to cache
     * @param int $lifeTime     if != false, set a specific lifetime for
     *                          this cache record (null => infinite lifeTime)
     * @return boolean true if no problem
     */
    protected function _doSave($id, $data, $lifeTime = false)
    {
        return $this->_cache->save(
            $data,
            $this->_prefix . $id,
            $this->getTags(),
            $lifeTime
        );
    }

    /**
     * Remove a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::delete()
     *
     * @param string $id cache id
     * @return boolean true if no problem
     */
    protected function _doDelete($id)
    {
        return $this->_cache->remove($this->_prefix . $id);
    }

    /**
     * Fetch an array of all keys stored in cache
     *
     * @return array Returns the array of cache keys
     */
    protected function _getCacheKeys()
    {
        $ids = $this->_cache->getIds();
        $prefix = $this->getPrefix();
        $length = strlen($prefix);
        foreach($ids as $key => $id) {
            $ids[$key] = substr($id, $length);
        }
        return $ids;
    }
}