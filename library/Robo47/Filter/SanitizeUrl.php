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
 * Robo47_Filter_SanitizeUrl
 *
 * Filter for sanitizing strings for use in urls (limited to german umlaute)
 *
 * @package     Robo47
 * @subpackage  Filter
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Filter_SanitizeUrl implements Zend_Filter_Interface
{
    /**
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        // all signs to replace
        $search = array('ä' => 'ae',
                        'ö' => 'oe',
                        'ü' => 'ue',
                        'ß' => 's',
                        'Ä' => 'Ae',
                        'Ö' => 'Oe',
                        'Ü' => 'Ue',
                        'ß' => 's',
                        ' ' => '-');

        // replace them
        $value = str_replace(array_keys($search), $search, $value);

        // remove everything which is not a-z 0-9 or - or .
        $value = preg_replace('~[^a-z0-9\.\-_]~i', '-', $value);

        // remove double -
        $value = preg_replace('~(-)+~', '-', $value);

        // remove trailing -
        return trim($value, '-');
    }
}
