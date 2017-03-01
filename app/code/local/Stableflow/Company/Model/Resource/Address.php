<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 1:42 PM
 */
class Stableflow_Company_Model_Resource_Address extends Mage_Eav_Model_Entity_Abstract {

    public function _construct(){
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_address');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
    }

    protected function _getDefaultAttributes(){
        return array(
            'entity_id',
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'is_active'
        );
    }
}