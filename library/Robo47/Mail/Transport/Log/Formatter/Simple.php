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
 * Robo47_Mail_Transport_Log_Formatter_Simple
 *
 * @package     Robo47
 * @subpackage  Mail
 * @since       0.1
 * @copyright   Copyright (c) 2007-2010 Benjamin Steininger (http://robo47.net)
 * @license     http://robo47.net/licenses/new-bsd-license New BSD License
 * @author      Benjamin Steininger <robo47[at]robo47[dot]net>
 */
class Robo47_Mail_Transport_Log_Formatter_Simple implements
Robo47_Mail_Transport_Log_Formatter_Interface
{
    public function format(Zend_Mail $mail)
    {
        $message = 'Subject: ' . $mail->getSubject() . PHP_EOL;
        $message .= 'To: ' . implode(', ', $mail->getRecipients()) . PHP_EOL;
        $message .= 'Text: ' . $mail->getBodyText()->getContent() . PHP_EOL . PHP_EOL;
        $message .= 'Html: ' . $mail->getBodyHtml()->getContent();
        return $message;
    }
}