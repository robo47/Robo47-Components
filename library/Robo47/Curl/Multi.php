<?php

/**
 * Robo47 Components
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @author     Benjamin Steininger <robo47[at]robo47[dot]net>
 * @category   Robo47
 * @package    Robo47_Curl
 * @copyright  Copyright (c) 2007-2010 Robo47 (http://components.robo47.net/)
 * @license    New BSD {@link http://components.robo47.net/LICENSE}
 */
/**
 * An object oriented Wrapper for the curl_multi_*-functions
 *
 * @author     Benjamin Steininger <robo47[at]robo47[dot]net>
 * @license    http://components.robo47.net/LICENSE New BSD License
 * @category   Robo47
 * @package    Robo47_Curl
 * @copyright  Copyright (c) 2007-2010 Robo47 (http://components.robo47.net/)
 */
class Robo47_Curl_Multi implements Countable
{

    /**
     * curl-multi
     *
     * @var resource
     */
    protected $_curlMulti = null;
    /**
     * @var array of Robo47_Curl
     */
    protected $_curls = array();

    /**
     *
     * @param resource $curlMulti
     */
    public function __construct($curlMulti = null)
    {
        $this->setCurlMulti($curlMulti);
    }

    /**
     * Get Curls
     *
     * @return array
     */
    public function getCurls()
    {
        return $this->_curls;
    }

    /**
     * Add Handle
     *
     * @return Robo47_Curl_Multi *Provides Fluent Interface*
     */
    public function addHandle(Robo47_Curl $curl)
    {
        curl_multi_add_handle($this->_curlMulti, $curl->getCurl());
        $this->_curls[] = $curl;
        return $this;
    }

    /**
     * Remove Handle
     *
     * @return Robo47_Curl_Multi *Provides Fluent Interface*
     */
    public function removeHandle(Robo47_Curl $curl)
    {
        curl_multi_remove_handle($this->_curlMulti, $curl->getCurl());
        foreach ($this->_curls as $key=>$internalCurl) {
            if ($internalCurl === $curl) {
                unset($this->_curls[$key]);
            }
        }
        return $this;
    }

    /**
     * Execute
     *
     * @return Robo47_Curl_Multi *Provides Fluent Interface*
     */
    public function exec(& $running)
    {
        curl_multi_exec($this->_curlMulti, $running);
        return $this;
    }

    /**
     * Set CurlMulti
     *
     * @param resource $curlMulti
     * @return Robo47_Curl_Multi *Provides Fluent Interface*
     */
    public function setCurlMulti($curlMulti)
    {
        if (null === $curlMulti) {
            $curlMulti = curl_multi_init();
        }
        if (!is_resource($curlMulti) ||
            get_resource_type($curlMulti) != 'curl_multi') {
            $message = '$curlMulti is not an curl-resource';
            throw new Robo47_Curl_Multi_Exception($message);
        }
        $this->_curlMulti = $curlMulti;
        return $this;
    }

    /**
     * Get CurlMulti
     *
     * @return resource
     */
    public function getCurlMulti()
    {
        return $this->_curlMulti;
    }

    /**
     * Implements SPL::Countable
     *
     * @return int
     */
    public function count()
    {
        return count($this->_curls);
    }

    /**
     *Close
     */
    public function close()
    {
        @curl_multi_close($this->_curlMulti);
        return $this;
    }

    /**
     * Destructor
     *
     * Closes the resource
     */
    public function __destruct()
    {
        $this->close();
    }
}