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
 * Robo47_View_Helper_Globals
 *
 * @package     Robo47
 * @subpackage  View
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_View_Helper_Globals extends Zend_View_Helper_Abstract
{

    /**
     *
     * Escaping is only used on strings! not on arrays !
     *
     * @param string $global
     * @param string $name
     * @param mixed $default
     * @param bool $escape
     * @return Robo47_View_Helper_Globals|mixed
     */
    public function Globals($global, $name = null, $default = null,
        $escape = true)
    {
        $value = $this->_checkGlobal($global, $name, $default);

        if (true === $escape) {
            if (is_string($value)) {
                $value = $this->view->escape($value);
            }
        }

        return $value;
    }

    /**
     *
     * @param string $global
     * @param string|null $name
     * @param mixed $default
     */
    protected function _checkGlobal($global, $name, $default)
    {
        $lglobal = strtolower($global);
        switch ($lglobal) {
            case 'server':
            case 'env':
            case 'post':
            case 'get':
            case 'cookie':
            case 'session':
                return $this->_getGlobal(
                        '_' . strtoupper($global), $name, $default
                );
                break;
            default:
                $message = 'Unknown global "' . $global . '"';
                throw new Robo47_View_Helper_Exception($message);
        }
    }

    /**
     *
     * @param string $global
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function _getGlobal($global, $name = null, $default = null)
    {
        if (!isset($GLOBALS[$global])) {
            return $default;
        }
        if (null === $name) {
            return $GLOBALS[$global];
        }
        if (isset($GLOBALS[$global][$name])) {
            return $GLOBALS[$global][$name];
        } else {
            return $default;
        }
    }

}
