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
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    public function getConfig()
    {
        return $this->_settingsObject;
    }

    public function getStatus()
    {

    }

    public function getSourceFile()
    {
        return $this->_source;
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

    public function setProcessAt()
    {
        $this->setData('process_at', Varien_Date::now());
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
    }

    protected function getLastRow()
    {
        return $this->getData('last_row');
    }

    public function getLog()
    {
        return Mage::getModel('company/parser_log')->getLogByTaskId($this->getId());
    }

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

    /**
     * Add Task to Queue
     *
     * @return bool|mixed
     * @throws Exception
     */
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

    /**
     * @param string $position
     * @return bool
     */
    protected function checkLastPosition($position)
    {
        if(!is_null($this->getLastRow())) {
            list($curSheetIdx, $curRow) = explode(':', $position);
            list($lastSheetIdx, $lastRow) = explode(':', $this->getLastRow());
            if($curSheetIdx <= $lastSheetIdx && $curRow <= $lastRow);
        }
        return $this->getLastRow() != null && $this->getLastRow() >= $ind;
    }
}