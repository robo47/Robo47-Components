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
 * Robo47_View_Helper_Gravatar
 *
 * @package     Robo47
 * @subpackage  View
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @todo        Use Validation, use fallback-url
 */
class Robo47_View_Helper_Gravatar extends Zend_View_Helper_HtmlElement
{

    /**
     * @var Robo47_Service_Gravatar
     */
    protected $_service = null;

    /**
     * Constructor
     *
     * @param Zend_Cache_Core $cache
     * @param string $defaultAlt
     * @param Robo47_Service_Gravatar $service
     */
    public function __construct(Robo47_Service_Gravatar $service = null)
    {
        $this->setService($service);
    }

    /**
     * Sets Service Instance
     *
     * @param Robo47_Service_Gravatar $service
     * @return Robo47_View_Helper_Gravatar *Provides Fluent Interface*
     */
    public function setService(Robo47_Service_Gravatar $service = null)
    {
        if (null === $service) {
            $service = new Robo47_Service_Gravatar();
        }
        $this->_service = $service;
        return $this;
    }

    /**
     * Returns Service Instance
     *
     * @return Robo47_Service_Gravatar
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     * Returns Gravatar-Image-tag
     *
     * @param string $email
     * @param array $params
     * @return string
     */
    public function Gravatar($email, $size = null, $rating = null,
        $default = null, $ssl = null, $separator = '&amp;',
        array $params = array())
    {
        $params['src'] = $this->_service->getUri(
            $email,
            $size,
            $rating,
            $default,
            $ssl,
            $separator
        );
        if (!isset($params['alt'])) {
            $params['alt'] = 'Gravatar ' .
                $this->_service->getGravatarHash($email);
        }
        $image = '<img ';
        $image .= $this->_htmlAttribs($params);
        $image .= $this->getClosingBracket();
        return $image;
    }
}
