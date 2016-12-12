<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:15 AM
 */
class Cybage_Marketplace_Model_Resource_Company_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected function _construct(){
        $this->_init('marketplace/company');
    }
}