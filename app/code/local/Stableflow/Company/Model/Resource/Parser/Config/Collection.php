<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 7:37 PM
 */
class Stableflow_Company_Model_Resource_Parser_Config_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_typeTable;

    protected $_map = array(
        'fields' => array(
            'config_description'  => 'main_table.description',
            'type_description' => 'price_type.description'
        ));

    protected function _construct(){
        $this->_init('company/parser_config');
        $this->_typeTable = $this->getTable('company/price_type');
    }

    public function addTypeFilter(){
         $this->getSelect()
             ->joinLeft(array('price_type' => $this->_typeTable), 'main_table.price_type_id = price_type.entity_id', array('entity_id', 'company_id', 'type_description' => 'description'))
             ->where('price_type.is_active', Stableflow_Company_Model_Parser_Price_Type::STATUS_ENABLED);
        return $this;
    }
}