<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:30 PM
 */
class Stableflow_Company_Model_Parser_Task extends Mage_Core_Model_Abstract
{
    const STATUS_COMPLETE       = 1;
    const STATUS_ERRORS_FOUND   = 2;
    const STATUS_NEW            = 3;
    const STATUS_IN_PROGRESS    = 4;

    protected $_config = null;

    protected $_startParsingTime = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_task');
    }


    /**
     * Retrieve config object
     * @return Stableflow_Company_Model_Parser_Config
     */
    public function getConfig()
    {
        $this->_config = Mage::getModel('company/parser_config')->load($this->getData('config_id'));
        return $this->_config;
    }

    public function getStatus(){}

    public function setStatus(){}

    public function getSpentTime(){}

    protected function _calcSpentTime(){}

    public function getLog(){}

    public function getTaskComplete(){}

    public function getTaskWithErrors(){}

    public function addToQueue()
    {
        $id = $this->getId();
        if($id && !Mage::getModel('company/parser_queue')->checkInQueue($id)){
            $queue = Mage::getModel('company/parser_queue');
            $queue->addData(array(
                'task_id' => $id,
                'status_id' => Stableflow_Company_Model_Parser_Queue::STATUS_PENDING
            ));
            return $queue->save()->getId();
        }
        return false;
    }
}