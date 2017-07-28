<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:58 PM
 */
class Stableflow_Company_Model_Resource_Parser_Config extends Mage_Core_Model_Resource_Db_Abstract
{

    protected $_typeTable;

    public function _construct(){
        $this->_init('company/parser_config', 'entity_id');
        $this->_typeTable = $this->getTable('company/price_type');
    }


}