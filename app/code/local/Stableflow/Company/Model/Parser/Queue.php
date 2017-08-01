<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:17 PM
 */
class Stableflow_Company_Model_Parser_Queue extends Mage_Core_Model_Abstract
{
    const STATUS_PENDING        = 1;
    const STATUS_IN_PROGRESS    = 2;

    protected $_queueCollection = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_queue');
    }

    /**
     * @param $taskId
     * @return mixed
     */
    public function getIdByTaskId($taskId)
    {
        return $this->_getResource()->getIdByTaskId($taskId);
    }

    public function getStatus(){}

    public function checkInQueue($taskId)
    {
        if($this->getIdByTaskId($taskId)){
            return true;
        }
        return false;
    }

    public function getQueue()
    {
        return $this->getCollection();
    }

    public function getTask($taskId)
    {
        return Mage::getModel('company/parser_task')->load($taskId);
    }

    public function PerformQueue(){}

    protected function _beforeSave()
    {
        parent::_beforeSave();

        return $this;
    }
}