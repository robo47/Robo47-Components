<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Filter
 * @group Robo47_Filter_Tidy
 */
class Robo47_Filter_TidyTest extends PHPUnit_Framework_TestCase
{
    
    public function tearDown()
    {
        Robo47_Filter_Tidy::setDefaultTidy(null);
        Zend_Registry::_unsetInstance();
    }

    /**
     * @covers Robo47_Filter_Tidy
     */
    public function testDefaultConstructor()
    {
        $filter = new Robo47_Filter_Tidy();
        $this->assertEquals(array(), $filter->getConfig(), 'Wrong Config');
        $this->assertEquals('utf8', $filter->getEncoding(), 'Wrong Encoding');
        $this->assertType('Tidy', $filter->getTidy());
    }

    /**
     * @covers Robo47_Filter_Tidy::__construct
     */
    public function testConstructorWithTidy()
    {
        $tidy = new Tidy();
        $filter = new Robo47_Filter_Tidy($tidy);
        $this->assertSame($tidy, $filter->getTidy());
    }

    /**
     * @covers Robo47_Filter_Tidy::__construct
     */
    public function testConstructorWithConfig()
    {
        $config = array('indent' => TRUE,
            'output-xhtml' => TRUE,
            'wrap' => 200);

        $filter = new Robo47_Filter_Tidy(null, $config);
        $this->assertEquals($config, $filter->getConfig(), 'Wrong Config');
    }

    /**
     * @covers Robo47_Filter_Tidy::__construct
     */
    public function testConstructorWithEncoding()
    {
        $encoding = 'latin1';

        $filter = new Robo47_Filter_Tidy(null, null, $encoding);
        $this->assertEquals($encoding, $filter->getEncoding(), 'Wrong Encoding');
    }
    
    public function encodingProvider()
    {
        $data = array();

        $data[] = array('utf-8', 'utf8');
        $data[] = array('ascii', 'ascii');
        $data[] = array('latin0', 'latin0');
        $data[] = array('latin1', 'latin1');
        $data[] = array('raw', 'raw');
        $data[] = array('utf8', 'utf8');
        $data[] = array('iso2022', 'iso2022');
        $data[] = array('mac', 'mac');
        $data[] = array('win1252', 'win1252');
        $data[] = array('ibm858', 'ibm858');
        $data[] = array('utf16', 'utf16');
        $data[] = array('utf16le', 'utf16le');
        $data[] = array('utf16be', 'utf16be');
        $data[] = array('big5', 'big5');
        $data[] = array('shiftjis', 'shiftjis');

        $data2 = $data;
        // add all in uppercase and expect lowercase
        foreach ($data2 as $value) {
            $data[] = array(strtoupper($value[0]), $value[1]);
        }

        return $data;
    }

    /**
     * @covers Robo47_Filter_Tidy::setEncoding
     * @covers Robo47_Filter_Tidy::getEncoding
     * @dataProvider encodingProvider
     */
    public function testSetEncodingGetEncoding($encoding, $expected)
    {
        $filter = new Robo47_Filter_Tidy();
        $return = $filter->setEncoding($encoding);
        $this->assertSame($filter, $return, 'No Fluent Interface');
        $this->assertEquals($expected, $filter->getEncoding());
    }

    /**
     * @covers Robo47_Filter_Tidy::setEncoding
     * @covers Robo47_Filter_Exception
     */
    public function testSetEncodingWithInvalidEncoding()
    {
        $filter = new Robo47_Filter_Tidy();
        try {
            $filter->setEncoding('Foo');
            $this->fail('No exception thrown on setting encoding to invalid encoding');
        } catch (Robo47_Filter_Exception $e) {
            $this->assertEquals('Unknown encoding: foo', $e->getMessage(), 'Wrong Exception Message');
        }
    }

    /**
     * @covers Robo47_Filter_Tidy::setTidy
     * @covers Robo47_Filter_Tidy::getTidy
     * @dataProvider encodingProvider
     */
    public function testSetTidyGetTidy()
    {
        $tidy = new Tidy();
        $filter = new Robo47_Filter_Tidy();
        $return = $filter->setTidy($tidy);
        $this->assertSame($filter, $return, 'No Fluent Interface');
        $this->assertEquals($tidy, $filter->getTidy());
    }

    /**
     * @covers Robo47_Filter_Tidy::setTidy
     * @covers Robo47_Filter_Tidy::_tidyFromRegistry
     */
    public function testSetTidyFromRegistry()
    {
        $myTidy = new Tidy();
        Zend_Registry::set('MyTidy', $myTidy);
        $filter = new Robo47_Filter_Tidy();
        $filter->setTidy('MyTidy');
        $this->assertSame($myTidy, $filter->getTidy(), 'Wrong Tidy');
    }
    
    public function invalidTidyObjects()
    {
        $data = array();

        $data[] = array(1);
        $data[] = array(new stdClass());
        $data[] = array(1.1);

        return $data;
    }

