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
 * @copyright  Copyright (c) 2008-2009 Robo47 (http://components.robo47.net/)
 * @license    New BSD {@link http://components.robo47.net/LICENSE}
 */

/**
 * An object oriented Wrapper for the curl_*-functions
 *
 * @author     Benjamin Steininger <robo47[at]robo47[dot]net>
 * @license    http://components.robo47.net/LICENSE New BSD License
 * @category   Robo47
 * @package    Robo47_Curl
 * @copyright  Copyright (c) 2008-2009 Robo47 (http://components.robo47.net/)
 */
class Robo47_Curl
{
    /**
     * Saves all Options set to the Curl instance
     *
     *
     * @var array
     */
    protected $_options = array();

    /**
     * curl Instance
     *
     * @var Resource
     */
    protected $_curl = null;

    /**
     * Content of Document if ReturnTransfer == true
     *
     * @var string
     */
    protected $_body = '';

    /**
     * @param string $url
     * @param boolean $returnTransfer
     */
    public function __construct($url = null, $returnTransfer = true)
    {
        $this->_curl = curl_init();
        if ($url != null) {
            $this->setOption(CURLOPT_URL, $url);
        }
        $this->setOption(CURLOPT_RETURNTRANSFER, $returnTransfer);
    }

    /**
     * @param integer $option
     * @param mixed $value
     * @return Robo47_Curl *Provides Fluent Interface*
     */
    public function setOption($option, $value)
    {
        $success = curl_setopt($this->_curl, $option, $value);
        if (false === $success) {
            $message = 'Error Setting Option: ' . (string)$option;
            throw new Robo47_Curl_Exception($message);
        }
        $this->_options[$option] = $value;
        return $this;
    }

    /**
     * @param array $options
     * @return Robo47_Curl *Provides Fluent Interface*
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param integer $option
     * @return mixed
     */
    public function getInfo($option)
    {
        return curl_getinfo($this->_curl, $option);
    }

    /**
     * @param array $options
     * @return array
     */
    public function getInfoArray(array $options)
    {
        $infos = array();
        foreach ($options as $option) {
            $infos[$option] = curl_getinfo($this->_curl, $option);
        }
        return $infos;
    }

    /**
     * @return Robo47_Curl *Provides Fluent Interface*
     */
    public function exec()
    {
        $this->_body = curl_exec($this->_curl);
        return $this;
    }

    /**
     * Returns Contents return by curl_exec
     *
     * @param boolean $noHeaders
     * @return string
     */
    public function getBody($noHeaders = true)
    {
        if (true === $noHeaders) {
            $pos = strpos($this->_body, "\r\n\r\n");
            if (false !== $pos) {
                return substr($this->_body, $pos);
            } else {
                throw new Robo47_Curl_Exception('unable to find body');
            }
        }
        return $this->_body;
    }

    /**
     * @param boolean $asArray
     * @return string
     */
    public function getHeaders($asArray = false)
    {
        if (isset($this->_options[CURLOPT_HEADER]) &&
            $this->_options[CURLOPT_HEADER] == true) {

            $pos = strpos($this->_body, "\r\n\r\n");
            if ($pos !== false) {
                $headers = substr($this->_body, 0, $pos);
            } else {
                $headers = '';
            }
        } else {
            $headers = '';
        }
        if (true === $asArray) {
            $headers = explode("\n", $headers);
            $aHeaders = array();
            foreach ($headers as $line) {
                $pos = strpos($line, ':');
                if (false !== $pos) {
                    $headerName = trim(substr($line, 0, $pos));
                    $headerValue = trim(substr($line, $pos + 1));
                    $aHeaders[$headerName] = $headerValue;
                }
            }
            return $aHeaders;
        } else {
            return $headers;
        }
    }

    /**
     * @return string
     */
    public function getError()
    {
        return curl_error($this->_curl);
    }

    /**
     * @return integer
     */
    public function getErrno()
    {
        return curl_errno($this->_curl);
    }

    /**
     * @return Robo47_Curl *Provides Fluent Interface*
     */
    public function close()
    {
        @curl_close($this->_curl);
        return $this;
    }

    /**
     * Clone Instance
     *
     * @return Robo47_Curl
     */
    public function __clone()
    {
        // resource can't be cloned, so we need to init a new one
        $this->_curl = curl_init();
        // needed for setting old options to the new curl-resource
        $this->setOptions($this->getOptions());
    }

    /**
     * @param integer $age
     * @return mixed
     */
    public function getVersion($age = 0)
    {
        return curl_version($age);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return resource
     */
    public function getCurl()
    {
        return $this->_curl;
    }

    /**
     *
     * Using set/getCurl can break getOptions !!
     *
     * @param resource $curl
     */
    public function setCurl($curl)
    {
        if (!is_resource($curl) || get_resource_type($curl) !== 'curl') {
            throw new Robo47_Curl_Exception('$curl is not an curl-resource');
        }
        $this->setOptions($this->getOptions());
        $this->_curl = $curl;
    }
}
