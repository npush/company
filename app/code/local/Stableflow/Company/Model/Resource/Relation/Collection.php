<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 3/1/17
 * Time: 11:47 AM
 */
class Stableflow_Company_Model_Resource_Relation_Collection extends  Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected function _construct(){
        $this->_init('company/relation');
    }
}