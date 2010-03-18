<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Service
 * @group Robo47_Service_Bitly
 */
class Robo47_Service_BitlyTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Zend_Http_Client_Adapter_Test
     */
    protected $_adapter = null;
    
    public function setUp()
    {
        $this->_adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
                'adapter' => $this->_adapter
        ));
        Zend_Service_Abstract::setHttpClient($client);
    }
    
    public function tearDown()
    {
        $this->_adapter = null;
    }
    
    public function getResponse($format, $method)
    {
        $path = dirname(__FILE__ ) . '/Bitly/_files/';
        $filename = 'response_' . strtoupper($format) . '_' . strtoupper($method);
        if (!file_exists($path . $filename)) {
            throw new Exception('file not found: ' . $path . $filename);
        }
        return file_get_contents($path . $filename);
    }

    /**
     * @covers Robo47_Service_Bitly<extended>
     * @covers Robo47_Service_Bitly::__construct
     */
    public function testDefaultConstruct()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals('login', $service->getLogin(), 'Wrong login');
        $this->assertEquals('apiKey', $service->getApiKey(), 'Wrong apiKey');
        $this->assertEquals('', $service->getCallback(), 'Wrong callback');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_JSON, $service->getFormat(), 'Wrong fromat');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_RESULT_NATIVE, $service->getResultFormat(), 'Wrong result format');
        $this->assertEquals('2.0.1', $service->getVersion(), 'Wrong version');
    }

    /**
     * @covers Robo47_Service_Bitly::__construct
     */
    public function testConstruct()
    {
        $service = new Robo47_Service_Bitly(
            'baa',
            'foo',
            Robo47_Service_Bitly::FORMAT_XML,
            Robo47_Service_Bitly::FORMAT_RESULT_ARRAY,
            'baafoo',
            '1.2.3'
        );
        $this->assertEquals('baa', $service->getLogin(), 'Wrong login');
        $this->assertEquals('foo', $service->getApiKey(), 'Wrong apiKey');
        $this->assertEquals('baafoo', $service->getCallback(), 'Wrong callback');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_XML, $service->getFormat(), 'Wrong fromat');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_RESULT_ARRAY, $service->getResultFormat(), 'Wrong result format');
        $this->assertEquals('1.2.3', $service->getVersion(), 'Wrong version');
    }

    /**
     * @covers Robo47_Service_Bitly::setFormat
     * @covers Robo47_Service_Bitly::getFormat
     */
    public function testSetFormatGetFormat()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_JSON, $service->getFormat(), 'Wrong format');
        $return = $service->setFormat(Robo47_Service_Bitly::FORMAT_XML);
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_XML, $service->getFormat(), 'Wrong format');
    }

    /**
     * @covers Robo47_Service_Bitly::setCallback
     * @covers Robo47_Service_Bitly::getCallback
     */
    public function testSetCallbackGetCallback()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals('', $service->getCallback(), 'Wrong callback');
        $return = $service->setCallback('foo');
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals('foo', $service->getCallback(), 'Wrong callback');
    }

    /**
     * @covers Robo47_Service_Bitly::setVersion
     * @covers Robo47_Service_Bitly::getVersion
     */
    public function testSetVersionGetVersion()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals('2.0.1', $service->getVersion(), 'Wrong version');
        $return = $service->setVersion('1.2.3');
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals('1.2.3', $service->getVersion(), 'Wrong version');
    }

    /**
     * @covers Robo47_Service_Bitly::setLogin
     * @covers Robo47_Service_Bitly::getLogin
     */
    public function testSetLoginGetLogin()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals('login', $service->getLogin(), 'Wrong login');
        $return = $service->setLogin('foo');
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals('foo', $service->getLogin(), 'Wrong login');
    }

    /**
     * @covers Robo47_Service_Bitly::setApiKey
     * @covers Robo47_Service_Bitly::getApiKey
     */
    public function testSetApiKeyGetApiKey()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals('apiKey', $service->getApiKey(), 'Wrong apiKey');
        $return = $service->setApiKey('foo');
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals('foo', $service->getApiKey(), 'Wrong apiKey');
    }
    
    public function resultFormatProvider()
    {
        $data = array();

        $data[] = array(Robo47_Service_Bitly::FORMAT_RESULT_NATIVE);
        $data[] = array(Robo47_Service_Bitly::FORMAT_RESULT_ARRAY);
        $data[] = array(Robo47_Service_Bitly::FORMAT_RESULT_OBJECT);

        return $data;
    }

    /**
     * @covers Robo47_Service_Bitly::setResultFormat
     * @covers Robo47_Service_Bitly::getResultFormat
     * @dataProvider resultFormatProvider
     */
    public function testSetResultFormatGetResultFormat($format)
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        $this->assertEquals(Robo47_Service_Bitly::FORMAT_RESULT_NATIVE, $service->getResultFormat(), 'Wrong result format');
        $return = $service->setResultFormat($format);
        $this->assertSame($return, $service, 'No Fluent Interface');
        $this->assertEquals($format, $service->getResultFormat(), 'Wrong result format');
    }

    /**
     * @covers Robo47_Service_Bitly::setResultFormat
     * @covers Robo47_Service_Bitly_Exception
     */
    public function testSetResultFormatWithInvalidFormat()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        try {
            $service->setResultFormat('foo');
            $this->fail('No Exception thrown');
        } catch (Robo47_Service_Bitly_Exception $e) {
            $this->assertEquals('Invalid Result Format: foo', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Service_Bitly::setFormat
     * @covers Robo47_Service_Bitly_Exception
     */
    public function testSetFormatWithInvalidFormat()
    {
        $service = new Robo47_Service_Bitly('login', 'apiKey');
        try {
            $service->setFormat('foo');
            $this->fail('No Exception thrown');
        } catch (Robo47_Service_Bitly_Exception $e) {
            $this->assertEquals('Invalid Format: foo', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Service_Bitly::_getData
     * @covers Robo47_Service_Bitly_Exception
     */
    public function testHttpErrorInResponseThrowsException()
    {
        $format = Robo47_Service_Bitly::FORMAT_XML;
        $resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );

        $this->_adapter->setResponse(new Zend_Http_Response(404, array()));
        try {
            $service->shorten('http://www.example.com');
            $this->fail('No Exception thrown');
        } catch (Robo47_Service_Bitly_Exception $e) {
            $this->assertEquals('Error on api-call: Not Found', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Service_Bitly::_getData
     * @covers Robo47_Service_Bitly_Exception
     */
    public function testApiErrorInResponseThrowsExceptionWithJSON()
    {
        $format = Robo47_Service_Bitly::FORMAT_JSON;
        $resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );

        $body = '
{
    "errorCode": 5,
    "errorMessage": "Something went wrong",
    "statusCode": "ERROR"
}
';

        $this->_adapter->setResponse(new Zend_Http_Response(200, array(), $body));
        try {
            $service->shorten('http://www.example.com');
            $this->fail('No Exception thrown');
        } catch (Robo47_Service_Bitly_Exception $e) {
            $this->assertEquals('Error on api-call: no errorCode=0 found', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Service_Bitly::_getData
     * @covers Robo47_Service_Bitly_Exception
     */
    public function testApiErrorInResponseThrowsExceptionWithXML()
    {
        $format = Robo47_Service_Bitly::FORMAT_JSON;
        $resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );

        $body = '<bitly><errorCode>5</errorCode><errorMessage>something went wrong</errorMessage></bitly>';

        $this->_adapter->setResponse(new Zend_Http_Response(200, array(), $body));
        try {
            $service->shorten('http://www.example.com');
            $this->fail('No Exception thrown');
        } catch (Robo47_Service_Bitly_Exception $e) {
            $this->assertEquals('Error on api-call: no errorCode=0 found', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Service_Bitly::xmlToArray
     * @covers Robo47_Service_Bitly::_nodeToArray
     */
    public function testXmlToArray()
    {
        $format = Robo47_Service_Bitly::FORMAT_JSON;
        $resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );
        $xml = '<bitly><errorCode>0</errorCode><errorMessage></errorMessage><results><doc><shortenedByUser>robo47</shortenedByUser><keywords></keywords><hash>3hDSUb</hash><exif></exif><surbl>0</surbl><contentLength></contentLength><id3></id3><calais></calais><longUrl>http://www.example.com/</longUrl><version>1.0</version><htmlMetaDescription><![CDATA[]]></htmlMetaDescription><htmlMetaKeywords></htmlMetaKeywords><calaisId></calaisId><thumbnail></thumbnail><contentType>text/html; charset=UTF-8</contentType><users></users><globalHash>3hDSUb</globalHash><htmlTitle><![CDATA[Example Web Page]]></htmlTitle><metacarta></metacarta><mirrorUrl></mirrorUrl><keyword></keyword><calaisResolutions></calaisResolutions><userHash>cAWQVU</userHash></doc></results><statusCode>OK</statusCode></bitly>';
        $array = $service->xmlToArray($xml);

        $this->assertEquals(1, count($array), 'Wrong element count');
        $this->assertArrayHasKey('bitly', $array, 'array misses element');

        $this->assertEquals(4, count($array['bitly']), 'Wrong element count');

        $this->assertArrayHasKey('errorCode', $array['bitly'], 'array misses element');
        $this->assertArrayHasKey('errorMessage', $array['bitly'], 'array misses element');
        $this->assertArrayHasKey('results', $array['bitly'], 'array misses element');
        $this->assertArrayHasKey('statusCode', $array['bitly'], 'array misses element');

        $this->assertEquals('0', $array['bitly']['errorCode'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['errorMessage'], 'Wrong value for element');
        $this->assertEquals('OK', $array['bitly']['statusCode'], 'Wrong value for element');

        $this->assertArrayHasKey('doc', $array['bitly']['results'], 'array misses element');

        $this->assertEquals(23, count($array['bitly']['results']['doc']), 'Wrong element count');
        $this->assertArrayHasKey('shortenedByUser', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('keywords', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('hash', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('exif', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('surbl', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('contentLength', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('id3', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('calais', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('longUrl', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('version', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('htmlMetaDescription', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('htmlMetaKeywords', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('calaisId', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('thumbnail', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('contentType', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('users', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('globalHash', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('htmlTitle', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('metacarta', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('mirrorUrl', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('keyword', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('calaisResolutions', $array['bitly']['results']['doc'], 'array misses element');
        $this->assertArrayHasKey('userHash', $array['bitly']['results']['doc'], 'array misses element');

        $this->assertEquals('robo47', $array['bitly']['results']['doc']['shortenedByUser'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['keywords'], 'Wrong value for element');
        $this->assertEquals('3hDSUb', $array['bitly']['results']['doc']['hash'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['exif'], 'Wrong value for element');
        $this->assertEquals('0', $array['bitly']['results']['doc']['surbl'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['contentLength'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['id3'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['calais'], 'Wrong value for element');
        $this->assertEquals('http://www.example.com/', $array['bitly']['results']['doc']['longUrl'], 'Wrong value for element');
        $this->assertEquals('1.0', $array['bitly']['results']['doc']['version'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['htmlMetaDescription'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['htmlMetaKeywords'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['calaisId'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['thumbnail'], 'Wrong value for element');
        $this->assertEquals('text/html; charset=UTF-8', $array['bitly']['results']['doc']['contentType'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['users'], 'Wrong value for element');
        $this->assertEquals('3hDSUb', $array['bitly']['results']['doc']['globalHash'], 'Wrong value for element');
        $this->assertEquals('Example Web Page', $array['bitly']['results']['doc']['htmlTitle'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['metacarta'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['mirrorUrl'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['keyword'], 'Wrong value for element');
        $this->assertEquals('', $array['bitly']['results']['doc']['calaisResolutions'], 'Wrong value for element');
        $this->assertEquals('cAWQVU', $array['bitly']['results']['doc']['userHash'], 'Wrong value for element');
    }

    /**
     * @covers Robo47_Service_Bitly::xmlToObject
     * @covers Robo47_Service_Bitly::_nodeToObject
     */
    public function testXmlToObject()
    {
        $format = Robo47_Service_Bitly::FORMAT_JSON;
        $resultFormat = Robo47_Service_Bitly::FORMAT_RESULT_NATIVE;
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );
        $xml = '<bitly><errorCode>0</errorCode><errorMessage></errorMessage><results><doc><shortenedByUser>robo47</shortenedByUser><keywords></keywords><hash>3hDSUb</hash><exif></exif><surbl>0</surbl><contentLength></contentLength><id3></id3><calais></calais><longUrl>http://www.example.com/</longUrl><version>1.0</version><htmlMetaDescription><![CDATA[]]></htmlMetaDescription><htmlMetaKeywords></htmlMetaKeywords><calaisId></calaisId><thumbnail></thumbnail><contentType>text/html; charset=UTF-8</contentType><users></users><globalHash>3hDSUb</globalHash><htmlTitle><![CDATA[Example Web Page]]></htmlTitle><metacarta></metacarta><mirrorUrl></mirrorUrl><keyword></keyword><calaisResolutions></calaisResolutions><userHash>cAWQVU</userHash></doc></results><statusCode>OK</statusCode></bitly>';
        $object = $service->xmlToObject($xml);

        $this->assertEquals(1, count(get_object_vars($object)), 'Wrong element count');
        $this->assertObjectHasAttribute('bitly', $object, 'array misses element');
        $this->assertEquals(4, count(get_object_vars($object->bitly)), 'Wrong element count');

        $this->assertObjectHasAttribute('errorCode', $object->bitly, 'array misses element');
        $this->assertObjectHasAttribute('errorMessage', $object->bitly, 'array misses element');
        $this->assertObjectHasAttribute('results', $object->bitly, 'array misses element');
        $this->assertObjectHasAttribute('statusCode', $object->bitly, 'array misses element');

        $this->assertEquals('0', $object->bitly->errorCode, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->errorMessage, 'Wrong value for element');
        $this->assertEquals('OK', $object->bitly->statusCode, 'Wrong value for element');

        $this->assertObjectHasAttribute('doc', $object->bitly->results, 'array misses element');

        $this->assertEquals(23, count(get_object_vars($object->bitly->results->doc)), 'Wrong element count');
        $this->assertObjectHasAttribute('shortenedByUser', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('keywords', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('hash', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('exif', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('surbl', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('contentLength', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('id3', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('calais', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('longUrl', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('version', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('htmlMetaDescription', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('htmlMetaKeywords', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('calaisId', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('thumbnail', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('contentType', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('users', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('globalHash', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('htmlTitle', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('metacarta', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('mirrorUrl', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('keyword', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('calaisResolutions', $object->bitly->results->doc, 'array misses element');
        $this->assertObjectHasAttribute('userHash', $object->bitly->results->doc, 'array misses element');

        $this->assertEquals('robo47', $object->bitly->results->doc->shortenedByUser, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->keywords, 'Wrong value for element');
        $this->assertEquals('3hDSUb', $object->bitly->results->doc->hash, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->exif, 'Wrong value for element');
        $this->assertEquals('0', $object->bitly->results->doc->surbl, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->contentLength, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->id3, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->calais, 'Wrong value for element');
        $this->assertEquals('http://www.example.com/', $object->bitly->results->doc->longUrl, 'Wrong value for element');
        $this->assertEquals('1.0', $object->bitly->results->doc->version, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->htmlMetaDescription, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->htmlMetaKeywords, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->calaisId, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->thumbnail, 'Wrong value for element');
        $this->assertEquals('text/html; charset=UTF-8', $object->bitly->results->doc->contentType, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->users, 'Wrong value for element');
        $this->assertEquals('3hDSUb', $object->bitly->results->doc->globalHash, 'Wrong value for element');
        $this->assertEquals('Example Web Page', $object->bitly->results->doc->htmlTitle, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->metacarta, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->mirrorUrl, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->keyword, 'Wrong value for element');
        $this->assertEquals('', $object->bitly->results->doc->calaisResolutions, 'Wrong value for element');
        $this->assertEquals('cAWQVU', $object->bitly->results->doc->userHash, 'Wrong value for element');
    }
    
    public function apiMethodsProvider()
    {
        $data = array();

        $methods = array(
            'shorten' => array('longUrl' => 'http://www.example.org'),
            'expandByHash' => array('hash' => 'cAWQVU'),
            'expandByShortUrl' => array('shortUrl' => 'http://bit.ly/cAWQVU'),
            'infoByHash' => array('hash' => 'cAWQVU'),
            'infoByHashWithKeys' => array('hash' => 'cAWQVU', 'keys' => array('hash', 'thumbnail')),
            'infoByShortUrl' => array('shortUrl' => 'http://bit.ly/cAWQVU'),
            'infoByShortUrlWithKeys' => array('shortUrl' => 'http://bit.ly/cAWQVU', 'keys' => array('hash', 'thumbnail')),
            'statsByHash' => array('hash' => 'cAWQVU'),
            'statsByShortUrl' => array('shortUrl' => 'http://bit.ly/cAWQVU'),
            'errors' => array(),
        );

        $formats = array(
            Robo47_Service_Bitly::FORMAT_JSON,
            Robo47_Service_Bitly::FORMAT_XML
        );

        $resultFormats = array(
            Robo47_Service_Bitly::FORMAT_RESULT_NATIVE,
            Robo47_Service_Bitly::FORMAT_RESULT_ARRAY,
            Robo47_Service_Bitly::FORMAT_RESULT_OBJECT,
        );

        foreach ($methods as $method => $params) {
            foreach ($formats as $format) {
                foreach ($resultFormats as $resultFormat) {
                    $data[] = array($method, $params, $format, $resultFormat);
                }
            }
        }
        return $data;
    }
    
    public function generateUrlProvider()
    {
        $data = array();

        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey'
        );

        $defaultParams = array(
            'login' => $service->getLogin(),
            'apiKey' => $service->getApiKey(),
            'version' => $service->getVersion(),
            'format' => $service->getFormat(),
            'version' => $service->getVersion(),
        );

        $data[] = array(
            clone $service,
            '/foo',
            array('foo' => 'baa', 'blub' => 'bla'),
        );

        // tests#1 that callback is only attachted with json
        $s2 = clone $service;
        $s2->setCallback('baafoo');
        $s2->setFormat('json');
        $data[] = array(
            $s2,
            '/foo',
            array('callback' => 'baafoo'),
        );

        // tests#2 that callback is only attachted with json
        $s3 = clone $service;
        $s3->setCallback('baafoo');
        $s3->setFormat('xml');
        $data[] = array(
            $s3,
            '/foo',
            array('format' => 'xml'),
        );
        // test#3 testing params get encoded
        $data[] = array(
            clone $service,
            '/foo',
            array('foo' => 'http://www.example.com?asdf=bla', 'blub' => 'bla'),
        );

        foreach ($data as $key => $value) {
            $params = array_merge($defaultParams, $data[$key][2]);
            $data[$key][3] = Robo47_Service_Bitly::API_URL . $data[$key][1];
            $data[$key][3] .= '?' . http_build_query($params, null, '&');
        }

        return $data;
    }

    /**
     * @covers Robo47_Service_Bitly::generateUrl
     * @dataProvider generateUrlProvider
     */
    public function testGenerateUrl($service, $path, $params, $url)
    {
        $generatedUrl = parse_url($service->generateUrl($path, $params));

        $expectedUrl = parse_url($url);

        // test all parts, expect query, because parts can have different order
        $this->assertEquals($generatedUrl['scheme'], $expectedUrl['scheme'], 'Wrong scheme');
        $this->assertEquals($generatedUrl['host'], $expectedUrl['host'], 'Wrong host');
        $this->assertEquals($generatedUrl['path'], $expectedUrl['path'], 'Wrong path');

        $expectedParams = array();
        $realParams = array();
        parse_str($generatedUrl['query'], $expectedParams);
        parse_str($expectedUrl['query'], $realParams);

        $this->assertEquals(count($expectedParams), count($realParams), 'Parameter Count missmatch');

        foreach ($realParams as $key => $value) {
            $this->assertEquals($expectedParams[$key], $realParams[$key], 'Wrong value for param ' . $key);
        }
    }

    /**
     * @dataProvider apiMethodsProvider
     */
    public function testGenerateUrlGeneratesSameUrlsApiCallsShouldUse($method, $params, $format, $resultFormat)
    {
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );

        $this->_adapter->setResponse($this->getResponse($format, $method));

        $result = call_user_func_array(array($service, $method), $params);

        $methods = array(
            'shorten' => Robo47_Service_Bitly::API_PATH_SHORTEN,
            'expandByHash' => Robo47_Service_Bitly::API_PATH_EXPAND,
            'expandByShortUrl' => Robo47_Service_Bitly::API_PATH_EXPAND,
            'infoByHash' => Robo47_Service_Bitly::API_PATH_INFO,
            'infoByHashWithKeys' => Robo47_Service_Bitly::API_PATH_INFO,
            'infoByShortUrl' => Robo47_Service_Bitly::API_PATH_INFO,
            'infoByShortUrlWithKeys' => Robo47_Service_Bitly::API_PATH_INFO,
            'statsByHash' => Robo47_Service_Bitly::API_PATH_STATS,
            'statsByShortUrl' => Robo47_Service_Bitly::API_PATH_STATS,
            'errors' => Robo47_Service_Bitly::API_PATH_ERRORS,
        );

        if (!isset($methods[$method])) {
            $this->fail('invalid Method: ' . $method);
        }

        $urlParams = $params;
        if (isset($urlParams['keys'])) {
            $urlParams['keys'] = implode(',', $urlParams['keys']);
        }

        $generatedUrl = parse_url($service->generateUrl($methods[$method], $urlParams));

        $url = parse_url($service->getHttpClient()->getUri()->getUri());

        foreach ($generatedUrl as $key => $value) {
            $this->assertEquals($generatedUrl[$key], $url[$key], 'Urls don\'t match in part:' . $key);
        }
    }

    /**
     * @covers Robo47_Service_Bitly::_callApi
     * @covers Robo47_Service_Bitly::_getData
     * @covers Robo47_Service_Bitly::shorten
     * @covers Robo47_Service_Bitly::expandByHash
     * @covers Robo47_Service_Bitly::expandByShortUrl
     * @covers Robo47_Service_Bitly::infoByHash
     * @covers Robo47_Service_Bitly::infoByHashWithKeys
     * @covers Robo47_Service_Bitly::infoByShortUrl
     * @covers Robo47_Service_Bitly::infoByShortUrlWithKeys
     * @covers Robo47_Service_Bitly::statsByHash
     * @covers Robo47_Service_Bitly::statsByShortUrl
     * @covers Robo47_Service_Bitly::errors
     * @dataProvider apiMethodsProvider
     */
    public function testApiMethods($method, $params, $format, $resultFormat)
    {
        $service = new Robo47_Service_Bitly(
            'login',
            'apiKey',
            $format,
            $resultFormat
        );

        $this->_adapter->setResponse($this->getResponse($format, $method));

        $result = call_user_func_array(array($service, $method), $params);

        $response = $service->getHttpClient()->request()->getBody();

        if ($format == Robo47_Service_Bitly::FORMAT_JSON) {
            if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_NATIVE) {
                $this->assertEquals($result, $response, 'Mismatch of result and response');
            } else if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_ARRAY) {
                $this->assertEquals($result, json_decode($response, true), 'Mismatch of result and response');
            } else if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_OBJECT) {
                $this->assertEquals($result, json_decode($response, false), 'Mismatch of result and response');
            } else {
                $this->fail('invalid resultformat: ' . $resultFormat);
            }
        } else if ($format == Robo47_Service_Bitly::FORMAT_XML) {
            if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_NATIVE) {
                $this->assertEquals($result, $response, 'Mismatch of result and response');
            } else if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_ARRAY) {
                $this->assertEquals($result, $service->xmlToArray($response), 'Mismatch of result and response');
            } else if ($resultFormat == Robo47_Service_Bitly::FORMAT_RESULT_OBJECT) {
                $this->assertEquals($result, $service->xmlToObject($response), 'Mismatch of result and response');
            } else {
                $this->fail('invalid resultformat: ' . $resultFormat);
            }
        } else {
            $this->fail('invalid format: ' . $format);
        }
    }
}