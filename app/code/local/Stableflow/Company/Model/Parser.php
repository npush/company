<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 3:48 PM
 */
class Stableflow_Company_Model_Parser extends Mage_Core_Model_Abstract
{

    protected $_companyId;

    protected $_config = null;

    /**
     * Get task status
     *
     * @return int
     */
    public function getStatus()
    {
        if (is_null($this->_getData('status_id'))) {
            $this->setData('status_id', Stableflow_Company_Model_Parser_Status::STATUS_ENABLED);
        }
        return $this->_getData('status_id');
    }

    public function getCompanyId(){
        return $this->_companyId;
    }

    public function getTasks(){}

    public function getPriceType(){}

    public function getConfig($companyId){
        $this->_config = Mage::getModel('company/parser_config')->load($companyId);
    }

    public function getQueue(){}
}