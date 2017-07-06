<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/5/17
 * Time: 4:40 PM
 */
class Stableflow_BlackIp_Model_Resource_Blacklist_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('sf_blackip/blacklist');
    }
}