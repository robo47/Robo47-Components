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
 * Robo47_Log_Filter_Category
 *
 * @package     Robo47
 * @subpackage  Log
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Log_Filter_Category extends Robo47_Log_Filter_ValidateProxy
{

    /**
     *
     * @param array $categories
     * @param boolean $not
     */
    public function __construct(array $categories = array(), $not = false)
    {
        parent::__construct(
            new Zend_Validate_InArray($categories), 'category', $not
        );
    }

    /**
     * Get Categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->_validator->getHaystack();
    }

    /**
     * Set Categories
     *
     * @param array $categories
     * @return Robo47_Log_Filter_Category *Provides Fluent Interface*
     */
    public function setCategories(array $categories = array())
    {
        $this->_validator->setHayStack($categories);

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options['categories'] = $this->getCategories();

        return $options;
    }

    /**
     * Construct a Zend_Log driver
     *
     * @param  array|Zend_Config $config
     * @return Robo47_Log_Filter_Category
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(
            array(
            'categories' => array(),
            'not' => false,
            ), $config
        );

        $filter = new Robo47_Log_Filter_Category(
            $config['categories'],
            $config['not']
        );

        return $filter;
    }

}
