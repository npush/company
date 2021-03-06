<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:17 PM
 */
class Stableflow_Company_Model_Parser_Log extends Mage_Core_Model_Abstract
{

    protected $_taskId = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_log');
    }

    public function getLogCollection($companyId = null, $status = null)
    {
        $collection = $this->getCollection();
        if(!is_null($companyId)){
            $collection->addCompanyFilter($companyId);
        }
        if(!is_null($status)){
            $collection->addFieldToFilter('status_id', array('eq' => $status));
        }
        return $collection;
    }

    public function getErrorsLinesIds($taskId = null)
    {

    }

    public function addToLog($message)
    {
        echo $message->_debugInfo();
        Mage::log($message->_debugInfo(), null, 'parser_task-'. $message->getCompanyId() .'.log');

    }

    /**
     * Add log message
     * @param Stableflow_Company_Model_Parser_Log_Message_Abstract $message
     * @return $this
     */
    public function log($data)
    {
        foreach ($data as $_data) {
            $this->_getResource()->addLog($_data);

        }
        return $this;
        $this->setData('task_id', Mage::registry('current_task'));
        $this->setData('error_text', $status);
        $this->setData('content', $this->toJson($message['content']));
//        if(count($message['process_data']) > 0) {
//            $this->setData('line', $this->toJson($message['row_number']));
//            $this->setData('task_id', $this->toJson($message['process_data']['task_id']));
//            $this->setData('task_id', $this->toJson($message['process_data']['task_id']));
//        }
        return $this;
    }

    /**
     * Clean log
     *
     * @param int $lifeTime Default 31 day`s
     * @return Stableflow_Company_Model_Parser_Log
     */
    public function cleanLog($lifeTime = 31)
    {
        $this->_getResource()->clean($lifeTime);
        return $this;
    }
}