<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 4:26 PM
 */
class Stableflow_Company_Model_Price_Resource_Price extends Mage_Eav_Model_Entity_Abstract{

    public function __construct(){
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_price');
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