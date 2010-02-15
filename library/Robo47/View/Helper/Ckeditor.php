<?php
/**
 * Robo47 Components
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://robo47.net/licenses/new-bsd-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to robo47[at]robo47[dot]net so I can send you a copy immediately.
 *
 * @category   Robo47
 * @package    Robo47
 * @copyright  Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license    http://robo47.net/licenses/new-bsd-license New BSD License
 */

/**
 * Robo47_View_Helper_Ckeditor
 *
 * View Helper for integrating an textarea with CKEditor,
 * it does NOT load CKEditor
 *
 * @package     Robo47
 * @subpackage  View
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @uses        Zend_View_Helper_HeadScript
 * @uses        Zend_view_Helper_FormTextarea
 */
class Robo47_View_Helper_Ckeditor extends Zend_View_Helper_FormTextarea
{
    /**
     * Option to use jquerys $(document).ready; via the Headscript-View-Helper
     */
    const INIT_MODE_JQUERY = 'jquery';

    /**
     * Option to use <script>-tags directly behind textarea
     */
    const INIT_MODE_SCRIPT = 'script';

    /**
     * Default options
     *
     * @var array
     */
    protected static $_defaultOptions = array();

    /**
     * InitMode
     *
     * @var string
     */
    protected $_initMode = 'script';

    /**
     * Placement
     *
     * @var string
     */
    protected $_placement = 'append';

    /**
     * Eitor Options
     *
     * @var string
     */
    protected $_editorOptions = '';

    /**
     * @param array|Zend_Config $options
     */
    public function  __construct($options = array())
    {
        $this->setOptions(self::$_defaultOptions);
        $this->setOptions($options);
    }

    /**
     * Set Options
     *
     * @param array|Zend_Config $options
     * @return
     */
    public function setOptions($options)
    {
        foreach ($options as $option => $value) {
            switch($option) {
                case 'initMode':
                    $this->setInitMode($value);
                    break;
                case 'placement':
                    $this->setPlacement($value);
                    break;
                case 'editorOptions':
                    $this->setEditorOptions($value);
                    break;
                default:
                    break;
            }
        }
        return $this;
    }

    /**
     * Get Options
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            'initMode' => $this->getInitMode(),
            'placement' => $this->getPlacement(),
            'editorOptions' => $this->getEditorOptions(),
        );
    }

    /**
     * Get InitMode
     *
     * @return string
     */
    public function getInitMode()
    {
        return $this->_initMode;
    }

    /**
     * Get Placement
     *
     * @return string
     */
    public function getPlacement()
    {
        return $this->_placement;
    }

    /**
     * Get Editor Options
     *
     * @return string
     */
    public function getEditorOptions()
    {
        return $this->_editorOptions;
    }

    /**
     * Set initMode
     *
     * @param string $initMode
     * @return Robo47_View_Helper_Ckeditor *Provides Fluent Interface*
     */
    public function setInitMode($initMode)
    {
        $initMode = strtolower($initMode);
        switch ($initMode) {
            case self::INIT_MODE_SCRIPT:
            case self::INIT_MODE_JQUERY:
                $this->_initMode = $initMode;
                break;
            default:
                $message = 'Invalid initMode: ' . $initMode;
                throw new Robo47_View_Helper_Exception($message);
        }
        return $this;
    }

    /**
     * Set Placement
     *
     * @param string $placement
     * @return Robo47_View_Helper_Ckeditor *Provides Fluent Interface*
     */
    public function setPlacement($placement)
    {
        $placement = strtolower($placement);
        switch ($placement) {
            case 'append':
            case 'prepend':
                $this->_placement = $placement;
                break;
            default:
                $message = 'Invalid placement: ' . $placement;
                throw new Robo47_View_Helper_Exception($message);
        }
        return $this;
    }

    /**
     * Set Editor Options
     *
     * @param string $options
     * @return Robo47_View_Helper_Ckeditor *Provides Fluent Interface*
     */
    public function setEditorOptions($options)
    {
        $this->_editorOptions = $options;
        return $this;
    }

    /**
     * Set default Options
     *
     * @param array|Zend_Config $options
     */
    public static function setDefaultOptions($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        self::$_defaultOptions = $options;
    }

    /**
     * Generates a 'textarea' element and adds Ckeditor
     *
     * @param string|array $name
     * @param mixed $value The element value.
     * @param array $attribs Attributes for the element tag.
     * @return string The element XHTML
     * @throws Robo47_View_Helper_Exception
     */
    public function ckeditor($name, $value = null, $attribs = null)
    {
        if (!isset($attribs['id'])) {
            $message = 'Cant create CDKEditor for textarea without an id';
            throw new Robo47_View_Helper_Exception($message);
        }
        $textArea = parent::formTextarea($name, $value, $attribs);

        $ckeditorOptions = $this->getEditorOptions();
        $id = $attribs['id'];
        if (!empty($ckeditorOptions)) {
            $ckeditorCode = "CKEDITOR.replace('{$id}', {$ckeditorOptions});";
        } else {
            $ckeditorCode = "CKEDITOR.replace('{$id}');";
        }

        $initMode = $this->getInitMode();

        switch($initMode) {
            case self::INIT_MODE_SCRIPT:
                $code = '<script type="text/javascript">' . PHP_EOL .
                        '//<![CDATA[' . PHP_EOL .
                        $ckeditorCode . PHP_EOL .
                        '//]]>' . PHP_EOL .
                        '</script>';
                $returnCode = $textArea . $code;
                break;
            case self::INIT_MODE_JQUERY:
                $headScript = $this->view->HeadScript();
                /* @var $headScript Zend_View_Helper_HeadScript */
                $code = '$(document).ready( function() { ' . PHP_EOL .
                        $ckeditorCode . PHP_EOL .
                        '});' . PHP_EOL;
                $placement = $this->getPlacement();
                $headScript->headScript('script', $code, $placement);
                $returnCode = $textArea;
                break;
        }
        return $returnCode;
    }

    /**
     * Set default Option
     *
     * @param string $option
     * @param mixed $value
     */
    public static function setDefaultOption($option, $value)
    {
        self::$_defaultOptions[$option] = $value;
    }

    /**
     * Get default Option
     *
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public static function getDefaultOption($option, $default = null)
    {
        if (isset(self::$_defaultOptions[$option])) {
            return self::$_defaultOptions[$option];
        } else {
            return $default;
        }
    }

    /**
     * Get default options
     *
     * @return array
     */
    public static function getDefaultOptions()
    {
        return self::$_defaultOptions;
    }

    /**
     * Clears default options
     */
    public static function unsetDefaultOptions()
    {
        self::$_defaultOptions = array();
    }
}
