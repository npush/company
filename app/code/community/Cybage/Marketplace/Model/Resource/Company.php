<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:13 AM
 */

class Cybage_Marketplace_Model_Resource_Company extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct(){
        $this->_init('marketplace/company','id');
    }
}