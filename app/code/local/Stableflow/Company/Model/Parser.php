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

    /** @var Stableflow_Company_Model_Parser_Entity_Abstract */
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
            if (!($this->_entityAdapter instanceof Stableflow_Company_Model_Parser_Entity_Product)) {
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
        //return $this->_getEntityAdapter()->getMessages();
        return $this->_getEntityAdapter()->getErrors();
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
        return true; //$this->_getEntityAdapter()->getNotices();
    }

    /**
     * Returns entity model noticees.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_getEntityAdapter()->getMessages();
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
     * Working directory (source files, ).
     *
     * @return string
     */
    public static function getWorkingDir()
    {
        return Mage::helper('company/parser')->getFileBaseDir();
    }

    /**
     *
     */
    public function performTasksInQueue()
    {
        $this->addLogComment(Mage::helper('company')->__('Begin import'));
        /** @var Stableflow_Company_Model_Resource_Parser_Queue_Collection $queueCollection */
        $queueCollection = Mage::getModel('company/parser_queue')
            ->getQueueCollection(Stableflow_Company_Model_Parser_Queue_Status::STATUS_PENDING);
            /** @var  $_taskInQueue Stableflow_Company_Model_Parser_Queue*/
        $this->addLogComment(Mage::helper('company')->__('Tasks in queue: %d', $queueCollection->getSize()));
        foreach($queueCollection as $_taskInQueue){
            $this->addLogComment(Mage::helper('company')->__('Start parsing task ID: %d', $_taskInQueue->getTask()->getId()));
            $_taskInQueue->setInProgress();
            /** @var string $sourceFile Full path to source file*/
            $sourceFile = $this->getWorkingDir() . $_taskInQueue->getTask()->getSourceFile();
            $sourceAdapter = $this->_getSourceAdapter(
                $_taskInQueue->getTask()->getConfig(),
                $sourceFile
            );
            $this->_getEntityAdapter()->setSource($sourceAdapter)->setTask($_taskInQueue->getTask());
            $this->validateSource($sourceFile);
            $this->_getEntityAdapter()->runParsingProcess();
            $this->addLogComment(array(
                Mage::helper('company')->__('Checked rows: %d, checked entities: %d, invalid rows: %d, total errors: %d',
                    $this->getProcessedRowsCount(),
                    $this->getProcessedEntitiesCount(),
                    $this->getInvalidRowsCount(),
                    $this->getErrorsCount()),
                Mage::helper('company')->__('Import has been done successfully.')
            ));
            $this->addDbParserLog($this->getErrors());
            Mage::log($this->getMessages(), null, 'success-product.log');
            $_taskInQueue->setComplete();
        }
    }

    /**
     * @param Stableflow_Company_Model_Parser_Task $task
     * @return bool
     */
    public function runTask($task)
    {
        $this->addLogComment(Mage::helper('company')->__('Begin import. Task ID: %d', $task->getId()));
        $task->setInProgress();
        /** @var string $sourceFile Full path to source file*/
        $sourceFile = $this->getWorkingDir() . $task->getSourceFile();
        $sourceAdapter = $this->_getSourceAdapter(
            $task->getConfig(),
            $sourceFile
        );
        $this->_getEntityAdapter()->setSource($sourceAdapter)->setTask($task);
        $this->validateSource($sourceFile);
        $this->_getEntityAdapter()->runParsingProcess();
        $this->addLogComment(array(
            Mage::helper('company')->__('Checked rows: %d, checked entities: %d, invalid rows: %d, total errors: %d',
                $this->getProcessedRowsCount(),
                $this->getProcessedEntitiesCount(),
                $this->getInvalidRowsCount(),
                $this->getErrorsCount()),
            Mage::helper('company')->__('Import has been done successfully. Task ID: %d', $task->getId())
        ));
        $this->addDbParserLog($this->getErrors());
        Mage::log($this->getMessages(), null, 'success-product.log');
        $task->setComplete();
        return true;
    }

    public function update()
    {
        try{
            $_taskInQueue->getTask()->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_ERRORS_FOUND);
            $this->addLogComment(Mage::helper('company')->__('Error in task ID:%d', $_taskInQueue->getTask()->getId()));
            //Mage::exception('Stableflow_Company', 'Task error', 0);
        }catch (PHPExcel_Exception $e){

        }catch (Stableflow_Company_Exception $e){
            var_dump($e->getMessage());
        }catch (Mage_Core_Exception $e){

        }catch (Exception $e){
            Mage::log($e->getMessage(), null, 'parser-log');
        }
    }

    /**
     * Validates source file and returns validation result.
     *
     * @param $task
     * @param string $sourceFile Full path to source file
     * @return bool
     */
    public function validateSource($sourceFile)
    {
        $this->addLogComment(Mage::helper('company')->__('Begin data validation'));
        $result = true;
//        $result = $this->_getEntityAdapter()
//            ->setSource($this->_getSourceAdapter($task->getConfig(), $sourceFile))
//            ->isDataValid();
//
//        $messages = $this->getOperationResultMessages($result);
//        $this->addLogComment($messages);
        if ($result) {
            $this->addLogComment(Mage::helper('company')->__('Done import data validation'));
        }
        return $result;
    }
}