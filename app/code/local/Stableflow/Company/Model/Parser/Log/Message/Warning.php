<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 1:01 PM
 */
class Stableflow_Company_Model_Parser_Log_Message_Warning extends Stableflow_Company_Model_Parser_Log_Message_Abstract
{
    public function __construct($code)
    {
        parent::__construct(Stableflow_Company_Model_Parser_Log_Message::MESSAGE_WARNING, $code);
    }
}