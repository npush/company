<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:49 PM
 */
class Stableflow_Company_Model_Resource_Parser_Price_Type extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct(){
        $this->_init('company/price_type', 'entity_id');
    }
}