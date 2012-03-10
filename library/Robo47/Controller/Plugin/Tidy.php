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
 * Robo47_Controller_Plugin_Tidy
 *
 * Plugin which cleans html output
 *
 * @package     Robo47
 * @subpackage  Controller
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 * @uses        Zend_View_Helper_HeadTitle
 */
class Robo47_Controller_Plugin_Tidy extends Zend_Controller_Plugin_Abstract
{

    /**
     * Tidy Filter
     *
     * @var Tidy
     */
    protected $_tidyFilter = null;

    /**
     *
     * @param Robo47_Filter_Tidy $tidyFilter
     * @param Zend_Log           $log
     * @param integer            $logPriority
     * @param string             $logCategory
     */
    public function __construct(Robo47_Filter_Tidy $tidyFilter = null,
        Zend_Log $log = null, $logPriority = Zend_Log::INFO,
        $logCategory = 'tidy')
    {
        $this->setTidyFilter($tidyFilter);
        $this->setLog($log);
        $this->setLogPriority($logPriority);
        $this->setLogCategory($logCategory);
    }

    /**
     * Set Tidy Filter
     *
     * @param Robo47_Filter_Tidy $tidyFilter
     * @return Robo47_Controller_Plugin_Tidy *Provides Fluent Interface*
     */
    public function setTidyFilter(Robo47_Filter_Tidy $tidyFilter = null)
    {
        if (null === $tidyFilter) {
            $tidyFilter = new Robo47_Filter_Tidy();
        }
        $this->_tidyFilter = $tidyFilter;

        return $this;
    }

    /**
     * Get Tidy Filter
     *
     * @return Robo47_Filter_Tidy
     */
    public function getTidyFilter()
    {
        return $this->_tidyFilter;
    }

    /**
     * Set Log
     *
     * @param Zend_Log $log
     * @return Robo47_Controller_Plugin_Tidy *Provides Fluent Interface*
     */
    public function setLog(Zend_Log $log = null)
    {
        $this->_log = $log;

        return $this;
    }

    /**
     * Get Log
     *
     * @return Robo47_Log
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * Set Log Category
     *
     * @param string $category
     * @return Robo47_Controller_Plugin_Tidy *Provides Fluent Interface*
     */
    public function setLogCategory($logCategory)
    {
        $this->_logCategory = $logCategory;

        return $this;
    }

    /**
     * Get Log Category
     *
     * @return string
     */
    public function getLogCategory()
    {
        return $this->_logCategory;
    }

    /**
     * Set Log Category
     *
     * @param integer $logPriority
     * @return Robo47_Controller_Plugin_Tidy *Provides Fluent Interface*
     */
    public function setLogPriority($logPriority)
    {
        $this->_logPriority = (int) $logPriority;

        return $this;
    }

    /**
     * Get Log Category
     *
     * @return integer
     */
    public function getLogPriority()
    {
        return $this->_logPriority;
    }

    /**
     * Is a Html Response
     *
     * Checks if it finds a Content-Type-header with the value text/html
     *
     * @param Zend_Controller_Response_Abstract $response
     * @return bool
     */
    public function isHtmlResponse(Zend_Controller_Response_Abstract $response)
    {
        foreach ($response->getHeaders() as $value) {
            if ('content-type' == trim(strtolower($value['name'])) &&
                false !== strpos(strtolower($value['value']), 'text/html')) {
                return true;
            }
        }

        foreach ($response->getRawHeaders() as $value) {
            $regex = preg_quote('content-type: text/html', '/');
            if (preg_match('/' . $regex . '/i', $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    public function dispatchLoopShutdown()
    {
        $response = $this->getResponse();
        // only tidy content recognized als text/html
        if ($this->isHtmlResponse($response)) {
            $tidyFilter = $this->getTidyFilter();
            // filter body and set it as new body again
            $response->setBody($tidyFilter->filter($response->getBody()));
            if (null !== $this->_log) {
                $tidy = $tidyFilter->getTidy();
                $tidy->diagnose();
                // log only if errors / warnings found
                $pos = strpos(
                    $tidy->errorBuffer, 'No warnings or errors were found'
                );
                if (false === $pos) {
                    $this->_log->log(
                        'Url: ' . $this->getRequest()->getRequestUri() .
                        PHP_EOL . $tidy->errorBuffer, $this->getLogPriority(),
                        array('category' => $this->getLogCategory())
                    );
                }
            }
        }
    }

}
