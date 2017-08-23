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

    protected $_taskCollection = null;

    protected $_startParsingTime = null;

    protected $_file;

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
                'status_id' => Stableflow_Company_Model_Parser_Queue::STATUS_PENDING
            ));
            return $queue->save()->getId();
        }
        return false;
    }


    public function getParserInstance()
    {
        return Stableflow_Company_Model_Parser_Adapter::factory($this->_config, $this->_file);
    }

    public function run()
    {
        $parser = $this->getParserInstance();

        $parser->init();
        $parser->parse();
        $status = $parser->updatePrice();

        if($status['status']) {
            $result['type'] = 'success';
            $result['message'] = Mage::helper('stableflow_pricelists')->__('Configuration saved. Prices successfully updated.');
            $result['message'] .= Mage::helper('stableflow_pricelists')->__(" Skipped Items: {$status['skipped']}, Saved Items: {$status['saved']}, Total: {$status['total']}");
        } else {
            $result['type'] = 'error';
            $result['message'] = "code required";
        }
    }
}