<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 5/16/17
 * Time: 4:26 PM
 */
class Stableflow_Company_Model_Resource_Owner_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected function _construct(){
        $this->_init('company/owner');
    }
}