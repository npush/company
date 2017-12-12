<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 7:37 PM
 */
class Stableflow_Company_Model_Resource_Parser_AddCode_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('company/parser_addCode');
    }
}