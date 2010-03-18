<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

/**
 * @group Robo47_Exiftool
 */
class Robo47_ExiftoolTest extends PHPUnit_Framework_TestCase
{
    
    public function getExiftoolTestImage()
    {
        return TESTS_PATH . '/Robo47/_files/exiftoolTestImage.jpg';
    }
    
    public function getExifToolWrapper1()
    {
        return TESTS_PATH . '/Robo47/_files/exiftoolwrapper1.php';
    }

    /**
     * @covers Robo47_Exiftool::__construct
     */
    public function testConstructDefault()
    {
        $exiftool = new Robo47_Exiftool();
        $this->assertEquals('/usr/bin/exiftool', $exiftool->getExiftool(), 'Default exiftool-path is wrong');
        $this->assertEquals(Robo47_Exiftool::FORMAT_JSON, $exiftool->getFormat(), 'Default format is wrong');
    }

    /**
     * @covers Robo47_Exiftool::__construct
     */
    public function testConstructWithExiftoolAndFormat()
    {
        $exiftool = new Robo47_Exiftool('/foo/exiftool', Robo47_Exiftool::FORMAT_ARRAY);
        $this->assertEquals('/foo/exiftool', $exiftool->getExiftool(), 'Passing exiftool via constructor failed');
        $this->assertEquals(Robo47_Exiftool::FORMAT_ARRAY, $exiftool->getFormat(), 'Passing format via constructor failed');
    }

