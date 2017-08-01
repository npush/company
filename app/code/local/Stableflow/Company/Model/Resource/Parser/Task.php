<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 5:00 PM
 */
class Stableflow_Company_Model_Resource_Parser_Task extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_configTable = null;

    public function _construct()
    {
        $this->_init('company/parser_tasks', 'entity_id');
        $this->_configTable = $this->getTable('company/parser_config');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $select->joinLeft(
            array('config_table' => $this->_configTable),
            $this->getMainTable() . '.config_id = config_table.entity_id',
            array('config', 'description'))
            ->where('config_table.is_active', Stableflow_Company_Model_Parser_Config::STATUS_ENABLED)
            ->limit(1);
        return $select;
    }
}