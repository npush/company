<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 4:27 PM
 */
class Stableflow_Company_Model_Price_Resource_Price_Collection extends Mage_Eav_Model_Entity_Collection_Abstract{

    protected function _construct(){
        $this->_init('company/price');
    }
}