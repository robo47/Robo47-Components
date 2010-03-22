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
 * Robo47_Doctrine_Hydartor_PopoDriver
 *
 * @package     Robo47
 * @subpackage  Doctrine
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @todo        Add possiblity to use another container [eg an ArrayObject too] ?
 */
class Robo47_Doctrine_Hydrator_PopoDriver extends Doctrine_Hydrator_ArrayDriver
{

    /**
     * Name of the variable containing the classtype
     *
     * If null, no type set
     *
     * @var string
     */
    protected static $_typename = '__type';

    /**
     * Name of the Popo-base-class
     *
     * Needs to implement ArrayAccess!
     *
     * @var string
     */
    protected static $_classname = 'Robo47_Popo';


    /**
     * Name of the Container-class
     *
     * Needs to implement ArrayAccess!
     *
     * @var string
     */
    protected static $_containerClassname = 'array';

    /**
     *
     * @param string $classname
     */
    public static function setDefaultClassname($classname)
    {
        if (!is_string($classname))  {
            $message = 'Invalid type for $classname: ' .
                Robo47_Core::getType($classname);
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        $instance = new $classname;
        if (!$instance instanceof ArrayAccess) {
            throw new Robo47_Doctrine_Hydrator_Exception('Type does not implement ArrayAccess: ' . Robo47_Core::getType($instance));
        }
        self::$_classname = $classname;
    }

    /**
     *
     * @param string $classname
     */
    public static function setDefaultContainerClassname($classname)
    {
        if (!is_string($classname))  {
            $message = 'Invalid type for $classname: ' .
                Robo47_Core::getType($classname);
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        if (strtolower($classname) != 'array') {
            $instance = new $classname;
            if (!$instance instanceof ArrayAccess) {
                throw new Robo47_Doctrine_Hydrator_Exception('Type does not implement ArrayAccess: ' . Robo47_Core::getType($instance));
            }
        }
        self::$_containerClassname = $classname;
    }

    /**
     *
     * @param string|null $typename
     */
    public static function setDefaultTypename($typename)
    {
        if(null !== $typename && !is_string($typename)) {
            $message = 'Invalid type for $typename: ' .
                Robo47_Core::getType($typename);
            throw new Robo47_Doctrine_Hydrator_Exception($message);
        }
        self::$_typename = $typename;
    }

    /**
     * @return string|null
     */
    public static function getDefaultContainerClassname()
    {
        return self::$_containerClassname;
    }

    /**
     * @return string
     */
    public static function getDefaultTypename()
    {
        return self::$_typename;
    }

    /**
     * @return string
     */
    public static function getDefaultClassname()
    {
        return self::$_classname;
    }

    /**
     *
     * @param array $data
     * @param string $component
     * @return Object|Robo47_Popo
     */
    public function getElement(array $data, $component)
    {
        $classname = self::getDefaultClassname();

        $popo = new $classname;

        foreach ($data as $key => $value) {
            $popo[$key] = $value;
        }

        $typename = self::getDefaultTypename();

        if (null !== $typename) {
            $popo[$typename] = $component;
        }

        return $popo;
    }

    /**
     *
     * @param string $component
     * @return array|ArrayObject
     */
    public function getElementCollection($component)
    {
        $containerClass = self::getDefaultContainerClassname();
        if ($containerClass == 'array') {
            $container = array();
        } else {
            $container = new $containerClass;
        }
        return $container;
    }
}