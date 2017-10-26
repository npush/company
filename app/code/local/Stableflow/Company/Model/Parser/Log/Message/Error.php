<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 12:43 PM
 */
class Stableflow_Company_Model_Parser_Log_Message_Error extends Stableflow_Company_Model_Parser_Log_Message_Abstract
{

    protected $_error_text;

    public function __construct($code, $text)
    {
        parent::__construct(Stableflow_Company_Model_Parser_Log_Message::MESSAGE_ERROR, $code);
        $this->_error_text = $text;
    }

    public function getErrorText()
    {
        return $this->_error_text;
    }
}