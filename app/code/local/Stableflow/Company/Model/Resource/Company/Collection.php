<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:04 PM
 */
class Stableflow_Company_Model_Company_Resource_Company_Collection extends Mage_Eav_Model_Entity_Collection_Abstract{

    protected function _construct(){
        $this->_init('company/company');
    }
}