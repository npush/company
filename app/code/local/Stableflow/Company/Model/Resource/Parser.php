<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 2:47 PM
 */
class Stableflow_Company_Model_Resource_Parser extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('company/parser_tasks', 'entity_id');
    }
}