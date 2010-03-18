<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Filter
 * @group Robo47_Filter_HtmlPurifier
 */
class Robo47_Filter_HtmlPurifierTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        if (count($loader->getNamespaceAutoloaders('HtmlPurifier')) == 0) {
            $loader->pushAutoloader(new Robo47_Loader_Autoloader_HtmlPurifier(), 'HtmlPurifier');
        }
    }
    
    public function tearDown()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        $HtmlPurifierLoaders = $loader->getNamespaceAutoloaders('HtmlPurifier');
        foreach ($HtmlPurifierLoaders as $hloader) {
            $loader->removeAutoloader($hloader, 'HtmlPurifier');
        }
        Robo47_Filter_HtmlPurifier::setDefaultPurifier();
        Zend_Registry::_unsetInstance();
    }

    /**
     *
     * @return HtmlPurifier
     */
    public function getPurifier()
    {
        $options = array(
            'Core.Encoding' => array('Core.Encoding', 'UTF-8'),
            'HTML.Doctype' => array('HTML.Doctype', 'XHTML 1.0 Strict'),
            'HTML.Allowed' => array('HTML.Allowed', 'abbr[title],acronym[title],'
                    . 'em,strong,a[href],ul,ol,li'
                    . ',code,pre,cite,q[cite],'
                    . 'blockquote[cite],sub,sup,p,'
                    . 'br'),
            'AutoFormat.Linkify' => array('AutoFormat.Linkify', 'true'),
            'Cache.SerializerPath' => array('Cache.SerializerPath',
                BASE_PATH . '/tests/tmp'),
        );

        $config = HTMLPurifier_Config::createDefault();
        foreach ($options as $option) {
            $config->set($option[0], $option[1]);
        }
        $purifier = new HTMLPurifier($config);
        return $purifier;
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier<extended>
     * @covers Robo47_Filter_HtmlPurifier::__construct
     */
    public function testConstructor()
    {
        $purifier = $this->getPurifier();
        $filter = new Robo47_Filter_HtmlPurifier($purifier);
        $this->assertSame($purifier, $filter->getPurifier());
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::__construct
     * @covers Robo47_Filter_HtmlPurifier::setDefaultPurifier
     * @covers Robo47_Filter_HtmlPurifier::getDefaultPurifier
     */
    public function testSetDefaultPurifierGetDefaultPurifierAndConstructorUsingDefaultValue()
    {
        $this->assertNull(Robo47_Filter_HtmlPurifier::getDefaultPurifier());
        $purifier = $this->getPurifier();
        Robo47_Filter_HtmlPurifier::setDefaultPurifier($purifier);
        $this->assertSame($purifier, Robo47_Filter_HtmlPurifier::getDefaultPurifier());
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::setPurifier
     */
    public function testSetPurifierUsesDefaultPurifier()
    {
        $purifier = $this->getPurifier();
        $purifier2 = $this->getPurifier();
        Robo47_Filter_HtmlPurifier::setDefaultPurifier($purifier);
        $filter = new Robo47_Filter_HtmlPurifier();
        $this->assertSame($purifier, $filter->getPurifier(), 'Constructor did not set purifier to defaultPurifier');
        $filter->setPurifier($purifier2);
        $this->assertSame($purifier2, $filter->getPurifier(), 'setPurifier did not set purifier');
        $filter->setPurifier();
        $this->assertSame($purifier, $filter->getPurifier(), 'setPurifier() did not set purifier to defaultPurifier');
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::__construct
     */
    public function testContructor2()
    {
        $this->assertNull(Robo47_Filter_HtmlPurifier::getDefaultPurifier());
        $filter = new Robo47_Filter_HtmlPurifier();
        $this->assertType('HtmlPurifier', $filter->getPurifier(), 'Wrong type');
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::setPurifier
     * @covers Robo47_Filter_HtmlPurifier::getPurifier
     */
    public function testSetPurifierGetPurifier()
    {
        $purifier = $this->getPurifier();
        $filter = new Robo47_Filter_HtmlPurifier();
        $this->assertNotSame($purifier, $filter->getPurifier());
        $filter->setPurifier($purifier);
        $this->assertSame($purifier, $filter->getPurifier());
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::setPurifier
     * @covers Robo47_Filter_HtmlPurifier::_purifierFromRegistry
     */
    public function testSetPurifierFromRegistry()
    {
        $myPurifier = $this->getPurifier();
        Zend_Registry::set('MyPurifier', $myPurifier);
        $filter = new Robo47_Filter_HtmlPurifier();
        $filter->setPurifier('MyPurifier');
        $this->assertSame($myPurifier, $filter->getPurifier(), 'Wrong Purifier');
    }

    /**
     * @return array
     */
    public function filterProvider()
    {
        $data = array();

        $data[] = array('<img src="/bla" alt="foo">', '');
        $data[] = array('<cite title="foo">xxx</cite>', '<cite>xxx</cite>');
        $data[] = array('<a href="/bla">Blub</a>', '<a href="/bla">Blub</a>');

        return $data;
    }

    /**
     *
     * @param mixed $value
     * @param mixed $expectedValue
     * @dataProvider filterProvider
     * @covers Robo47_Filter_HtmlPurifier::filter
     */
    public function testFilter($value, $expectedValue)
    {
        $filter = new Robo47_Filter_HtmlPurifier($this->getPurifier());
        $this->assertSame($expectedValue, $filter->filter($value));
    }
    
    public function invalidPurifierObjects()
    {
        $data = array();

        $data[] = array(1);
        $data[] = array(new stdClass());
        $data[] = array(1.1);

        return $data;
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::setPurifier
     * @covers Robo47_Filter_HtmlPurifier::_purifierFromRegistry
     * @covers Robo47_Filter_Exception
     * @dataProvider invalidPurifierObjects
     */
    public function testSetPurifierWithOtherObjects($object)
    {
        $filter = new Robo47_Filter_HtmlPurifier();
        try {
            $filter->setPurifier($object);
            $this->fail('No exception thrown on setting purifier with a non-purifier-object');
        } catch (Robo47_Filter_Exception $e) {
            $this->assertEquals('purifier is no instance of class HtmlPurifier', $e->getMessage(), 'Wrong Exception Message');
        }
    }

    /**
     * @covers Robo47_Filter_HtmlPurifier::setPurifier
     * @covers Robo47_Filter_HtmlPurifier::_purifierFromRegistry
     * @covers Robo47_Filter_Exception
     */
    public function testSetPurifierFromRegistryWithoutPurifierinRegistry()
    {
        $filter = new Robo47_Filter_HtmlPurifier();
        try {
            $filter->setPurifier('MyPurifier');
            $this->fail('No exception thrown on setting purifier via registry without existing in Registry');
        } catch (Robo47_Filter_Exception $e) {
            $this->assertEquals('Registry key "MyPurifier" for HtmlPurifier is not registered.', $e->getMessage(), 'Wrong Exception Message');
        }
    }
}