<?php
require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * @todo any kind of url-stream-mock-extension
 * @todo test getHeaders, getError, getErrno, exec, getInfoArray, getInfo
 * @group Robo47_Curl
 */
class Robo47_CurlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Curl::getCurl
     * @covers Robo47_Curl::setCurl
     */
    public function testGetSetCurl()
    {
        $curl = new Robo47_Curl();
        $curlResource = $curl->getCurl();
        $this->assertInternalType('resource', $curlResource);
        $this->assertEquals('curl', get_resource_type($curlResource));

        $resource = curl_init();

        $curl->setCurl($resource);

        $curlResource = $curl->getCurl();
        $this->assertInternalType('resource', $curlResource);
        $this->assertSame($curlResource, $resource);
    }

    /**
     * @covers Robo47_Curl::__construct
     * @covers Robo47_Curl::__destruct
     * @covers Robo47_Curl::close
     */
    public function testConstruct()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $options = $curl->getOptions();
        $this->assertArrayHasKey(CURLOPT_URL, $options);
        $this->assertEquals('http://example.com', $options[CURLOPT_URL]);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);
        $this->assertEquals(true, $options[CURLOPT_RETURNTRANSFER]);
        unset($curl);

        $curl = new Robo47_Curl('http://example.com/foo', false);
        $options = $curl->getOptions();
        $this->assertArrayHasKey(CURLOPT_URL, $options);
        $this->assertEquals('http://example.com/foo', $options[CURLOPT_URL]);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);
        $this->assertEquals(false, $options[CURLOPT_RETURNTRANSFER]);
        unset($curl);

        $curl = new Robo47_Curl(null);
        $options = $curl->getOptions();
        $this->assertArrayNotHasKey(CURLOPT_URL, $options);
        unset($curl);
    }

    /**
     * @covers Robo47_Curl::getCurl
     * @covers Robo47_Curl::setCurl
     * @covers Robo47_Curl_Exception
     */
    public function testGetSetCurlWithInvalidResource()
    {
        $curl = new Robo47_Curl();
        try {
            $curl->setCurl('string');
            $this->fail('no exception thrown on passing string to setCurl');
        } catch (Robo47_Curl_Exception $e) {
            $this->assertEquals('$curl is not an curl-resource', $e->getMessage());
        }

        $curl = new Robo47_Curl();
        try {
            $file = fopen(__FILE__ , 'r');
            $curl->setCurl($file);
            fclose($file);
            $this->fail('no exception thrown on passing stream resource (fopen()) to setCurl');
        } catch (Robo47_Curl_Exception $e) {
            fclose($file);
            $this->assertEquals('$curl is not an curl-resource', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Curl::getOptions
     * @covers Robo47_Curl::setOptions
     * @covers Robo47_Curl::setOption
     */
    public function testSetGetOptions()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $curl->setOption(CURLOPT_AUTOREFERER, true);

        $options = $curl->getOptions();

        $this->assertArrayHasKey(CURLOPT_URL, $options);
        $this->assertArrayHasKey(CURLOPT_AUTOREFERER, $options);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);

        $this->assertEquals('http://example.com', $options[CURLOPT_URL]);
        $this->assertTrue($options[CURLOPT_AUTOREFERER]);
        $this->assertTrue($options[CURLOPT_RETURNTRANSFER]);

        $curl = new Robo47_Curl('http://example.com', true);
        $newOptions = array();
        $newOptions[CURLOPT_URL] = 'http://example.com';
        $newOptions[CURLOPT_AUTOREFERER] = false;
        $newOptions[CURLOPT_RETURNTRANSFER] = false;
        $curl->setOptions($newOptions);

        unset($options);

        $options = $curl->getOptions();

        $this->assertArrayHasKey(CURLOPT_URL, $options);
        $this->assertArrayHasKey(CURLOPT_AUTOREFERER, $options);
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);

        $this->assertEquals('http://example.com', $options[CURLOPT_URL]);
        $this->assertFalse($options[CURLOPT_AUTOREFERER]);
        $this->assertFalse($options[CURLOPT_RETURNTRANSFER]);
    }

    /**
     * @covers Robo47_Curl::setOption
     * @covers Robo47_Curl_Exception
     */
    public function testSetOptionWithInvalidOption()
    {
        $this->markTestIncomplete('found no way to let curl_setopt return false');
        $curl = new Robo47_Curl('http://example.com', true);
        try {
            $curl->setOption(1234567, 'bla');
            $this->fail('no exception thrown on setting non-existing option');
        } catch (Robo47_Curl_Exception $e) {
            $this->assertEquals('Error Setting Option: -1', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Curl::getError
     * @covers Robo47_Curl::getErrno
     */
    public function testGetErrorGetErrno()
    {
        $this->markTestIncomplete('testing doesnt work right');
        $curl = new Robo47_Curl('https://example.com', true);
        $curl->setOption(CURLOPT_TIMEOUT, 1);
        $this->assertEquals('', $curl->getError());
        $this->assertEquals(0, $curl->getErrno());
        $curl->exec();

        $this->assertEquals('couldn\'t connect to host', $curl->getError());
        $this->assertEquals(CURLE_COULDNT_CONNECT, $curl->getErrno());
    }

    /**
     * @covers Robo47_Curl::getVersion
     */
    public function testGetVersion()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $version = $curl->getVersion();
        $expectedVersion = curl_version();
        $this->assertSame($expectedVersion, $version);
    }

    /**
     * @covers Robo47_Curl::getHeaders
     */
    public function testGetHeaders()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $curl->setOption(CURLOPT_HEADER, true);
        $curl->exec();

        // @todo some better asserts
        $headers = $curl->getHeaders(true);
        $this->assertInternalType('array', $headers);
        $this->assertGreaterThan(2, count($headers));

        $headers = $curl->getHeaders(false);
        $this->assertInternalType('string', $headers);
        $this->assertGreaterThan(10, strlen($headers));
    }

    /**
     * @covers Robo47_Curl::getBody
     */
    public function testGetBody()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $curl->setOption(CURLOPT_HEADER, true);
        $curl->exec();
        $bodyWithoutHeaders = $curl->getBody(true);
        $bodyWithHeaders = $curl->getBody(false);

        $this->assertGreaterThan(strlen($bodyWithoutHeaders), strlen($bodyWithHeaders));
    }

    /**
     * @covers Robo47_Curl::__clone
     */
    public function testClone()
    {
        $curl = new Robo47_Curl('http://example.com', true);
        $curl->setOption(CURLOPT_HEADER, false);
        $curl2 = clone $curl;

        $this->assertNotSame($curl, $curl2);
        $this->assertEquals($curl->getOptions(), $curl2->getOptions());
        $this->assertNotSame($curl->getCurl(), $curl2->getCurl());
    }
}