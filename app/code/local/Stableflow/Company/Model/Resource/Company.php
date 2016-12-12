<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:58 AM
 */
class Stableflow_Company_Model_Company_Resource_Company extends Mage_Eav_Model_Entity_Abstract{

    public function __construct(){
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_company');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
    }

    protected function _getDefaultAttributes(){
        return [
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'website_id'
        ];
    }
}