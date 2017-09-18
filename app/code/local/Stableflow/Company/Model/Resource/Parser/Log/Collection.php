<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 12:41 PM
 */
class Stableflow_Company_Model_Resource_Parser_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_typeTable;

    protected $_configTable;

    protected $_taskTable;

    protected function _construct()
    {
        $this->_init('company/parser_log');
        $this->_typeTable = $this->getTable('company/price_type');
        $this->_configTable = $this->getTable('company/parser_config');
        $this->_taskTable = $this->getTable('company/parser_tasks');
    }

    public function addCompanyFilter($companyId)
    {
        $this->getSelect()
            ->joinLeft(array('task_tbl' => $this->_taskTable),
                'main_table.task_id = task_tbl.entity_id',
                'config_id')
            ->joinLeft(array('config_tbl' => $this->_configTable),
                'task_tbl.config_id = config_tbl.entity_id',
                'price_type_id')
            ->joinLeft(array('type_tbl' => $this->_typeTable),
                'config_tbl.price_type_id = type_tbl.entity_id',
                'company_id')
            ->where('type_tbl.company_id = ?', $companyId);
        return $this;
    }
}