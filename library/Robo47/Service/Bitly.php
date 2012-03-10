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
 * Robo47_Service_Bitly
 *
 * @package     Robo47
 * @subpackage  Service
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Service_Bitly extends Zend_Service_Abstract
{
    /**
     * API URL
     */

    const API_URL = 'http://api.bit.ly';

    /**
     * API Path Shorten
     */
    const API_PATH_SHORTEN = '/shorten';

    /**
     * API Path Expand
     */
    const API_PATH_EXPAND = '/expand';

    /**
     * API Path Info
     */
    const API_PATH_INFO = '/info';

    /**
     * API Path Stats
     */
    const API_PATH_STATS = '/stats';

    /**
     * API Path Errors
     */
    const API_PATH_ERRORS = '/errors';

    /**
     * Format JSON
     */
    const FORMAT_JSON = 'json';

    /**
     * Format XML
     */
    const FORMAT_XML = 'xml';

    /**
     * Result Format Object
     */
    const FORMAT_RESULT_OBJECT = 'object';

    /**
     * Result Format Array
     */
    const FORMAT_RESULT_ARRAY = 'array';

    /**
     * Result Format Native
     */
    const FORMAT_RESULT_NATIVE = 'native';

    /**
     * The api login
     *
     * @var string
     */
    protected $_login = null;

    /**
     * The api key
     *
     * @var string
     */
    protected $_apiKey = null;

    /**
     * The api version
     *
     * @var string
     */
    protected $_version = null;

    /**
     * The format
     *
     * @see Robo47_Service_Bitly::FORMAT_JSON
     * @see Robo47_Service_Bitly::FORMAT_XML
     * @var string
     */
    protected $_format = Robo47_Service_Bitly::FORMAT_JSON;

    /**
     * The callback (used only if format is json)
     *
     * @var string
     */
    protected $_callback = '';

    /**
     * The result Format
     *
     * @see Robo47_Service_Bitly::FORMAT_RESULT_OBJECT
     * @see Robo47_Service_Bitly::FORMAT_RESULT_ARRAY
     * @see Robo47_Service_Bitly::FORMAT_RESULT_NATIVE
     * @var string
     */
    protected $_resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;

    /**
     *
     * @param string $login
     * @param string $apiKey
     * @param string $format
     * @param string $resultFormat
     * @param string $callback
     * @param string $version
     */
    public function __construct($login, $apiKey, $format = self::FORMAT_JSON,
        $resultFormat = self::FORMAT_RESULT_NATIVE, $callback = '',
        $version = '2.0.1')
    {
        $this->setLogin($login);
        $this->setApiKey($apiKey);
        $this->setFormat($format);
        $this->setResultFormat($resultFormat);
        $this->setCallback($callback);
        $this->setVersion($version);
    }

    /**
     * Set Format
     *
     * Supports json and xml
     *
     * @param string $format
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setFormat($format)
    {
        $format = strtolower($format);
        switch ($format) {
            case self::FORMAT_JSON:
            case self::FORMAT_XML:
                $this->_format = $format;
                break;
            default:
                $message = 'Invalid Format: ' . $format;
                throw new Robo47_Service_Bitly_Exception($message);
        }

        return $this;
    }

    /**
     * Set the callback (used only if format is json)
     *
     * @param string $callback
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setCallback($callback)
    {
        $this->_callback = $callback;

        return $this;
    }

    /**
     * Get the callback
     *
     * @return string
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * Get the format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * Set the version
     *
     * @param string $version
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setVersion($version)
    {
        $this->_version = $version;

        return $this;
    }

    /**
     * Get Version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Set the api login
     *
     * @param string $login
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setLogin($login)
    {
        $this->_login = $login;

        return $this;
    }

    /**
     * Set the api key
     *
     * @param string $apiKey
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the api login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * Get the api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * Set Result Format
     *
     * Possible Formats are native, array, object
     * native returns the response as string
     *
     * @param string $resultFormat
     * @return Robo47_Service_Bitly *Provides Fluent Interface*
     */
    public function setResultFormat($resultFormat)
    {
        $resultFormat = strtolower($resultFormat);
        switch ($resultFormat) {
            case self::FORMAT_RESULT_NATIVE:
            case self::FORMAT_RESULT_ARRAY:
            case self::FORMAT_RESULT_OBJECT:
                $this->_resultFormat = $resultFormat;
                break;
            default:
                $message = 'Invalid Result Format: ' . $resultFormat;
                throw new Robo47_Service_Bitly_Exception($message);
        }

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getResultFormat()
    {
        return $this->_resultFormat;
    }

    /**
     * Call the API
     *
     * @param string $requestUri
     * @return array
     */
    protected function _callApi($requestUri)
    {
        $httpClient = self::getHttpClient();
        $httpClient->setUri($requestUri);
        $response = $httpClient->request(Zend_Http_Client::GET);

        return $this->_getData($response);
    }

    /**
     *
     * @param string $xml
     * @return array
     */
    public function xmlToArray($xml)
    {
        $document = new DOMDocument();
        $document->loadXml($xml);
        $array = array();
        foreach ($document->childNodes as $node) {
            $array[$node->nodeName] = $this->_nodeToArray($node);
        }

        return $array;
    }

    /**
     * @param DOMNode $node
     * @return array|string
     */
    protected function _nodeToArray(DOMNode $node)
    {
        if ($node->hasChildNodes()) {
            $array = array();
            foreach ($node->childNodes as $childNode) {
                /* @var $childNode DOMNode */
                if ($childNode->nodeName == '#text' ||
                    $childNode->nodeName == '#cdata-section') {
                    $array = $this->_nodeToArray($childNode);
                } else {
                    $nodeName = $childNode->nodeName;
                    $array[$nodeName] = $this->_nodeToArray($childNode);
                }
            }
        } else {
            $array = $node->nodeValue;
        }

        return $array;
    }

    /**
     *
     * @param string $xml
     * @return stdClass
     */
    public function xmlToObject($xml)
    {
        $document = new DOMDocument();
        $document->loadXml($xml);
        $object = new StdClass();
        foreach ($document->childNodes as $node) {
            $object->{$node->nodeName} = $this->_nodeToObject($node);
        }

        return $object;
    }

    /**
     * @param DOMNode $node
     * @return array|string
     */
    protected function _nodeToObject(DOMNode $node)
    {
        if ($node->hasChildNodes()) {
            $object = new stdClass();
            foreach ($node->childNodes as $childNode) {
                /* @var $childNode DOMNode */
                if ($childNode->nodeName == '#text' ||
                    $childNode->nodeName == '#cdata-section') {
                    $object = $this->_nodeToArray($childNode);
                } else {
                    $nodeName = $childNode->nodeName;
                    $object->{$nodeName} = $this->_nodeToObject($childNode);
                }
            }
        } else {
            $object = $node->nodeValue;
        }

        return $object;
    }

    /**
     * Returns data of the call if there was no error
     *
     * @throws Robo47_Service_Bitly_Exception
     * @param Zend_Http_Response $response
     * @return string|array|object
     */
    protected function _getData(Zend_Http_Response $response)
    {
        if ($response->isError()) {
            $message = 'Error on api-call: ' . $response->getMessage();
            throw new Robo47_Service_Bitly_Exception($message);
        }

        // find errors without parsing xml / json
        // @todo parse formats always and move tests into the other switch
        //       and refactor a bit, method gets big and ugly
        switch ($this->_format) {
            case self::FORMAT_XML:
                $regex = preg_quote('<errorCode>0</errorCode>', '/');
                break;
            case self::FORMAT_JSON:
                $regex = preg_quote('"errorCode": 0', '/');
                break;
        }
        if (!preg_match('/' . $regex . '/i', $response->getBody())) {
            $message = 'Error on api-call: no errorCode=0 found';
            throw new Robo47_Service_Bitly_Exception($message);
        }

        // @todo checking for errors ALWAYS!
        switch ($this->_resultFormat) {
            case self::FORMAT_RESULT_NATIVE;
                $result = $response->getBody();
                break;
            case self::FORMAT_RESULT_ARRAY:
                switch ($this->_format) {
                    case self::FORMAT_XML:
                        $result = $this->xmlToArray($response->getBody());
                        break;
                    case self::FORMAT_JSON:
                        $result = json_decode($response->getBody(), true);
                        break;
                }
                break;
            case self::FORMAT_RESULT_OBJECT:
                switch ($this->_format) {
                    case self::FORMAT_XML:
                        $result = $this->xmlToObject($response->getBody());
                        break;
                    case self::FORMAT_JSON:
                        $result = json_decode($response->getBody(), false);
                        break;
                }
                break;
        }

        return $result;
    }

    /**
     * Generate URL
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function generateUrl($path, array $params = array())
    {
        $defaultParams = array();
        $defaultParams['version'] = $this->_version;
        $defaultParams['login'] = $this->_login;
        $defaultParams['apiKey'] = $this->_apiKey;
        $defaultParams['format'] = $this->_format;
        if (self::FORMAT_JSON === $defaultParams['format'] &&
            !empty($this->_callback)) {
            $defaultParams['callback'] = $this->_callback;
        }
        $defaultParams = array_merge($defaultParams, $params);

        $url = Robo47_Service_Bitly::API_URL;
        $url .= $path . '?';
        $url .= http_build_query($defaultParams, null, '&');

        return $url;
    }

    /**
     * shorten
     *
     * @param string $longUrl
     * @return mixed
     */
    public function shorten($longUrl)
    {
        $params = array('longUrl' => $longUrl);
        $url = $this->generateUrl(self::API_PATH_SHORTEN, $params);

        return $this->_callApi($url);
    }

    /**
     * expand by shorturl
     *
     * @param string $shortUrl
     * @return mixed
     */
    public function expandByShortUrl($shortUrl)
    {
        $params = array('shortUrl' => $shortUrl);
        $url = $this->generateUrl(self::API_PATH_EXPAND, $params);

        return $this->_callApi($url);
    }

    /**
     * expand by hash
     *
     * @param string $hash
     * @return mixed
     */
    public function expandByHash($hash)
    {
        $params = array('hash' => $hash);
        $url = $this->generateUrl(self::API_PATH_EXPAND, $params);

        return $this->_callApi($url);
    }

    /**
     * info by shorturl
     *
     * @param string $shortUrl
     * @return mixed
     */
    public function infoByShortUrl($shortUrl)
    {
        $params = array('shortUrl' => $shortUrl);

        $url = $this->generateUrl(self::API_PATH_INFO, $params);

        return $this->_callApi($url);
    }

    /**
     * info by shorturl with keys
     *
     * @param string $shortUrl
     * @param array $keys
     * @return mixed
     */
    public function infoByShortUrlWithKeys($shortUrl, array $keys)
    {
        $params = array(
            'shortUrl' => $shortUrl,
            'keys' => implode(',', $keys),
        );

        $url = $this->generateUrl(self::API_PATH_INFO, $params);

        return $this->_callApi($url);
    }

    /**
     * info by hash
     *
     * @param string $hash
     * @return mixed
     */
    public function infoByHash($hash)
    {
        $params = array('hash' => $hash);
        $url = $this->generateUrl(self::API_PATH_INFO, $params);

        return $this->_callApi($url);
    }

    /**
     * info by hash with keys
     *
     * @param string $hash
     * @param array $keys
     * @return mixed
     */
    public function infoByHashWithKeys($hash, array $keys)
    {
        $params = array(
            'hash' => $hash,
            'keys' => implode(',', $keys),
        );

        $url = $this->generateUrl(self::API_PATH_INFO, $params);

        return $this->_callApi($url);
    }

    /**
     * stats by shorturl
     *
     * @param string $shortUrl
     * @return mixed
     */
    public function statsByShortUrl($shortUrl)
    {
        $params = array('shortUrl' => $shortUrl);
        $url = $this->generateUrl(self::API_PATH_STATS, $params);

        return $this->_callApi($url);
    }

    /**
     * stats by Hash
     *
     * @param string $hash
     * @return mixed
     */
    public function statsByHash($hash)
    {
        $params = array('hash' => $hash);
        $url = $this->generateUrl(self::API_PATH_STATS, $params);

        return $this->_callApi($url);
    }

    /**
     * errors
     *
     * Returns the apis possible error-codes
     *
     * @return mixed
     */
    public function errors()
    {
        $url = $this->generateUrl(self::API_PATH_ERRORS, array());

        return $this->_callApi($url);
    }

}
