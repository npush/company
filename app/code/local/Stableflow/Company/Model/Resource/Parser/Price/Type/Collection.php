<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/28/17
 * Time: 3:16 PM
 */
class Stableflow_Company_Model_Resource_Parser_Price_Type_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('company/parser_price_type');
    }
}