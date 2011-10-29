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
 * Robo47_Validate_StringNotContains
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Validate_StringNotContains extends Robo47_Validate_StringContains
{

    protected $_messageTemplates = array(
        self::CONTAINS => "'%value%' does contain '%contains%'"
    );

    /**
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        $isValid = true;
        foreach ($this->_contains as $contains) {
            if (false !== strpos($value, $contains)) {
                $isValid = false;
                $this->_createMessage(self::CONTAINS, $contains);
                break;
            }
        }
        return $isValid;
    }
}