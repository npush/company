<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:17 PM
 */
class Stableflow_Company_Model_Parser_Queue extends Mage_Core_Model_Abstract
{
    protected $_queueCollection = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_queue');
    }

    /**
     * Get Queue ID by Task ID
     * @param $taskId
     * @return mixed
     */
    public function getIdByTaskId($taskId)
    {
        return $this->_getResource()->getIdByTaskId($taskId);
    }

    public function getTaskId()
    {
        return $this->getData('task_id');
    }

    public function getStatus()
    {
        $this->getData('status_id');
    }

    public function setStatus($status)
    {
        $this->setData('status_id', $status);
    }

    public function checkInQueue($taskId)
    {
        if($this->getIdByTaskId($taskId)){
            return true;
        }
        return false;
    }

    /**
     * Get Queue collection
     * @param int $status
     * @return Stableflow_Company_Model_Resource_Parser_Queue_Collection
     */
    public function getQueueCollection($status = null)
    {
        $collection = $this->getCollection();
        if(!is_null($status)){
            $collection->addFieldToFilter('status_id', $status);
        }
        return $collection;
    }

    /**
     * Add message to queue
     *
     * @return Stableflow_Company_Model_Parser_Queue
     */
    public function addToQueue()
    {
        try {
            $this->save();
            $this->setId(null);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    /**
     * Clean queue from sent messages
     *
     * @param int $lifeTime
     * @return Stableflow_Company_Model_Parser_Queue
     */
    public function cleanQueue($lifeTime)
    {
        $this->_getResource()->clean($lifeTime);
        return $this;
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        return $this;
    }
}