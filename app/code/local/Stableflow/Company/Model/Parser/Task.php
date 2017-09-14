<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:30 PM
 */
class Stableflow_Company_Model_Parser_Task extends Mage_Core_Model_Abstract
{
    /**
     * Configuration object
     * @var Stableflow_Company_Model_Parser_Config_Settings
     */
    protected $_config = null;

    /** @var Stableflow_Company_Model_Resource_Parser_Config_Collection  */
    protected $_taskCollection = null;

    /**
     * Source file path
     * @var string
     */
    protected $_source = null;

    /**
     * Parser task Start Time
     * @var int
     */
    protected $_startTime = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_task');
    }

    protected function _initConfiguration()
    {
        $config = Mage::getModel('company/parser_config')->load($this->getData('config_id'));
        $this->_config = $config->getSettingsObject();
        $this->_source = $this->getData('name');
    }

    /**
     * Retrieve config object
     * @return Stableflow_Company_Model_Parser_Config
     */
    public function getConfig()
    {
        $this->_initConfiguration();
        return $this->_config;
    }

    public function getStatus()
    {

    }

    public function setStatus($status)
    {
        $this->setData('status_id', $status);
    }

    public function getCompanyId()
    {
        $config = Mage::getModel('company/parser_config')->load($this->getData('config_id'));
        return $config->getCompanyId();
    }

    public function getSpentTime()
    {
        return $this->getData('time_spent');
    }

    public function setSpentTime()
    {
        $this->setData('time_spent', $this->_calcSpentTime());
    }

    protected function _initTime()
    {
        $this->_startTime = microtime(true);
    }

    protected function _calcSpentTime()
    {
        $finalTime = microtime(true) - $this->_startTime;
        return $finalTime;
    }

    public function getLog(){}

    public function getTasksCollection($company_id = null, $status = null)
    {

        $collection = $this->getCollection();
        if(!is_null($company_id)){
            $configIds = Mage::getModel('company/parser_config')->getConfigIds($company_id);
            $collection->addFieldToFilter('config_id', array('in' => $configIds));
        }
        if(!is_null($status)){
            $collection->addFieldToFilter('status_id', array('eq' => $status));
        }
        return $collection;
    }

    public function addToQueue()
    {
        $id = $this->getId();
        if($id && !Mage::getModel('company/parser_queue')->checkInQueue($id)){
            $queue = Mage::getModel('company/parser_queue');
            $queue->addData(array(
                'task_id' => $id,
                'status_id' => Stableflow_Company_Model_Parser_Queue_Status::STATUS_PENDING
            ));
            return $queue->save()->getId();
        }
        return false;
    }


    public function getParserInstance()
    {
        $dir = Mage::helper('company/parser')->getFileBaseDir();
        return Stableflow_Company_Model_Parser_Adapter::factory($this->_config, $dir . $this->_source);
    }

    public function run()
    {
        $this->_initTime();
        $this->_initConfiguration();
        $parser = $this->getParserInstance();
        $productModel = Mage::getModel('company/parser_entity_product');
        //$productModel->update($data);
        // Iterate
        foreach($parser as $row){
            if(!$row['code']) continue;
            $data = array(
                'company_id' => $this->getCompanyId(),
                'row'        => $row,
                'row_num'    => $parser->key()
            );
            printf("Row N: %d. %s \n", $parser->key(), serialize($row));
            $productModel->update($data);
        }
        $this->setSpentTime();
        //$this->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_COMPLETE);
        $this->save();
        return true;
    }
}