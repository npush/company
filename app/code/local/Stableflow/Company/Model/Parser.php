<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 3:48 PM
 */
class Stableflow_Company_Model_Parser extends Stableflow_Company_Model_Parser_Abstract
{

    protected $_eventPrefix      = 'company_parser';
    protected $_eventObject      = 'parser';

    protected $_entityAdapter;

    /**
     * Create instance of entity adapter and returns it.
     *
     * @throws Mage_Core_Exception
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    protected function _getEntityAdapter()
    {
        if (!$this->_entityAdapter) {
            $this->_entityAdapter = Mage::getModel('company/parser_entity_product');
            if (!($this->_entityAdapter instanceof Stableflow_Company_Model_Parser_Entity_Abstract)) {
                Mage::throwException(
                    Mage::helper('company')->__('Entity adapter object must be an instance of Stableflow_Company_Model_Parser_Entity_Abstract')
                );
            }
            //$this->_entityAdapter->setParameters($this->getData());
        }
        return $this->_entityAdapter;
    }

    /**
     * Returns source adapter object.
     *
     * @param Stableflow_Company_Model_Parser_Config_Settings $settings
     * @param string $sourceFile Full path to source file
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    protected function _getSourceAdapter($settings, $sourceFile)
    {
        return Stableflow_Company_Model_Parser_Adapter::factory($settings, $sourceFile);
    }

    /**
     * Get entity adapter errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_getEntityAdapter()->getErrorMessages();
    }

    /**
     * Returns error counter.
     *
     * @return int
     */
    public function getErrorsCount()
    {
        return $this->_getEntityAdapter()->getErrorsCount();
    }

    /**
     * Returns invalid rows count.
     *
     * @return int
     */
    public function getInvalidRowsCount()
    {
        return $this->_getEntityAdapter()->getInvalidRowsCount();
    }

    /**
     * Returns entity model noticees.
     *
     * @return array
     */
    public function getNotices()
    {
        return $this->_getEntityAdapter()->getNotices();
    }

    /**
     * Returns number of checked entities.
     *
     * @return int
     */
    public function getProcessedEntitiesCount()
    {
        return $this->_getEntityAdapter()->getProcessedEntitiesCount();
    }

    /**
     * Returns number of checked rows.
     *
     * @return int
     */
    public function getProcessedRowsCount()
    {
        return $this->_getEntityAdapter()->getProcessedRowsCount();
    }

    /**
     * Working directory (source files, result files, lock files etc.).
     *
     * @return string
     */
    public static function getWorkingDir()
    {
        return Mage::getBaseDir('media') . DS . 'pricelists' . DS;
    }

    /**
     * Get Task by Id
     *
     * @param $id
     * @return Stableflow_Company_Model_Parser_Task
     */
    public function loadTask($id)
    {
        return Mage::getModel('company/parser_task')->load($id);
    }

    public function updatePriceLists()
    {
        $this->addLogComment(Mage::helper('company')->__('Begin import'));
        /** @var Stableflow_Company_Model_Parser_Entity_Abstract $entity */
        $entity = $this->_getEntityAdapter();
        /** @var Stableflow_Company_Model_Parser_Queue $queue */
        $queue = Mage::getModel('company/parser_queue');
        /** @var Stableflow_Company_Model_Resource_Parser_Queue_Collection $queueCollection */
        $queueCollection = $queue->getQueueCollection(Stableflow_Company_Model_Parser_Queue_Status::STATUS_PENDING);
        try{
            /** @var  $_taskInQueue Stableflow_Company_Model_Parser_Queue*/
            foreach($queueCollection as $_taskInQueue){
                //$_taskQueue->setStatus(Stableflow_Company_Model_Parser_Queue_Status::STATUS_IN_PROGRESS);
                /** @var Stableflow_Company_Model_Parser_Task $task */
                $task = $this->loadTask($_taskInQueue->getTaskId());
                $settings = $task->getConfig();
                $source = $task->getSourceFile();
                $dir = Mage::helper('company/parser')->getFileBaseDir();
                $adapter = $this->_getSourceAdapter($settings, $dir.$source);

                if($entity->_run($task, $adapter)) {
                    $this->addLogComment(array(
                        Mage::helper('company')->__('Checked rows: %d, checked entities: %d, invalid rows: %d, total errors: %d',
                            $this->getProcessedRowsCount(),
                            $this->getProcessedEntitiesCount(),
                            $this->getInvalidRowsCount(),
                            $this->getErrorsCount()),
                        Mage::helper('company')->__('Import has been done successfully.')
                    ));
                    $_taskInQueue->delete();
                }
                $task->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_ERRORS_FOUND);
                unset($task);
            }
        }catch (Exception $e){
            Mage::log($e->getMessage(), null, 'Queue-log');
        }
    }


    /**
     * Validates source file and returns validation result.
     *
     * @param string $sourceFile Full path to source file
     * @return bool
     */
    public function validateSource($sourceFile)
    {
        $this->addLogComment(Mage::helper('importexport')->__('Begin data validation'));
        $result = $this->_getEntityAdapter()
            ->setSource($this->_getSourceAdapter($sourceFile))
            ->isDataValid();

        $messages = $this->getOperationResultMessages($result);
        $this->addLogComment($messages);
        if ($result) {
            $this->addLogComment(Mage::helper('importexport')->__('Done import data validation'));
        }
        return $result;
    }
}