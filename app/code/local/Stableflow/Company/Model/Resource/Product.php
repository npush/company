<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/28/17
 * Time: 6:21 PM
 */
class Stableflow_Company_Model_Resource_Product extends Mage_Eav_Model_Entity_Abstract{

    public function _construct(){
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_product');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
        //$this->_companyTable = $this->getTable('mageplaza_betterblog/post_category');
    }

    public function getMainTable(){
        return $this->getEntityTable();
    }

    protected function _getDefaultAttributes(){
        return [
            'entity_id',
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'is_active'
            //'website_id'
        ];
    }
}