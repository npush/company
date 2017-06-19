<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 5:30 PM
 */
class Stableflow_Log404_Model_Resource_Log404_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('sf_log404/log404');
    }
}