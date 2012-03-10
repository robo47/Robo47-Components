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
 * Robo47_Service_Gravatar
 *
 * @package     Robo47
 * @subpackage  Service
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @todo        isValidCaching
 */
class Robo47_Service_Gravatar extends Zend_Service_Abstract
{
    /**
     * GRAVATAR API URL
     */

    const API_URL = 'http://www.gravatar.com/avatar/';

    /**
     * GRAVATAR API SSL URL
     */
    const API_SSL_URL = 'https://secure.gravatar.com/avatar/';

    /**
     * GRAVATAR Rating G - General Audiences
     */
    const RATING_G = 'g';

    /**
     * GRAVATAR Rating PG - Parental Guidance Suggested
     */
    const RATING_PG = 'pg';

    /**
     * GRAVATAR Rating R - Restricted
     */
    const RATING_R = 'r';

    /**
     * GRAVATAR Rating X
     */
    const RATING_X = 'x';

    /**
     * GRAVATAR Default Identicon
     * @link http://en.wikipedia.org/wiki/Identicon
     */
    const DEFAULT_IDENTICON = 'identicon';

    /**
     * GRAVATAR Default Monsterid
     * @link http://www.splitbrain.org/projects/monsterid
     */
    const DEFAULT_MONSTERID = 'monsterid';

    /**
     * GRAVATAR Default Wavatar
     * @link http://www.shamusyoung.com/twentysidedtale/?p=1462
     */
    const DEFAULT_WAVATAR = 'wavatar';

    /**
     * GRAVATAR Default 404
     */
    const DEFAULT_404 = '404';

    /**
     * Used rating
     *
     * Can be g, pg, r, x
     *
     * @see Robo47_Service_Gravatar::RATING_G
     * @see Robo47_Service_Gravatar::RATING_PG
     * @see Robo47_Service_Gravatar::RATING_R
     * @see Robo47_Service_Gravatar::RATING_X
     * @var string
     */
    protected $_rating = 'g';

    /**
     * Used size
     *
     * @var integer
     */
    protected $_size = 80;

    /**
     * Default image
     *
     * Can be identicon, monsterid, wavatar, 404 or a self specified url
     * if no gravatar is found.
     *
     * @see Robo47_Service_Gravatar::DEFAULT_IDENTICON
     * @see Robo47_Service_Gravatar::DEFAULT_MONSTERID
     * @see Robo47_Service_Gravatar::DEFAULT_WAVATAR
     * @see Robo47_Service_Gravatar::DEFAULT_404
     *
     * @var string
     */
    protected $_default = '';

    /**
     * Used Cache
     *
     * @var Zend_Cache_Core
     */
    protected $_cache = null;

    /**
     * Prefix used for the cache-ids.
     *
     * @var string
     */
    protected $_cachePrefix = 'gravatar_';

    /**
     * Whether to use api via SSL or not
     *
     * @var bool
     */
    protected $_useSSL = false;

    /**
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Set options
     *
     * @param array $options
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setOptions(array $options = array())
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'rating':
                    $this->setRating($value);
                    break;
                case 'default':
                    $this->setDefault($value);
                    break;
                case 'cache':
                    $this->setCache($value);
                    break;
                case 'cachePrefix':
                    $this->setCachePrefix($value);
                    break;
                case 'size':
                    $this->setSize($value);
                    break;
                case 'useSSL':
                    $this->useSSL($value);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    /**
     * Whether to use SSL or not
     *
     * @param bool $flag
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function useSSL($flag)
    {
        $this->_useSSL = (bool) $flag;

        return $this;
    }

    /**
     * If SSL is used
     *
     * @return bool
     */
    public function usesSSL()
    {
        return $this->_useSSL;
    }