    /**
     * @covers Robo47_Filter_Tidy::setTidy
     * @covers Robo47_Filter_Tidy::_tidyFromRegistry
     * @covers Robo47_Filter_Exception
     * @dataProvider invalidTidyObjects
     */
    public function testSetTidyWithOtherObjects($object)
    {
        $filter = new Robo47_Filter_Tidy();
        try {
            $filter->setTidy($object);
            $this->fail('No exception thrown on setting tidy with a non-tidy-object');
        } catch (Robo47_Filter_Exception $e) {
            $this->assertEquals('Tidy is no instance of class Tidy', $e->getMessage(), 'Wrong Exception Message');
        }
    }

    /**
     * @covers Robo47_Filter_Tidy::setTidy
     * @covers Robo47_Filter_Tidy::_tidyFromRegistry
     * @covers Robo47_Filter_Exception
     */
    public function testSetTidyFromRegistryWithoutTidyinRegistry()
    {
        $filter = new Robo47_Filter_Tidy();
        try {
            $filter->setTidy('MyTidy');
            $this->fail('No exception thrown on setting tidy via registry without existing in Registry');
        } catch (Robo47_Filter_Exception $e) {
            $this->assertEquals('Registry key "MyTidy" for Tidy is not registered.', $e->getMessage(), 'Wrong Exception Message');
        }
    }

    /**
     * @covers Robo47_Filter_Tidy::setConfig
     * @covers Robo47_Filter_Tidy::getConfig
     */
    public function testSetConfigGetConfig()
    {
        $config = array(
            'indent' => true,
            'output-xhtml' => true,
            'wrap' => 200
        );
        $filter = new Robo47_Filter_Tidy();
        $return = $filter->setConfig($config);
        $this->assertSame($filter, $return, 'No Fluent Interface');
        $objectConfig = $filter->getConfig();
        foreach ($config as $key => $value) {
            $this->assertArrayHasKey($key, $objectConfig, 'Config misses Key');
            $this->assertEquals($value, $objectConfig[$key], 'Config has wrong value for key ' . $key);
        }
    }

    /**
     * @covers Robo47_Filter_Tidy::setConfig
     * @covers Robo47_Filter_Tidy::getConfig
     */
    public function testSetConfigGetConfigWithZendConfig()
    {
        $config = new Zend_Config(
            array(
                'indent' => true,
                'output-xhtml' => true,
                'wrap' => 200
            )
        );
        $filter = new Robo47_Filter_Tidy();
        $return = $filter->setConfig($config);
        $this->assertSame($filter, $return, 'No Fluent Interface');
        $objectConfig = $filter->getConfig();
        foreach ($config as $key => $value) {
            $this->assertArrayHasKey($key, $objectConfig, 'Config misses Key');
            $this->assertEquals($value, $objectConfig[$key], 'Config has wrong value for key ' . $key);
        }
    }

    /**
     * @covers Robo47_Filter_Tidy::setDefaultTidy
     * @covers Robo47_Filter_Tidy::getDefaultTidy
     */
    public function testSetDefaultTidyGetDefaultTidy()
    {
        $tidy = new Tidy();
        $this->assertNull(Robo47_Filter_Tidy::getDefaultTidy());
        Robo47_Filter_Tidy::setDefaultTidy($tidy);
        $this->assertSame($tidy, Robo47_Filter_Tidy::getDefaultTidy());
    }

    /**
     * @covers Robo47_Filter_Tidy::setTidy
     */
    public function testSetTidyUsesDefaultTidy()
    {
        $tidy = new Tidy();
        $tidy2 = new Tidy();
        Robo47_Filter_Tidy::setDefaultTidy($tidy);
        $filter = new Robo47_Filter_Tidy();
        $this->assertSame($tidy, $filter->getTidy(), 'Constructor did not set tidy to defaultTidy');
        $filter->setTidy($tidy2);
        $this->assertSame($tidy2, $filter->getTidy(), 'setTidy did not set tidy');
        $filter->setTidy();
        $this->assertSame($tidy, $filter->getTidy(), 'setTidy() did not set tidy to defaultTidy');
    }
    
    public function filterProvider()
    {
        $data = array();

        $data[] = array('<html><head><title>foo</title></head><body></body></html>');
        $data[] = array('<head><title></head>');

        return $data;
    }

    /**
     * @dataProvider filterProvider
     * @covers Robo47_Filter_Tidy::filter
     */
    public function testFilter($code)
    {
        $filter = new Robo47_Filter_Tidy();
        $filtered = $filter->filter($code);
        $tidy = new Tidy();
        $tidy->parseString($code, $filter->getConfig(), $filter->getEncoding());
        $tidy->cleanRepair();
        $this->assertEquals((string) $tidy, $filtered, 'Filter output missmatches direct tidy-output');
    }
}