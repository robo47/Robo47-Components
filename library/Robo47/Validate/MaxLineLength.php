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
 * Robo47_Validate_MaxLineLength
 *
 * @package     Robo47
 * @subpackage  Validate
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Validate_MaxLineLength extends Zend_Validate_Abstract
{
    /**
     * Line too long
     */

    const LINE_TOO_LONG = 'lineToLong';

    protected $_messageTemplates = array(
        self::LINE_TOO_LONG => "Line %value% is too long"
    );

    /**
     * Allowed LineLength
     *
     * @var integer
     */
    protected $_lineLength = 80;

    /**
     * Used encoding
     *
     * @var string
     */
    protected $_encoding = 'utf-8';

    /**
     * @param integer $lineLength
     * @param string $encoding
     */
    public function __construct($lineLength = 80, $encoding = 'utf-8')
    {
        $this->setLineLength($lineLength);
        $this->setEncoding($encoding);
    }

    /**
     * Set Encoding
     *
     * @param string $encoding
     * @return Robo47_Validate_LineLength *Provides Fluent Interface*
     */
    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;

        return $this;
    }

    /**
     * Get Encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Set LineLength
     *
     * @param integer $lineLength
     * @return Robo47_Validate_LineLength *Provides Fluent Interface*
     */
    public function setLineLength($lineLength)
    {
        $lineLength = (int) $lineLength;
        if ($lineLength < 1) {
            $message = 'lineLength is less than 1';
            throw new Robo47_Validate_Exception($message);
        } else {
            $this->_lineLength = $lineLength;
        }

        return $this;
    }

    /**
     * Get LineLength
     *
     * @return integer
     */
    public function getLineLength()
    {
        return $this->_lineLength;
    }

    /**
     * Is Valid
     *
     * @see Zend_Validate_Interface::isValid
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        // replace other line-endings
        $value = str_replace("\n\r", "\n", $value);
        $value = str_replace("\r\n", "\n", $value);
        // create array
        $asArray = explode("\n", $value);

        foreach ($asArray as $linenumber => $line) {
            if (mb_strwidth($line, $this->_encoding) > $this->_lineLength) {
                $this->_error(self::LINE_TOO_LONG, $linenumber + 1);

                return false;
            }
        }

        return true;
    }

}
