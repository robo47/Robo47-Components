<?php
require_once dirname(dirname(__FILE__ )) . DIRECTORY_SEPARATOR . '../TestHelper.php';

/**
 * @group Robo47_Curl
 * @group Robo47_Curl_Mutli
 */
class Robo47_Curl_MultiTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Curl_Multi
     * @covers Robo47_Curl_Multi_Exception
     * @todo make test for each method
     */
    public function testCurlMulti()
    {
        $curlMulti = new Robo47_Curl_Multi();
        $curl1 = new Robo47_Curl('http://example.com/doc1', true);
        $curl2 = new Robo47_Curl('http://example.com/doc2', true);
        $curl3 = new Robo47_Curl('http://example.com/doc3', true);

        $curlMulti->addHandle($curl1)
            ->addHandle($curl2)
            ->addHandle($curl3);

        $this->assertEquals(3, count($curlMulti));

        $curls = $curlMulti->getCurls();

        $this->assertInternalType('array', $curls);
        $this->assertEquals(3, count($curls));

        $curlMulti->removeHandle($curl2);

        $curls = $curlMulti->getCurls();

        $this->assertEquals(2, count($curlMulti));

        $this->assertInternalType('array', $curls);
        $this->assertEquals(2, count($curls));
        $this->assertContains($curl1, $curls);
        $this->assertContains($curl3, $curls);

        $resource = $curlMulti->getCurlMulti();
        $this->assertInternalType('resource', $resource);
        $this->assertEquals('curl_multi', get_resource_type($resource));

        $curlMulti->exec($number);

        $resource = $curlMulti->getCurlMulti();

        $curlMulti->setCurlMulti(curl_multi_init());

        $this->assertInternalType('resource', $resource);

        $this->assertEquals('curl_multi', get_resource_type($resource));
        $this->assertNotSame($resource, $curlMulti->getCurlMulti());
        try {
            $file = fopen(__FILE__ , 'r');
            $curlMulti->setCurlMulti($file);
            fclose($file);
            $this->fail('no exception thrown on passing invalid param to setCurlMulti()');
        } catch (Robo47_Curl_Multi_Exception $e) {
            $this->assertEquals('$curlMulti is not an curl-resource', $e->getMessage());
        }
        $curlMulti->close();
        unset($curlMulti);
    }
}