<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 4:25 PM
 */
class Stableflow_Company_Model_Price extends Mage_Core_Model_Abstract{

    protected function _construct(){
        $this->_init('company/price');
    }
}