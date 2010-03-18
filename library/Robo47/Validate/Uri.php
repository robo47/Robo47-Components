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
 * Robo47_Validate_Uri
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @uses        Zend_Uri
 */
class Robo47_Validate_Uri extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value is not a valid url
     */
    const NO_VALID_URI = 'noValidUri';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NO_VALID_URI => "URI '%value%' is not valid: '%message%'"
    );
    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = array(
        'message' => '_message',
        'value' => '_value'
    );
    /**
     * @var string
     */
    protected $_message = '';

    /**
     * @param mixed $value
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        try {
            $uri = Zend_Uri::factory($value);
        } catch (Exception $e) {
            $this->_message = $e->getMessage();
            $this->_error(self::NO_VALID_URI, $value);
            return false;
        }
        return $uri->valid();
    }
}