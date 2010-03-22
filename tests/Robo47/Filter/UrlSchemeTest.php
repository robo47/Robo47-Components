<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Filter
 * @group Robo47_Filter_UrlScheme
 */
class Robo47_Filter_UrlSchemeTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Robo47_Filter_UrlScheme
     */
    protected $_filter = null;
    
    public function setUp()
    {
        $this->_filter = new Robo47_Filter_UrlScheme();
    }
    
    public function tearDown()
    {
        $this->_filter = null;
    }

    /**
     * @covers Robo47_Filter_UrlScheme
     */
    public function testDefaultConstructor()
    {
        $filter = new Robo47_Filter_UrlScheme();
        $this->assertEquals('http', $filter->getDefaultScheme(), 'default scheme is wrong');
        $schemes = $filter->getSchemes();
        $this->assertContains('http', $schemes, 'schemes is missing http');
        $this->assertContains('https', $schemes, 'schemes is missing https');
        $this->assertContains('ftp', $schemes, 'schemes is missing ftp');
        $this->assertContains('ftps', $schemes, 'schemes is missing ftps');
        $this->assertTrue($filter->getTrim(), 'trim is not true by default');
    }

    /**
     * @covers Robo47_Filter_UrlScheme::__construct
     * @covers Robo47_Filter_UrlScheme::setOptions
     */
    public function testConstructorAndSetOptions()
    {
        $options = array('defaultScheme' => 'baa',
            'schemes' => array('baa', 'foo'),
            'trim' => false,
            'foo' => 'blub');

        $filter = new Robo47_Filter_UrlScheme($options);
        $this->assertEquals('baa', $filter->getDefaultScheme(), 'default scheme is wrong');
        $schemes = $filter->getSchemes();
        $this->assertEquals(2, count($schemes), 'number of schemes is wrong');
        $this->assertContains('baa', $schemes, 'scheme baa is missing');
        $this->assertContains('foo', $schemes, 'scheme foo is missing');
        $this->assertFalse($filter->getTrim());
    }

    /**
     * @covers Robo47_Filter_UrlScheme::setTrim
     * @covers Robo47_Filter_UrlScheme::getTrim
     */
    public function testSetTrimGetTrim()
    {
        $filter = new Robo47_Filter_UrlScheme();
        $return = $filter->setTrim(false);
        $this->assertSame($return, $filter, 'setTrim() does not have fluent interface');
        $this->assertEquals(false, $filter->getTrim());
    }

    /**
     * @covers Robo47_Filter_UrlScheme::setDefaultScheme
     * @covers Robo47_Filter_UrlScheme::getDefaultScheme
     */
    public function testSetDefaultSchemeGetDefaultScheme()
    {
        $filter = new Robo47_Filter_UrlScheme();
        $return = $filter->setDefaultScheme('foo');
        $this->assertSame($return, $filter, 'setDefaultScheme() does not have fluent interface');
        $this->assertEquals('foo', $filter->getDefaultScheme());
    }

    /**
     * @covers Robo47_Filter_UrlScheme::setSchemes
     * @covers Robo47_Filter_UrlScheme::getSchemes
     */
    public function testSetSchemesGetSchemes()
    {
        $filter = new Robo47_Filter_UrlScheme();
        $return = $filter->setSchemes(array('foo', 'baa'));
        $this->assertSame($return, $filter, 'setScheme() does not have fluent interface');
        $schemes = $filter->getSchemes();
        $this->assertEquals(2, count($schemes), 'number of schemes is wrong');
        $this->assertContains('foo', $schemes, 'scheme foo is missing');
        $this->assertContains('baa', $schemes, 'scheme baa is missing');
    }

    /**
     * @return array
     */
    public function filterProvider()
    {
        $data = array();

        // prefix normal strings
        $data[] = array('foo', 'http://foo');

        // dont prefix strings with a scheme
        $data[] = array('http://foo', 'http://foo');
        $data[] = array('https://foo', 'https://foo');
        $data[] = array('ftp://foo', 'ftp://foo');
        $data[] = array('ftps://foo', 'ftps://foo');

        // empty keeps empty
        $data[] = array('', '');

        // without trimming
        $data[] = array(' http://foo', 'http:// http://foo', array('trim' => false));
        $data[] = array(' https://foo', 'http:// https://foo', array('trim' => false));
        $data[] = array(' ftp://foo', 'http:// ftp://foo', array('trim' => false));
        $data[] = array(' ftps://foo', 'http:// ftps://foo', array('trim' => false));

        // with trimming
        $data[] = array(' http://foo', 'http://foo');
        $data[] = array(' https://foo', 'https://foo');
        $data[] = array(' ftp://foo', 'ftp://foo');
        $data[] = array(' ftps://foo', 'ftps://foo');

        $data[] = array('baa://foo', 'baa://foo', array('defaultScheme' => 'baa',
                'schemes' => array('baa', 'foo')));

        $data[] = array('foo', 'baa://foo', array('defaultScheme' => 'baa',
                'schemes' => array('baa', 'foo')));

        $data[] = array('baa', 'baa://baa', array('defaultScheme' => 'baa',
                'schemes' => array('baa', 'foo')));

        return $data;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $expectedValue
     * @param array $options
     * @dataProvider filterProvider
     * @covers Robo47_Filter_UrlScheme::filter
     */
    public function testFilter($value, $expectedValue, array $options = array())
    {
        $this->_filter->setOptions($options);
        $this->assertSame($expectedValue, $this->_filter->filter($value));
    }
}