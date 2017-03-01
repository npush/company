<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/28/17
 * Time: 6:22 PM
 */
class Stableflow_Company_Model_Resource_Product_Collection extends Mage_Eav_Model_Entity_Collection_Abstract{


    protected function _construct(){
        $this->_init('company/product');
    }
}