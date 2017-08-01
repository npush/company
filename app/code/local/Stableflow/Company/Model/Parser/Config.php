<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:16 PM
 */
class Stableflow_Company_Model_Parser_Config extends Mage_Core_Model_Abstract
{

    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;

    protected $_configCollection = null;

    protected $_priceType = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_config');
    }

    /**
     * @return Stableflow_Company_Model_Parser_Price_Type|null
     */
    public function getPriceType()
    {
        $this->_priceType = Mage::getModel('company/parser_price_type')->load($this->getData('price_type_id'));
        return $this->_priceType;
    }

    /**
     * Retrieve PriceType Collection
     * @param $companyId
     * @return Stableflow_Company_Model_Resource_Parser_Price_Type_Collection
     */
    public function gePriceTypeCollection($companyId)
    {
        return Mage::getModel('company/parser_price_type')->getPriceTypeCollection($companyId);
    }

    /**
     * Retrieve Config Collection object
     * @param $companyId
     * @return Stableflow_Company_Model_Resource_Parser_Config_Collection
     */
    public function getConfigCollection($companyId)
    {
        if(!$this->_configCollection) {
            $this->_configCollection = $this->getCollection()
                ->addTypeFilter()
                ->addFieldToFilter('company_id', $companyId);
        }
        return $this->_configCollection;
    }

    /**
     * @param Stableflow_Company_Model_Parser_Config_Settings $data
     */
    public function setSettings(Stableflow_Company_Model_Parser_Config_Settings $data)
    {
        $this->setData('config', $data);
    }

    /**
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    public function getSettings()
    {
        return $this->getData('config');
    }

    public function addNewConfig()
    {

    }

    /**
     * Check config status
     * @return mixed
     */
    public function getStatus()
    {
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