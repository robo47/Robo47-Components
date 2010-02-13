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
 * Robo47_Form_Decorator_Info
 *
 * @package     Robo47
 * @subpackage  Form
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Form_Decorator_Info extends Zend_Form_Decorator_Abstract
{
    /**
     * @var string
     */
    protected $_placement = self::PREPEND;

    /**
     * @var string
     */
    protected $_info = '';

    /**
     *
     * @param string $placement
     * @return Robo47_Form_Decorator_Info *Provides Fluent Interface*
     */
    public function setPlacement($placement)
    {
        $this->_placement = strtoupper($placement);
        return $this;
    }

    /**
     *
     * @param string $info
     * @return Robo47_Form_Decorator_Info *Provides Fluent Interface*
     */
    public function setInfo($info)
    {
        $this->_info = $info;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->_info;
    }

    /**
     *
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();

        $info = $this->getInfo();

        switch($placement) {
            case self::PREPEND:
                $return = $info . $separator . $content;
                break;
            case self::APPEND:
            default:
                $return = $content . $separator . $info;
                break;
        }
        return $return;
    }
}
