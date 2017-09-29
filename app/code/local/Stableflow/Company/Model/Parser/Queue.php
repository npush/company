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
        $this->save();
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
    public function getQueue($status = null)
    {
        $collection = $this->getCollection();
        if(!is_null($status)){
            $collection->addFieldToFilter('status_id', $status);
        }
        return $collection;
    }

    /**
     * Get Task by task ID
     * @param $taskId
     * @return Stableflow_Company_Model_Parser_Task
     */
    public function getTask($taskId)
    {
        return Mage::getModel('company/parser_task')->load($taskId);
    }

    /**
     *
     */
    public function performQueue()
    {
        $queueCollection = $this->getQueue(Stableflow_Company_Model_Parser_Queue_Status::STATUS_PENDING);
        try{
            /** @var  $_taskQueue Stableflow_Company_Model_Parser_Queue*/
            foreach($queueCollection as $_taskQueue){
                $_taskQueue->setStatus(Stableflow_Company_Model_Parser_Queue_Status::STATUS_IN_PROGRESS);
                $task = $this->getTask($_taskQueue->getTaskId());
                if($task->run()) {
                    $_taskQueue->delete();
                }
              $task->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_ERRORS_FOUND);
                unset($task);
            }
        }catch (Exception $e){
            Mage::log($e->getMessage(), null, 'Queue-log');
        }
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        return $this;
    }
}