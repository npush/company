<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 1:02 PM
 */
class Stableflow_Company_Model_Parser_Log_Message_Success extends Stableflow_Company_Model_Parser_Log_Message_Abstract
{
    public function __construct($rowData, $statusCode)
    {
        parent::__construct($rowData, $statusCode);
    }
}