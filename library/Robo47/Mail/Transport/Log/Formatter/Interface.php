<?php

interface Robo47_Mail_Transport_Log_Formatter_Interface
{
    /**
     * Format
     *
     * Formats the Mail into a "logable" Format
     *
     * @param Zend_Mail $mail
     * @return string
     */
    public function format(Zend_Mail $mail);
}
