<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:30 PM
 */
class Stableflow_Company_Model_Parser_Task extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix      = 'company_parser';
    protected $_eventObject      = 'task';

    protected $_config = null;

    /**
     * Configuration object
     * @var Stableflow_Company_Model_Parser_Config_Settings
     */
    protected $_settingsObject = null;

    /** @var Stableflow_Company_Model_Resource_Parser_Config_Collection  */
    protected $_taskCollection = null;

    /**
     * Source file path
     * @var string
     */
    protected $_source = null;

    protected $_companyId = null;

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

    public function load($id, $field = null){
        parent::load($id, $field);
        $this->_initTime();
        $this->_initConfiguration();
        return $this;
    }

    protected function _initConfiguration()
    {
        $config = Mage::getModel('company/parser_config')->load($this->getData('config_id'));
        $this->_config = $config;
        $this->_settingsObject = $config->getSettingsObject();
        $this->_source = $this->getData('name');
        $this->_companyId = $config->getCompanyId();
    }

    protected function _getEventData()
    {
        return array(
            'data_object'       => $this,
            $this->_eventObject => $this,
        );
    }
    /**
     * Retrieve config object
     * @return Stableflow_Company_Model_Parser_Config
     */
    public function getConfig()
    {
        return $this->_settingsObject;
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
        return $this->_companyId;
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

    protected function setReadRowNum($num)
    {
        $this->setData('last_row', $num);
        $this->save();
    }

    protected function checkLastRow()
    {
        return $this->getData('last_row');
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
        return Stableflow_Company_Model_Parser_Adapter::factory($this->_settingsObject, $dir . $this->_source);
    }

    public function run()
    {
        $parser = $this->getParserInstance();
        //$params = array('object' => $this, 'field' => $field, 'value'=> $id);
        //$params = array_merge($params, $this->_getEventData());
        Mage::dispatchEvent($this->_eventPrefix.'_task_run_before', array($this->_eventObject => $this));
        // Iterate
        foreach($parser as $row){
            if($this->checkLastRow() != null && $this->checkLastRow() >= $parser->key()){
                continue;
            }
            $data = new Varien_Object(array(
                'company_id'            => $this->getCompanyId(),
                'manufacturer'          => $this->_settingsObject->getManufacturer(),
                'task_id'               => $this->getId(),
                'line_num'              => $parser->key(),
                'content'               => serialize($row),
                'raw_data'              => $row,
                'catalog_product_id'    => null,
                'company_product_id'    => null
            ));
            Mage::getModel('company/parser_entity_product')->update($data);
            $this->setReadRowNum($parser->key());
        }
        $this->setSpentTime();
        //$this->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_COMPLETE);
        $this->save();
        Mage::dispatchEvent($this->_eventPrefix.'_task_run_after', array($this->_eventObject => $this));
        return true;
    }
}