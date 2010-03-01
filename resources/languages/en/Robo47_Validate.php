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
 * @package    Robo47_Validate
 * @subpackage Ressource
 * @copyright  Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license    http://robo47.net/licenses/new-bsd-license New BSD License
 */

return array (
    // Robo47_Validate_Uri
    "URI '%value%' is not valid: '%message%'" => "URI '%value%' is not valid: '%message%'",

    // Robo47_Validate_StringNotContains
    "'%value%' does contain '%contains%'" => "'%value%' does contain '%contains%'",

    // Robo47_Validate_StringContains
    "'%value%' does not contain any of the specified strings" => "'%value%' does not contain any of the specified strings",

    // Robo47_Validate_MaxLineLength
    "Line %value% is too long" => "Line %value% is too long",

    // Robo47_Validate_Doctrine_Record(Not)Exists
    // Same as Zend_Validate_Db_Record(Not)Exists
    'No record matching %value% was found' => 'No record matching %value% was found',
    "A record matching %value% was found" => "A record matching %value% was found",
);