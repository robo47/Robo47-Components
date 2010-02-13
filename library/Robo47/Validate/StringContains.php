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
 * Robo47_Validate_StringContains
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Validate_StringContains extends Zend_Validate_Abstract
{
    const CONTAINS      = 'contains';

    /**
     * @var string
     */
    protected $_contains = '';

    protected $_messageTemplates = array(
        self::CONTAINS =>
            "'%value%' does not contain any of the specified strings",
    );

    protected $_messageVariables = array(
        'value' => '_value',
    );

    /**
     * @param string|array $contains
     */
    public function __construct($contains)
    {
        $this->setContains($contains);
    }

    /**
     * Set Contains
     *
     * @param string|array $contains
     * @return Robo47_Validate_StringContains *Provides Fluent Interface*
     */
    public function setContains($contains)
    {
        if (is_string($contains)) {
            $contains = array($contains);
        }
        if (empty($contains)) {
            $message = '$contains is empty';
            throw new Robo47_Validate_Exception($message);
        }
        $this->_contains = $contains;
        return $this;
    }

    /**
     * Get Contains
     *
     * @return array
     */
    public function getContains()
    {
        return $this->_contains;
    }

    /**
     * Is Valid
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $isValid = false;
        foreach ($this->_contains as $contains) {
            if (false !== strpos($value, $contains)) {
                $isValid = true;
                $this->_error(self::CONTAINS);
                break;
            }
        }
        return $isValid;
    }
}
