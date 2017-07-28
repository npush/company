<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:16 PM
 */
class Stableflow_Company_Model_Parser_Config extends Mage_Core_Model_Abstract
{

    protected $_configCollection = null;

    protected function _construct(){
        $this->_init('company/parser_config');
    }

    public function getConfig($companyId, $pricetypeId){
        return $this;
    }

    public function gePriceTypeCollection($companyId){
        return Mage::getModel('company/parser_price_type')->getPriceTypeCollection($companyId);
    }

    public function getConfigCollection($companyId){
        if(!$this->_configCollection) {
            $this->_configCollection = $this->getCollection()
                ->addTypeFilter()
                ->addFieldToFilter('company_id', $companyId);
        }
        return $this->_configCollection;
    }

    public function getStatus(){
        if (is_null($this->_getData('is_active'))) {
            $this->setData('is_active', Stableflow_Company_Model_Parser_Config_Status::STATUS_ENABLED);
        }
        return $this->_getData('is_active');
    }

    /**
     * Checks whether config has enabled status
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getStatus() == Stableflow_Company_Model_Parser_Config_Status::STATUS_ENABLED;
    }
}