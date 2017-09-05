<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 12:41 PM
 */
class Stableflow_Company_Model_Resource_Parser_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct(){
        $this->_init('company/parser_log');
    }
}