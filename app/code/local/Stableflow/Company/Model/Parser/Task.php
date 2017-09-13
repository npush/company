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

    /** @var int */
    protected $_startParsingTime = null;

    /**
     * Source file path
     * @var string
     */
    protected $_source = null;

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
        $a = Mage::getModel('company/parser_config')->load($this->getData('config_id'));
        $this->_config = $a->getSettingsObject();
        $this->_source = $this->getData('name');
        return $this->_config;
    }

    public function getStatus()
    {

    }

    public function setStatus($status)
    {
        $this->setData('status_id', $status);
    }

    public function getSpentTime()
    {

    }

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
                'status_id' => Stableflow_Company_Model_Parser_Queue_Status::STATUS_PENDING
            ));
            return $queue->save()->getId();
        }
        return false;
    }


    public function getParserInstance()
    {
        return Stableflow_Company_Model_Parser_Adapter::factory($this->_config, Mage::getBaseDir('media'). '/pricelists'.$this->_source);
    }

    public function run()
    {
        $data = array();
        $config = $this->getConfig();
        $parser = $this->getParserInstance();
        $productModel = Mage::getModel('company/parser_entity_product');
        //$productModel->update($data);
        //$parser->setStatus(Stableflow_Company_Model_Parser_Task_Status::S);
        for($i= 50; $i > 0; $i--){
            $data = $parser->current();
            print_r($data);
            $productModel->update($data);
            $parser->next();
        }

        /*$parser->current();
        foreach($parser as $row){
            print_r($row);
            $productModel->update($row);
        }*/

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