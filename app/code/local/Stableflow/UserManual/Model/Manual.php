<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 1:28 PM
 */
class Stableflow_UserManual_Model_Manual extends Mage_Core_Model_Abstract{

    public function _construct(){
        $this->_init('user_manual/manual');
    }

    public function getId(){
        return $this->getData('value_id');
    }
}