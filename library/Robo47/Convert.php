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
 * Robo47_Convert
 *
 * Class for converting shorthand-values to bytes
 *
 * @package     Robo47
 * @subpackage  Convert
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Convert
{

    /**
     * Converts shorthand value to bytes
     *
     * Shorthand values are used for example in the php.ini
     * @see http://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
     *
     * @return integer upload_max_filesize as integer in bytes
     * @throws Robo47_Convert_Exception
     */
    public static function shortHandToBytes($value)
    {
        if (is_numeric($value)) {
            if ($value >= PHP_INT_MAX) {
                $message = 'input is greater than PHP_INT_MAX on this ' .
                    'plattform (' . PHP_INT_MAX . ')';
                throw new Robo47_Convert_Exception($message);
            }
            return (int)$value;
        } else {
            $lastSign = strtolower($value{strlen($value) - 1});
            $valueBytes = (int)mb_substr($value, 0, strlen($value) - 1);
            switch ($lastSign) {
                case 'k':
                    $valueBytes *= 1024;
                    break;
                case 'm':
                    $valueBytes *= 1024 * 1024;
                    break;
                case 'g':
                    $valueBytes *= 1024 * 1024 * 1024;
                    break;
                default:
                    $message = 'invalid last sign in ' . $value;
                    throw new Robo47_Convert_Exception($message);
                    break;
            }
            if ($valueBytes >= PHP_INT_MAX) {
                $message = 'input is greater than PHP_INT_MAX on this ' .
                    'plattform (' . PHP_INT_MAX . ')';
                throw new Robo47_Convert_Exception($message);
            }
            return $valueBytes;
        }
    }
}