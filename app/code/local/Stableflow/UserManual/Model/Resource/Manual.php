<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 1:29 PM
 */
class Stableflow_UserManual_Model_Resource_Manual extends Mage_Core_Model_Resource_DB_Abstract
{

    public function _construct()
    {
        $this->_init('user_manual/manual', 'value_id');

    }
}