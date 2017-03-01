<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:58 AM
 */
class Stableflow_Company_Model_Resource_Company extends Mage_Eav_Model_Entity_Abstract{

    protected $_companyProductTable = null;

    public function _construct(){
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_company');
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