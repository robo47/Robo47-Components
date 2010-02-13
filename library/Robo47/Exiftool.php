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
 * Robo47_Exiftool
 *
 * Needs exiftool 7.77+
 *
 * @package     Robo47
 * @subpackage  Exiftool
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 *
 */
class Robo47_Exiftool extends Zend_Exception
{
    /**
     * Format ARRAY
     */
    const FORMAT_ARRAY = 'array';

    /**
     * Format XML
     */
    const FORMAT_XML = 'xml';

    /**
     * Format JSON
     */
    const FORMAT_JSON = 'json';

    /**
     *
     * @var string
     */
    protected $_format = null;

    /**
     * Path to the exiftool-binary
     *
     * @var string
     */
    protected $_exiftool = '/usr/bin/exiftool';

    /**
     *
     * @param string $exiftool
     */
    public function __construct($exiftool = '/usr/bin/exiftool',
                                $format = self::FORMAT_JSON)
    {
        $this->setExiftool($exiftool);
        $this->setFormat($format);
    }

    /**
     * Set Exiftool
     *
     * @param string $exiftool
     * @return Robo47_Exiftool *Provides Fluent Interface*
     */
    public function setExiftool($exiftool)
    {
        $this->_exiftool = $exiftool;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getExiftool()
    {
        return $this->_exiftool;
    }

    /**
     *
     * @param string $format
     * @return Robo47_Exiftool *Provides Fluent Interface*
     */
    public function setFormat($format)
    {
        $format = strtolower($format);
        switch($format) {
            case self::FORMAT_JSON:
            case self::FORMAT_ARRAY:
            case self::FORMAT_XML:
                break;
            default:
                $message = 'Invalid format: ' . $format;
                throw new Robo47_Exiftool_Exception($message);
        }
        $this->_format = $format;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * Returns exifs of an image
     *
     * @param string $file
     * @param string $format Valid formats are xml, json, array, dom
     * @throws Robo47_Exiftool_Exception
     * @return DOMDocument|array|string
     */
    public function getExifs($file, $format = null)
    {
        if (!file_exists($file) || !is_file($file)) {
            $message = 'File "' . $file . '" does not exist.';
            throw new Robo47_Exiftool_Exception($message);
        }
        if (null === $format) {
            $format = $this->getFormat();
        }
        switch($format) {
            case self::FORMAT_JSON:
                $exifs = $this->_runExiftool($file, self::FORMAT_JSON);
                break;
            case self::FORMAT_ARRAY:
                $exifs = $this->_runExiftool($file, self::FORMAT_JSON);
                $exifs = json_decode($exifs, true);
                break;
            case self::FORMAT_XML:
                $exifs = $this->_runExiftool($file, self::FORMAT_XML);
                break;
        }
        return $exifs;
    }

    /**
     *
     * @param string $file
     * @param string $format
     * @throws Robo47_Exiftool_Exception
     */
    protected function _runExiftool($file, $format)
    {
        $command = $this->_exiftool;
        switch($format)
        {
            case self::FORMAT_JSON:
                $command .= ' -j ';
                break;
            case self::FORMAT_XML:
                $command .= ' -X ';
                break;
        }
        $output = array();
        $command .= ' ' . escapeshellarg($file);
        $command .= ' 2>&1 ';
        $returnCode = 0;
        exec($command, $output, $returnCode);
        if ($returnCode != 0) {
            $message = 'executing exiftool failed: ' . implode('', $output);
            throw new Robo47_Exiftool_Exception($message, $returnCode);
        }
        return implode('', $output);
    }
}
