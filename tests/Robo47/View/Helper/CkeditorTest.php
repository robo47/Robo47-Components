<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_View
 * @group Robo47_View_Helper
 * @group Robo47_View_Helper_Ckeditor
 */
class Robo47_View_Helper_CkeditorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_View_Helper_Ckeditor
     */
    protected $_helper = null;
    /**
     * @var Zend_View
     */
    protected $_view = null;
    
    public function setUp()
    {
        $this->_helper = new Robo47_View_Helper_Ckeditor();
        $this->_helper->setView($this->getView());
    }
    
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = new Zend_View();
            // doctype needed because of usage of cdata in HeadScript-Helper
            $this->_view->doctype('XHTML1_STRICT');
        }
        return $this->_view;
    }
    
    public function tearDown()
    {
        $this->_helper = null;
        $this->_view = null;
        Robo47_View_Helper_Ckeditor::unsetDefaultOptions();
        Zend_Registry::_unsetInstance();
// View-Helpers use the registry!
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setDefaultOptions
     * @covers Robo47_View_Helper_Ckeditor::getDefaultOptions
     */
    public function testSetDefaultOptionsGetDefaultOptions()
    {
        $this->assertEquals(0, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));
        Robo47_View_Helper_Ckeditor::setDefaultOptions(array('foo' => 'bar',
                'bar2' => 'foo2'));
        $options = Robo47_View_Helper_Ckeditor::getDefaultOptions();
        $this->assertArrayHasKey('foo', $options);
        $this->assertArrayHasKey('bar2', $options);

        $this->assertEquals('bar', $options['foo']);
        $this->assertEquals('foo2', $options['bar2']);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setDefaultOptions
     */
    public function testSetDefaultOptionsWithZendConfig()
    {
        $this->assertEquals(0, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));
        $config = new Zend_Config(array('foo' => 'bar', 'bar2' => 'foo2'));
        Robo47_View_Helper_Ckeditor::setDefaultOptions($config);
        $options = Robo47_View_Helper_Ckeditor::getDefaultOptions();
        $this->assertArrayHasKey('foo', $options);
        $this->assertArrayHasKey('bar2', $options);

        $this->assertEquals('bar', $options['foo']);
        $this->assertEquals('foo2', $options['bar2']);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setDefaultOption
     * @covers Robo47_View_Helper_Ckeditor::getDefaultOption
     */
    public function testSetDefaultOptionGetDefaultOption()
    {
        Robo47_View_Helper_Ckeditor::setDefaultOption('foo', 'bar');

        $this->assertEquals(1, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));

        $this->assertEquals('bar', Robo47_View_Helper_Ckeditor::getDefaultOption('foo'));

        Robo47_View_Helper_Ckeditor::setDefaultOption('bar2', 'foo2');

        $this->assertEquals(2, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));

        $this->assertEquals('foo2', Robo47_View_Helper_Ckeditor::getDefaultOption('bar2'));

        $this->assertNull(Robo47_View_Helper_Ckeditor::getDefaultOption('nonExisting'));
        $this->assertNull(Robo47_View_Helper_Ckeditor::getDefaultOption('nonExisting', null));
        $this->assertFalse(Robo47_View_Helper_Ckeditor::getDefaultOption('nonExisting', false));
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::unsetDefaultOptions
     */
    public function testUnsetDefaultOptions()
    {
        Robo47_View_Helper_Ckeditor::setDefaultOption('foo', 'bar');
        Robo47_View_Helper_Ckeditor::setDefaultOption('bar2', 'foo2');
        $this->assertEquals(2, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));
        Robo47_View_Helper_Ckeditor::unsetDefaultOptions();
        $this->assertEquals(0, count(Robo47_View_Helper_Ckeditor::getDefaultOptions()));
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor
     */
    public function testDefaultConstructor()
    {
        $this->_helper = new Robo47_View_Helper_Ckeditor();

        $this->assertEquals('script', $this->_helper->getInitMode());
        $this->assertEquals('append', $this->_helper->getPlacement());
        $this->assertEquals('', $this->_helper->getEditorOptions());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::__construct
     */
    public function testConstructorWithOptions()
    {
        $options = array(
            'initMode' => 'jquery',
            'placement' => 'PREPEND',
            'editorOptions' => 'FOO'
        );

        $this->_helper = new Robo47_View_Helper_Ckeditor($options);

        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->assertEquals('FOO', $this->_helper->getEditorOptions());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::__construct
     */
    public function testConstructorWithDefaultOptions()
    {
        $options = array(
            'initMode' => 'jquery',
            'placement' => 'PREPEND',
            'editorOptions' => 'FOO'
        );

        Robo47_View_Helper_Ckeditor::setDefaultOptions($options);

        $this->_helper = new Robo47_View_Helper_Ckeditor();

        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->assertEquals('FOO', $this->_helper->getEditorOptions());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::__construct
     */
    public function testConstructorWithDefaultOptionsAndOptions()
    {
        $options = array(
            'initMode' => 'jquery',
            'placement' => 'PREPEND',
            'editorOptions' => 'FOO'
        );

        $options2 = array(
            'initMode' => 'script',
            'placement' => 'append',
            'editorOptions' => 'BLUB'
        );

        Robo47_View_Helper_Ckeditor::setDefaultOptions($options2);

        $this->_helper = new Robo47_View_Helper_Ckeditor($options);

        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->assertEquals('FOO', $this->_helper->getEditorOptions());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::ckeditor
     * @covers Robo47_View_Helper_Exception
     */
    public function testCkeditorExceptionWithoutId()
    {
        try {
            $this->_helper->ckeditor('foo', 'baa', null, array());
            $this->fail('no exception thrown without id');
        } catch (Robo47_View_Helper_Exception $e) {
            $this->assertEquals('Cant create CDKEditor for textarea without an id', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setInitMode
     * @covers Robo47_View_Helper_Exception
     */
    public function testExceptionOnSetInitModeWithInvalidInitMode()
    {
        try {
            $this->_helper->setInitMode('foo');
            $this->fail('no exception on invalid initMode');
        } catch (Robo47_View_Helper_Exception $e) {
            $this->assertEquals('Invalid initMode: foo', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setPlacement
     * @covers Robo47_View_Helper_Exception
     */
    public function testExceptionOnSetPlacementWithInvalidPlacement()
    {
        try {
            $this->_helper->setPlacement('foo');
            $this->fail('no exception on invalid placement');
        } catch (Robo47_View_Helper_Exception $e) {
            $this->assertEquals('Invalid placement: foo', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::ckeditor
     */
    public function testCkeditorWithInitModeScriptAndNoCKEditorOptions()
    {
        $options = array(
            'initMode' => 'script',
            'placement' => 'append',
            'editorOptions' => ''
        );

        $this->_helper->setOptions($options);

        $view = $this->getView();

        $textarea = $view->formTextarea('foo', 'baa', array('id' => 'foobaa'));

        $code = $this->_helper->ckeditor('foo', 'baa', array('id' => 'foobaa'), array());

        $expected = $textarea .
            '<script type="text/javascript">' . PHP_EOL .
            '//<![CDATA[' . PHP_EOL .
            'CKEDITOR.replace(\'foobaa\');' . PHP_EOL .
            '//]]>' . PHP_EOL .
            '</script>';

        $this->assertEquals($expected, $code);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::ckeditor
     */
    public function testCkeditorWithInitModeScriptAndCKEditorOptions()
    {
        $options = array(
            'initMode' => 'script',
            'placement' => 'append',
            'editorOptions' => '{ SomeOptions: foo, }'
        );

        $this->_helper->setOptions($options);

        $view = $this->getView();

        $textarea = $view->formTextarea('foo', 'baa', array('id' => 'foobaa'));

        $code = $this->_helper->ckeditor('foo', 'baa', array('id' => 'foobaa'), array());

        $expected = $textarea .
            '<script type="text/javascript">' . PHP_EOL .
            '//<![CDATA[' . PHP_EOL .
            'CKEDITOR.replace(\'foobaa\', { SomeOptions: foo, });' . PHP_EOL .
            '//]]>' . PHP_EOL .
            '</script>';

        $this->assertEquals($expected, $code);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::ckeditor
     */
    public function testCkeditorWithInitModeJQueryAndCKEditorOptions()
    {

        $options = array(
            'initMode' => 'jquery',
            'placement' => 'append',
            'editorOptions' => '{ SomeOptions: foo, }'
        );

        $this->_helper->setOptions($options);

        $view = $this->getView();

        $textarea = $view->formTextarea('foo', 'baa', array('id' => 'foobaa'));

        $code = $this->_helper->ckeditor('foo', 'baa', array('id' => 'foobaa'), array());

        $expected = $textarea;

        $this->assertEquals($expected, $code, 'Generated textarea missmatch');

        $code = '<script type="text/javascript">' . PHP_EOL .
            '    //<![CDATA[' . PHP_EOL .
            '$(document).ready( function() { ' . PHP_EOL .
            'CKEDITOR.replace(\'foobaa\', { SomeOptions: foo, });' . PHP_EOL .
            '});' . PHP_EOL .
            '    //]]>' . PHP_EOL .
            '</script>';

        $this->assertEquals($code, (string) $view->HeadScript(), 'wrong code in headscript');
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::ckeditor
     */
    public function testCkeditorWithInitModeJQueryAndNoCKEditorOptions()
    {

        $options = array(
            'initMode' => 'jquery',
            'placement' => 'append',
            'editorOptions' => ''
        );

        $this->_helper->setOptions($options);

        $view = $this->getView();

        $textarea = $view->formTextarea('foo', 'baa', array('id' => 'foobaa'));

        $code = $this->_helper->ckeditor('foo', 'baa', array('id' => 'foobaa'), array());

        $expected = $textarea;

        $this->assertEquals($expected, $code);

        $code = '<script type="text/javascript">' . PHP_EOL .
            '    //<![CDATA[' . PHP_EOL .
            '$(document).ready( function() { ' . PHP_EOL .
            'CKEDITOR.replace(\'foobaa\');' . PHP_EOL .
            '});' . PHP_EOL .
            '    //]]>' . PHP_EOL .
            '</script>';

        $this->assertEquals($code, (string) $view->HeadScript());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setOptions
     * @covers Robo47_View_Helper_Ckeditor::getOptions
     */
    public function testSetOptionsGetOptions()
    {
        $options = array(
            'initMode' => 'jquery',
            'placement' => 'PREPEND',
            'editorOptions' => 'FOO',
            'foo' => 'baa',
        );

        $this->_helper->setOptions($options);

        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->assertEquals('FOO', $this->_helper->getEditorOptions());

        $getOptions = $this->_helper->getOptions();

        $this->assertEquals(3, count($getOptions), 'Wrong number of elements in array from getOptions()');
        $this->assertArrayHasKey('initMode', $getOptions, 'Options-array misses initMode');
        $this->assertArrayHasKey('editorOptions', $getOptions, 'Options-array misses editorOptions');
        $this->assertArrayHasKey('placement', $getOptions, 'Options-array misses placement');

        $this->assertEquals('jquery', $getOptions['initMode']);
        $this->assertEquals('prepend', $getOptions['placement']);
        $this->assertEquals('FOO', $getOptions['editorOptions']);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setOptions
     * @covers Robo47_View_Helper_Ckeditor::getOptions
     */
    public function testSetOptionsGetOptionsWithZendConfig()
    {
        $options = array(
            'initMode' => 'jquery',
            'placement' => 'PREPEND',
            'editorOptions' => 'FOO'
        );

        $config = new Zend_Config($options);

        $this->_helper->setOptions($config);

        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->assertEquals('FOO', $this->_helper->getEditorOptions());

        $getOptions = $this->_helper->getOptions();

        $this->assertEquals(3, count($getOptions), 'Wrong number of elements in array from getOptions()');
        $this->assertArrayHasKey('initMode', $getOptions, 'Options-array misses initMode');
        $this->assertArrayHasKey('editorOptions', $getOptions, 'Options-array misses editorOptions');
        $this->assertArrayHasKey('placement', $getOptions, 'Options-array misses placement');

        $this->assertEquals('jquery', $getOptions['initMode']);
        $this->assertEquals('prepend', $getOptions['placement']);
        $this->assertEquals('FOO', $getOptions['editorOptions']);
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setInitMode
     * @covers Robo47_View_Helper_Ckeditor::getInitMode
     */
    public function testSetInitModeGetInitMode()
    {
        $return = $this->_helper->setInitMode('jquery');
        $this->assertSame($this->_helper, $return, 'No Fluent Interface');
        $this->assertEquals('jquery', $this->_helper->getInitMode());
        $this->_helper->setInitMode('script');
        $this->assertEquals('script', $this->_helper->getInitMode());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setPlacement
     * @covers Robo47_View_Helper_Ckeditor::getPlacement
     */
    public function testSetPlacementGetPlacement()
    {
        $return = $this->_helper->setPlacement('prepend');
        $this->assertSame($this->_helper, $return, 'No Fluent Interface');
        $this->assertEquals('prepend', $this->_helper->getPlacement());
        $this->_helper->setPlacement('append');
        $this->assertEquals('append', $this->_helper->getPlacement());
    }

    /**
     * @covers Robo47_View_Helper_Ckeditor::setEditorOptions
     * @covers Robo47_View_Helper_Ckeditor::getEditorOptions
     */
    public function testSetEditorOptionsGetEditorOptions()
    {
        $return = $this->_helper->setEditorOptions('{foo, baa}');
        $this->assertSame($this->_helper, $return, 'No Fluent Interface');
        $this->assertEquals('{foo, baa}', $this->_helper->getEditorOptions());
        $this->_helper->setEditorOptions('{baa, foo}');
        $this->assertEquals('{baa, foo}', $this->_helper->getEditorOptions());
    }
}