    /**
     * Set the used Cache
     *
     * @param Zend_Cache_Core|string|null $cache
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setCache($cache = null)
    {
        if (is_string($cache)) {
            $cache = $this->_cacheFromRegistry($cache);
        }
        if (!$cache instanceof Zend_Cache_Core &&
            null !== $cache) {
            $message = 'cache is not instance of Zend_Cache_Core';
            throw new Robo47_Service_Gravatar_Exception($message);
        }
        $this->_cache = $cache;

        return $this;
    }

    /**
     * Get tidy from Registry if found
     *
     * @throws Robo47_Filter_Exception
     * @param string $key
     * @return mixed
     */
    protected function _cacheFromRegistry($key)
    {
        if (Zend_Registry::isRegistered($key)) {
            return Zend_Registry::get($key);
        } else {
            $message = 'Registry key "' . $key .
                '" for Cache is not registered.';
            throw new Robo47_Service_Gravatar_Exception($message);
        }
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
     * Set CachePrefix
     *
     * @param string $cachePrefix
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setCachePrefix($cachePrefix)
    {
        $this->_cachePrefix = $cachePrefix;

        return $this;
    }

    /**
     * Get CachePrefix
     *
     * @return string
     */
    public function getCachePrefix()
    {
        return $this->_cachePrefix;
    }

    /**
     * Set Rating
     *
     * @param string $rating
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setRating($rating)
    {
        $rating = strtolower($rating);
        switch ($rating) {
            case self::RATING_G:
            case self::RATING_PG:
            case self::RATING_R:
            case self::RATING_X:
                $this->_rating = $rating;
                break;
            default:
                $message = 'Invalid rating: ' . $rating;
                throw new Robo47_Service_Gravatar_Exception($message);
        }

        return $this;
    }

    /**
     * Get Rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->_rating;
    }

    /**
     * Set Size
     *
     * @param integer $size
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setSize($size)
    {
        $size = (int) $size;
        if ($size < 1) {
            $message = 'size is smaller than 1';
            throw new Robo47_Service_Gravatar_Exception($message);
        }
        if ($size > 512) {
            $message = 'size is greater than 512';
            throw new Robo47_Service_Gravatar_Exception($message);
        }
        $this->_size = $size;

        return $this;
    }

    /**
     * Returns the size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Sets the default
     *
     * Default can be an url of a image which is used if no gravatar found or
     * one of the Robo47_Service_Gravatar::DEFAULT_*-Constants for alternate
     * generated images (identicon, monsterid, wavatar) or a 404-header as
     * answeder
     *
     * @param string    $default
     * @return Robo47_Service_Gravatar *Provides Fluent Interface*
     */
    public function setDefault($default)
    {
        $this->_default = $default;

        return $this;
    }

    /**
     * Returns the default
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->_default;
    }

    /**
     * Filters the size
     *
     * With Fallback for null to internal value
     *
     * @param integer|null  $size
     * @return integer
     */
    protected function _filterSize($size)
    {
        if (null === $size) {
            return $this->getSize();
        } else {
            return (int) $size;
        }
    }

    /**
     * Filters the rating
     *
     * With Fallback for null to internal value
     *
     * @param string|null   $rating
     * @return string
     */
    protected function _filterRating($rating)
    {
        $rating = strtolower($rating);
        switch ($rating) {
            case self::RATING_G:
            case self::RATING_PG:
            case self::RATING_R:
            case self::RATING_X:
                return $rating;
                break;
            default:
                return $this->getRating();
        }
    }

    /**
     * Filters the default
     *
     * With Fallback for null to internal value
     *
     * @param string|null   $default
     * @return string
     */
    protected function _filterDefault($default)
    {
        if (null === $default) {
            return $this->getDefault();
        } else {
            return (string) $default;
        }
    }

    /**
     * Filters ssl
     *
     * With Fallback for null to internal value
     *
     * @param bool|null     $ssl
     * @return bool
     */
    protected function _filterSsl($ssl)
    {
        if (null === $ssl) {
            return $this->usesSSL();
        } else {
            return (bool) $ssl;
        }
    }

    /**
     * Filters cachePrefix
     *
     * With Fallback for null to internal value
     *
     * @param string|null     $cachePrefix
     * @return string
     */
    protected function _filterCachePrefix($cachePrefix)
    {
        if (null === $cachePrefix) {
            return $this->getCachePrefix();
        } else {
            return (string) $cachePrefix;
        }
    }

    /**
     * Get Uri
     *
     * @param string    $email
     * @param integer   $size
     * @param string    $rating
     * @param string    $default
     * @param bool      $ssl
     * @param string    $separator
     * @return string
     */
    public function getUri($email, $size = null, $rating = null,
        $default = null, $ssl = null, $separator = '&amp;')
    {
        $size = $this->_filterSize($size);
        $rating = $this->_filterRating($rating);
        $default = $this->_filterDefault($default);
        $ssl = $this->_filterSsl($ssl);

        return $this->_getUri(
                $email, $size, $rating, $default, $ssl, $separator
        );
    }

    /**
     *
     * @param string    $email
     * @param integer   $size
     * @param string    $rating
     * @param string    $default
     * @param bool      $ssl
     * @param string    $separator
     * @return string
     */
    protected function _getUri($email, $size, $rating, $default, $ssl,
        $separator)
    {
        if ($ssl) {
            $url = Robo47_Service_Gravatar::API_SSL_URL;
        } else {
            $url = Robo47_Service_Gravatar::API_URL;
        }

        $url .= $this->getGravatarHash($email);

        $params = array(
            's' => $size,
            'r' => $rating,
            'd' => $default,
        );

        return $url . '?' . http_build_query($params, '', $separator);
    }

    /**
     * Get Gravatar Hash
     *
     * @param string $email
     * @return string
     */
    public function getGravatarHash($email)
    {
        return md5(strtolower(trim($email)));
    }

    /**
     * Get CacheId
     *
     * @param string    $email
     * @param string    $prefix
     * @param string    $rating
     * @param integer   $size
     * @param string    $default
     * @return string
     */
    public function getCacheId($email, $cachePrefix = null, $rating = null,
        $size = null, $default = null)
    {
        $cachePrefix = $this->_filterCachePrefix($cachePrefix);
        $size = $this->_filterSize($size);
        $rating = $this->_filterRating($rating);
        $default = $this->_filterDefault($default);

        $id = $cachePrefix;
        $id .= $this->getGravatarHash($email);
        $id .= '_' . $rating;
        $id .= '_' . $size;
        $id .= '_' . md5($default);

        return $id;
    }

    /**
     * Get CacheId
     *
     * @param string    $email
     * @return string
     */
    public function _getCacheId($email)
    {
        return $this->getCacheId(
                $email, $this->getCachePrefix(), $this->getRating(),
                $this->getSize(), $this->getDefault()
        );
    }

    /**
     * Checks if a gravatar exists for the email using default=404
     *
     * @param string    $email
     * @param bool      $cached whether to use cache or not
     * @return boolean
     */
    public function gravatarExists($email, $cached = true)
    {
        $cacheId = $this->_getCacheId($email);
        if (null !== $this->_cache && $cached) {
            $successful = $this->_cache->load($cacheId);
            if ('true' === $successful) {
                return true;
            } elseif ('false' === $successful) {
                return false;
            }
        }
        $httpClient = self::getHttpClient();

        $uri = $this->getUri($email, null, null, null, null, '&');
        $httpClient->setUri($uri);

        $response = $httpClient->request(Zend_Http_Client::GET);
        $success = false;
        if (200 == $response->getStatus()) {
            $success = true;
        }
        if (null !== $this->_cache) {
            if ($success) {
                $this->_cache->save('true', $cacheId);
            } else {
                $this->_cache->save('false', $cacheId);
            }
        }

        return $success;
    }

}
