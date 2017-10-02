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

    protected $_priceType = null;

    /** @var Stableflow_Company_Model_Parser_Config_Settings  */
    protected $_settings = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_config');
    }

    /**
     * @return int $companyId
     */
    public function getCompanyId()
    {
        return $this->getData('company_id');
    }

    /**
     * @return Stableflow_Company_Model_Parser_Price_Type|null
     */
    public function getPriceType()
    {
        if(is_null($this->_priceType)) {
            $this->_priceType = Mage::getModel('company/parser_price_type')->load($this->getData('price_type_id'));
        }
        return $this->_priceType;
    }

    /**
     * Retrieve PriceType Collection
     * @param $companyId|null
     * @return Stableflow_Company_Model_Resource_Parser_Price_Type_Collection
     */
    public function getPriceTypeCollection($companyId = null)
    {
        return Mage::getModel('company/parser_price_type')->getPriceTypeCollection($companyId);
    }

    /**
     * Retrieve Config Collection object
     * @param $companyId|null
     * @return Stableflow_Company_Model_Resource_Parser_Config_Collection
     */
    public function getConfigCollection($companyId = null)
    {
        $this->_configCollection = $this->getCollection()->addTypeFilter();
        if(!is_null($companyId)) {
            $this->_configCollection->addFieldToFilter('company_id', $companyId);
        }
        return $this->_configCollection;
    }

    /**
     * Get config Ids by company Id
     * @param $companyId
     * @return array
     */
    public function getConfigIds($companyId)
    {
        return $this->_getResource()->getConfigIds($companyId);
    }

    /**
     * @param array $data
     */
    public function setSettings($data)
    {
        $this->setData('config', $data);
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->getData('config');
    }

    /**
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    public function getSettingsObject()
    {
        if($this->_settings == null && $this->getData('config') != '') {
            $this->_settings =  Mage::getSingleton('company/parser_config_settings', $this->getData('config'));
        }
        return $this->_settings;
    }

    /**
     * Check config status
     * @return mixed
     */
    public function getStatus()
    {
        if(is_null($this->_getData('is_active'))){
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

    public function getValues()
    {
        foreach($this->_configCollection as $_config){
            $values[] =  array(
                'value'     => $_config->getId(),
                'label'     => $_config->getData('description'),
            );
        }
    }
}