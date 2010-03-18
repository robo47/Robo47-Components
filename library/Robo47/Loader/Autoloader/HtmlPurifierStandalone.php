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
 * Autoloader for the HTMLPurifier-Library in the standalone-version
 *
 * The standalone-version packs almost all needed classes into one file
 *
 * @package     Robo47
 * @subpackage  Loader
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Loader_Autoloader_HtmlPurifierStandalone
implements Zend_Loader_Autoloader_Interface
{

    /**
     * @var boolean
     */
    protected $_loaded = false;

    /**
     * Loads Purifier
     */
    public function __construct()
    {
        if (!class_exists('HTMLPurifier', false)) {
            require_once 'HTMLPurifier.standalone.php';
        }
    }

    /**
     * Autoload-Method
     *
     * @param string $class name of the class
     * @return boolean
     */
    public function autoload($class)
    {
        return HTMLPurifier_Bootstrap::autoload($class);
    }
}