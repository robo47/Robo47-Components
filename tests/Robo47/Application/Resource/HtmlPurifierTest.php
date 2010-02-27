<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Application_Resource_HtmlPurifierTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        if (!class_exists('HTMLPurifier_Bootstrap', false)) {
            require_once 'HTMLPurifier/Bootstrap.php';
        }
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
        Zend_Registry::_unsetInstance();
    }
    
    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
    }

    /**
     * @covers Robo47_Application_Resource_HtmlPurifier::init
     * @covers Robo47_Application_Resource_HtmlPurifier::_setupHtmlPurifier
     * @covers Robo47_Application_Resource_HtmlPurifier::getHtmlPurifier
     */
    public function testInitWithRegistryKey()
    {
        $options = array(
            'options' => array(
                'Core.Encoding' => 'utf-8',
                'HTML.Doctype'  => 'XHTML 1.0 Strict',
                'HTML.Allowed'  => 'abbr[title],acronym[title],'
                    . 'em,strong,a[href],ul,ol,li'
                    . ',code,pre,cite,q[cite],'
                    . 'blockquote[cite],sub,sup,p,'
                    . 'br',
                'AutoFormat.Linkify'    => 'true',
                'Cache.SerializerPath'  => TESTS_PATH . '/tmp/'
            ),
            'registryKey' => 'HTMLPurifier',
        );

        $resource = new Robo47_Application_Resource_HtmlPurifier($options);
        $resource->init();

        $HtmlPurifier = $resource->getHtmlPurifier();
        /* @var $HtmlPurifier HtmlPurifier */

        $this->assertType('HTMLPurifier', $HtmlPurifier);
        $config = $HtmlPurifier->config;
        /* @var $config HTMLPurifier_Config */
        $this->assertEquals($options['options']['Core.Encoding'], $config->get('Core.Encoding'));
        $this->assertEquals($options['options']['HTML.Doctype'], $config->get('HTML.Doctype'));
        $this->assertEquals($options['options']['HTML.Allowed'], $config->get('HTML.Allowed'));
        $this->assertEquals($options['options']['AutoFormat.Linkify'], $config->get('AutoFormat.Linkify'));
        $this->assertEquals($options['options']['Cache.SerializerPath'], $config->get('Cache.SerializerPath'));

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']));
    }

    /**
     * @covers Robo47_Application_Resource_HtmlPurifier<extended>
     * @covers Robo47_Application_Resource_HtmlPurifier::init
     * @covers Robo47_Application_Resource_HtmlPurifier::_setupHtmlPurifier
     * @covers Robo47_Application_Resource_HtmlPurifier::getHtmlPurifier
     */
    public function testInit()
    {
        $options = array(
            'options' => array(
                'Core.Encoding' => 'utf-8',
                'HTML.Doctype'  => 'XHTML 1.0 Strict',
                'HTML.Allowed'  => 'abbr[title],acronym[title],'
                    . 'em,strong,a[href],ul,ol,li'
                    . ',code,pre,cite,q[cite],'
                    . 'blockquote[cite],sub,sup,p,'
                    . 'br',
                'AutoFormat.Linkify'    => 'true',
                'Cache.SerializerPath'  => TESTS_PATH . '/tmp/'
            ),
        );

        $resource = new Robo47_Application_Resource_HtmlPurifier($options);
        $resource->init();

        $HtmlPurifier = $resource->getHtmlPurifier();
        /* @var $HtmlPurifier HtmlPurifier */

        $this->assertType('HTMLPurifier', $HtmlPurifier);
        $config = $HtmlPurifier->config;
        /* @var $config HTMLPurifier_Config */
        $this->assertEquals($options['options']['Core.Encoding'], $config->get('Core.Encoding'));
        $this->assertEquals($options['options']['HTML.Doctype'], $config->get('HTML.Doctype'));
        $this->assertEquals($options['options']['HTML.Allowed'], $config->get('HTML.Allowed'));
        $this->assertEquals($options['options']['AutoFormat.Linkify'], $config->get('AutoFormat.Linkify'));
        $this->assertEquals($options['options']['Cache.SerializerPath'], $config->get('Cache.SerializerPath'));
    }

    /**
     * @covers Robo47_Application_Resource_HtmlPurifier::init
     * @covers Robo47_Application_Resource_Exception
     */
    public function testInitWithoutHtmlPurifier()
    {
        $resource = new Robo47_Application_Resource_HtmlPurifier(array());

        try {
            $resource->init();
            $this->fail('no exception thrown on init without HtmlPurifier');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_HtmlPurifier.', $e->getMessage());
        }
    }
}