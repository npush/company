<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/13/17
 * Time: 4:34 PM
 */

class Stableflow_Company_Model_Resource_Owner extends Mage_Core_Model_Resource_Db_Abstract
{

    protected $_customerTable = null;

    protected function _construct()
    {
        $this->_init('company/owner', 'entity_id');
        $this->_customerTable = $this->getTable('customer/entity');
    }
}