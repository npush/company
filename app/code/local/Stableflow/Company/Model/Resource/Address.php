<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 1:42 PM
 */
class Stableflow_Company_Model_Resource_Address extends Mage_Eav_Model_Entity_Abstract
{


    public function _construct()
    {
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_address');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
    }

    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    protected function _getDefaultAttributes()
    {
        return array(
            'entity_id',
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'is_active',
            'parent_id'
        );
    }

    protected function _updateAttribute($object, $attribute, $valueId, $value)
    {
        $table = $attribute->getBackend()->getTable();
        if(!isset($this->_attributeValuesToSave[$table])){
            $this->_attributeValuesToSave[$table] = array();
        }

        $entityIdField = $attribute->getBackend()->getEntityIdField();

        $data = array(
            'entity_type_id'    => $object->getEntityTypeId(),
            $entityIdField      => $object->getId(),
            'attribute_id'      => $attribute->getId(),
            'value'             => $this->_prepareValueForSave($value, $attribute)
        );

        if($valueId){
            $data['value_id'] = $valueId;
        }

        $this->_attributeValuesToSave[$table][] = $data;

        return $this;
    }
}