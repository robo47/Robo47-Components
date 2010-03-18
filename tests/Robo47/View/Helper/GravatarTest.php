<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_View
 * @group Robo47_View_Helper
 * @group Robo47_View_Helper_Gravatar
 */
class Robo47_View_Helper_GravatarTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
    }
    
    public function tearDown()
    {
        Zend_Controller_Front::getInstance()->resetInstance();
    }

    /**
     * @covers Robo47_View_Helper_Gravatar::__construct
     */
    public function testConstructDefault()
    {
        $helper = new Robo47_View_Helper_Gravatar();
        $service = $helper->getService();
        $this->assertType('Robo47_Service_Gravatar', $service);
    }

    /**
     * @covers Robo47_View_Helper_Gravatar<extended>
     * @covers Robo47_View_Helper_Gravatar::__construct
     */
    public function testConstructWithService()
    {
        $service = new Robo47_Service_Gravatar();
        $helper = new Robo47_View_Helper_Gravatar($service);
        $this->assertType('Robo47_Service_Gravatar', $helper->getService());
        $this->assertSame($service, $helper->getService());
    }

    /**
     * @covers Robo47_View_Helper_Gravatar::setService
     * @covers Robo47_View_Helper_Gravatar::getService
     */
    public function testSetServiceGetService()
    {
        $service = new Robo47_Service_Gravatar();
        $helper = new Robo47_View_Helper_Gravatar();
        $this->assertType('Robo47_Service_Gravatar', $helper->getService());
        $helper->setService($service);
        $this->assertSame($service, $helper->getService());
    }
    
    public function gravatarProvider()
    {
        $data = array();

        $data[] = array(
            'foo@example.com',
            200,
            Robo47_Service_Gravatar::RATING_G,
            '',
            true,
            '&',
            array('foo' => 'baa')
        );

        $data[] = array(
            'baafoo@example.com',
            80,
            Robo47_Service_Gravatar::RATING_G,
            Robo47_Service_Gravatar::DEFAULT_404,
            false,
            '&amp;',
            array('alt' => '<Blubber>"Foo"</Blubber>')
        );

        $data[] = array(
            'baafoo@example.com',
            null,
            null,
            null,
            null,
            '&amp;',
            array('alt' => 'Blubber')
        );

        return $data;
    }

    /**
     * @covers Robo47_View_Helper_Gravatar::Gravatar
     * @dataProvider gravatarProvider
     */
    public function testGravatar($email, $size, $rating, $default, $ssl, $separator, $params)
    {
        $view = new Zend_View();
        $view->setEncoding('utf-8');
        $view->Doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
        $service = new Robo47_Service_Gravatar();
        $helper = new Robo47_View_Helper_Gravatar($service);
        $helper->setView($view);
        $gravatarImageTag = $helper->Gravatar($email, $size, $rating, $default, $ssl, $separator, $params);

        $src = $service->getUri($email, $size, $rating, $default, $ssl, $separator);
        $alt = 'Gravatar ' . $service->getGravatarHash($email);

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<html><head><title></title></head><body>' . $gravatarImageTag . '</body></html>');
        libxml_use_internal_errors(false);
        $nodes = $dom->getElementsByTagName('img');
        $this->assertEquals(1, $nodes->length);
        $image = $nodes->item(0);

        $this->assertTrue($image->hasAttribute('src'), 'Image has no attribute "href"');
        $this->assertTrue($image->hasAttribute('alt'), 'Image has no alt');

        foreach ($params as $param => $value) {
            $this->assertTrue($image->hasAttribute($param), 'Image has no attribute "' . $param . '"');
            $this->assertEquals($value, $image->getAttribute($param), 'Image attribute "' . $param . '" has wrong value');
        }

        $srcAttribute = $image->getAttribute('src');
        $this->assertEquals($src, $srcAttribute, 'Image attribute "src" has wrong value');

        if (isset($params['alt'])) {
            $altAttribute = $image->getAttribute('alt');
            $this->assertEquals($params['alt'], $altAttribute, 'Image attribute "alt" has wrong value');
        } else {
            $altAttribute = $image->getAttribute('alt');
            $this->assertEquals($alt, $altAttribute, 'Image attribute "alt" has wrong value');
        }
    }
}