<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 1:33 PM
 */
class Stableflow_UserManual_model_resource_Manual_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    public function _construct(){
        $this->_init('user_manual/manual');
        parent::_construct();
    }
}