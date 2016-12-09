<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:55 AM
 */
class Stableflow_Company_Model_Company extends Mage_Core_Model_Abstract{

    protected function _construct(){
        $this->_init('company/company');
    }
}