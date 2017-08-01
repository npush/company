<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:58 PM
 */
class Stableflow_Company_Model_Resource_Parser_Config extends Mage_Core_Model_Resource_Db_Abstract
{

//    protected $_serializableFields = array(
//        'config' => array(null, array())
//    );

    protected $_typeTable;

    public function _construct()
    {
        $this->_init('company/parser_config', 'entity_id');
        $this->_typeTable = $this->getTable('company/price_type');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $select->joinLeft(
            array('price_type' => $this->_typeTable),
            $this->getMainTable() . '.price_type_id = price_type.entity_id',
            array('company_id', 'type_description' => 'description')
        )
            ->where('price_type.is_active', Stableflow_Company_Model_Parser_Price_Type::STATUS_ENABLED)
            ->limit(1);
        return $select;
    }

    protected function _beforeSave(Stableflow_Company_Model_Parser_Config $object)
    {
        parent::_beforeSave($object);
        if ($object->getConfig()) {
            $serializedValue =  serialize($object->getConfig());
            $object->setConfig($serializedValue);
        }
        return $this;
    }

    protected function _afterLoad(Stableflow_Company_Model_Parser_Config $object)
    {
        parent::_afterLoad($object);
        if ($object->getConfig()) {
            $unSerializedValue =  unserialize($object->getConfig());
            $object->setConfig($unSerializedValue);
        }
        return $this;
    }
}