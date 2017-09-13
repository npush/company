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

    public function setCompanyId(Stableflow_Company_Model_Company $company)
    {
        $this->_companyId = $company->getId();
    }

    public function getCompanyId()
    {
        return $this->_companyId;
    }

    /**
     * Retrieve tasks collection
     * @param $companyId
     * @return Stableflow_Company_Model_Resource_Parser_Config_Collection
     */
    public function getTasksCollection($companyId)
    {
        $configCollection = Mage::getModel('company/parser_config')->getConfigCollection($companyId);
        $ids = array();
        foreach($configCollection as $config){
            $ids[] = $config->getId();
        }
        return $this->getCollection()
            ->addFieldToFilter('config_id', array('in' => $ids));
    }

    public function loadTask($id)
    {
        return Mage::getModel('company/parser_task')->load($id);
    }

    public function getPriceType(){}

    public function getConfig()
    {
        return $this->_config;
    }

    public function addTaskToQueue($taskId)
    {

    }

    public function getTaskStatus(){}
    public function getQueue(){}
}