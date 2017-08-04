<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:15 PM
 */
class Stableflow_Company_Model_Parser_Price_Type extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;

    protected $_typeCollection = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_price_type');
    }

    /**
     * @param null $companyId
     * @return null|Stableflow_Company_Model_Resource_Parser_Price_Type_Collection
     */
    public function getPriceTypeCollection($companyId = null){
        $this->_typeCollection = $this->getCollection();
        if(!is_null($companyId)){
            $this->_typeCollection->addFieldToFilter('company_id', $companyId);
        }
        return $this->_typeCollection;
    }

    public function setDescription($description){
        $this->setData('description', trim($description));
    }

    /**
     * Check config status
     * @return mixed
     */
    public function getStatus()
    {
        if(is_null($this->_getData('is_active'))){
            $this->setData('is_active', Stableflow_Company_Model_Parser_Price_Type::STATUS_ENABLED);
        }
        return $this->_getData('is_active');
    }

    public function isActive()
    {
        return $this->getStatus() == Stableflow_Company_Model_Parser_Price_Type::STATUS_ENABLED;
    }
}