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

    protected function _construct(){
        $this->_init('company/parser_price_type');
    }

    public function getPriceTypeCollection($companyId){
        if(!$this->_typeCollection) {
            $this->_typeCollection = $this->getCollection()
                ->addFieldToFilter('company_id', $companyId);
        }
        return $this->_typeCollection;
    }
}