    /**
     * @covers Robo47_Exiftool::setExiftool
     * @covers Robo47_Exiftool::getExiftool
     */
    public function testSetExiftolGetExiftool()
    {
        $exiftool = new Robo47_Exiftool('/foo/exiftool');
        $this->assertEquals('/foo/exiftool', $exiftool->getExiftool());
        $return = $exiftool->setExiftool('/baa/exiftool');
        $this->assertSame($return, $exiftool, 'setExiftool() does not have fluent interface');
        $this->assertEquals('/baa/exiftool', $exiftool->getExiftool());
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool_Exception
     */
    public function testGetExifsThrowsExceptionIfPathIsNoFile()
    {
        $exiftool = new Robo47_Exiftool();
        $file = dirname(__FILE__ );
        try {
            $exiftool->getExifs($file);
            $this->fail('no exception thrown');
        } catch (Robo47_Exiftool_Exception $e) {
            $this->assertEquals('File "' . $file . '" does not exist.', $e->getMessage(), 'Exception message does not match');
        }
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool_Exception
     */
    public function testGetExifsThrowsExceptionIfFileNotExists()
    {
        $exiftool = new Robo47_Exiftool();
        $file = dirname(__FILE__ ) . '/some.non-existing.file.jpeg';
        try {
            $exiftool->getExifs($file);
            $this->fail('no exception thrown');
        } catch (Robo47_Exiftool_Exception $e) {
            $this->assertEquals('File "' . $file . '" does not exist.', $e->getMessage(), 'Exception message does not match');
        }
    }
    
    public function formatProvider()
    {
        $data = array();

        $data[] = array(Robo47_Exiftool::FORMAT_ARRAY);
        $data[] = array(Robo47_Exiftool::FORMAT_JSON);
        $data[] = array(Robo47_Exiftool::FORMAT_XML);

        return $data;
    }

    /**
     * @covers Robo47_Exiftool::setFormat
     * @dataProvider formatProvider
     */
    public function testSetFormatWithAllAllowedFormats($format)
    {
        $exiftool = new Robo47_Exiftool();
        $exiftool->setFormat($format);
        $this->assertEquals($format, $exiftool->getFormat());
    }

    /**
     * @covers Robo47_Exiftool::setFormat
     * @covers Robo47_Exiftool::getFormat
     */
    public function testSetFormatGetFormat()
    {
        $exiftool = new Robo47_Exiftool();
        $return = $exiftool->setFormat(Robo47_Exiftool::FORMAT_ARRAY);
        $this->assertSame($return, $exiftool, 'setFormat() does not have fluent interface');
        $this->assertEquals(Robo47_Exiftool::FORMAT_ARRAY, $exiftool->getFormat());
    }

    /**
     * @covers Robo47_Exiftool::setFormat
     * @covers Robo47_Exiftool_Exception
     */
    public function testSetFormatWithInvalidFormat()
    {
        $exiftool = new Robo47_Exiftool();
        $format = 'foo';
        try {
            $exiftool->setFormat($format);
            $this->fail('no exception thrown');
        } catch (Robo47_Exiftool_Exception $e) {
            $this->assertEquals('Invalid format: foo', $e->getMessage(), 'Exception message does not match');
        }
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool::_runExiftool
     */
    public function testGetExifsAsJson()
    {
        $image = $this->getExiftoolTestImage();
        $exiftool = new Robo47_Exiftool($this->getExifToolWrapper1());
        $result = $exiftool->getExifs($image, Robo47_Exiftool::FORMAT_JSON);
        $resultArray = json_decode($result);
        $this->assertEquals(3, count($resultArray), 'Wrong count of results');

        $this->assertArrayHasKey(0, $resultArray, 'Array misses Key 0');
        $this->assertArrayHasKey(1, $resultArray, 'Array misses Key 1');
        $this->assertArrayHasKey(2, $resultArray, 'Array misses Key 2');

        $this->assertEquals($this->getExifToolWrapper1(), $resultArray[0], 'Wrong value for param 1');
        $this->assertEquals('-j', $resultArray[1], 'Wrong value for param 1');
        $this->assertEquals($this->getExiftoolTestImage(), $resultArray[2], 'Wrong value for param 1');
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool::_runExiftool
     */
    public function testGetExifsAsXml()
    {
        $image = $this->getExiftoolTestImage();
        $exiftool = new Robo47_Exiftool($this->getExifToolWrapper1());
        $result = $exiftool->getExifs($image, Robo47_Exiftool::FORMAT_XML);
        $xml = new DOMDocument();
        $xml->loadXML($result);
        $node0List = $xml->getElementsByTagName('attr0');
        $node1List = $xml->getElementsByTagName('attr1');
        $node2List = $xml->getElementsByTagName('attr2');

        $this->assertEquals(1, count($node0List), 'Wrong count of nodes for attr0');
        $this->assertEquals(1, count($node1List), 'Wrong count of nodes for attr1');
        $this->assertEquals(1, count($node2List), 'Wrong count of nodes for attr2');

        $this->assertEquals($this->getExifToolWrapper1(), $node0List->item(0)->nodeValue, 'Wrong value for param 0');
        $this->assertEquals('-X', $node1List->item(0)->nodeValue, 'Wrong value for param 1');
        $this->assertEquals($this->getExiftoolTestImage(), $node2List->item(0)->nodeValue, 'Wrong value for param 2');
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool::_runExiftool
     */
    public function testGetExifsAsArray()
    {
        $image = $this->getExiftoolTestImage();
        $exiftool = new Robo47_Exiftool($this->getExifToolWrapper1());
        $resultArray = $exiftool->getExifs($image, Robo47_Exiftool::FORMAT_ARRAY);
        $this->assertEquals(3, count($resultArray), 'Wrong count of results');

        $this->assertArrayHasKey(0, $resultArray, 'Array misses Key 0');
        $this->assertArrayHasKey(1, $resultArray, 'Array misses Key 1');
        $this->assertArrayHasKey(2, $resultArray, 'Array misses Key 2');

        $this->assertEquals($this->getExifToolWrapper1(), $resultArray[0], 'Wrong value for param 1');
        $this->assertEquals('-j', $resultArray[1], 'Wrong value for param 1');
        $this->assertEquals($this->getExiftoolTestImage(), $resultArray[2], 'Wrong value for param 1');
    }

    /**
     * @covers Robo47_Exiftool::getExifs
     * @covers Robo47_Exiftool::_runExiftool
     */
    public function testGetExifsAsArrayBySetFormat()
    {
        $image = $this->getExiftoolTestImage();
        $exiftool = new Robo47_Exiftool($this->getExifToolWrapper1());
        $exiftool->setFormat(Robo47_Exiftool::FORMAT_ARRAY);
        $resultArray = $exiftool->getExifs($image);
        $this->assertEquals(3, count($resultArray), 'Wrong count of results');

        $this->assertArrayHasKey(0, $resultArray, 'Array misses Key 0');
        $this->assertArrayHasKey(1, $resultArray, 'Array misses Key 1');
        $this->assertArrayHasKey(2, $resultArray, 'Array misses Key 2');

        $this->assertEquals($this->getExifToolWrapper1(), $resultArray[0], 'Wrong value for param 1');
        $this->assertEquals('-j', $resultArray[1], 'Wrong value for param 1');
        $this->assertEquals($this->getExiftoolTestImage(), $resultArray[2], 'Wrong value for param 1');
    }

    /**
     * @covers Robo47_Exiftool::_runExiftool
     */
    public function testRunningNonExistingExiftool()
    {
        $file = dirname(__FILE__ ) . '/_files/exiftoolTestImage.jpg';
        $exiftool = new Robo47_Exiftool('/non/existing/exiftool', Robo47_Exiftool::FORMAT_ARRAY);
        try {
            $exiftool->getExifs($file);
            $this->fail('No Exception thrown');
        } catch (Robo47_Exiftool_Exception $e) {
            $this->assertContains('executing exiftool failed: ', $e->getMessage(), 'Wrong Exception message');
        }
    }
}