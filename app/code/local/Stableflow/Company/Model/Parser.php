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
     * @param Stableflow_Company_Model_Parser_Config_Settings $settings $settings
     * @param string $sourceFile Full path to source file
     * @return Stableflow_Company_Model_Parser_Adapter
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
     * Import/Export working directory (source files, result files, lock files etc.).
     *
     * @return string
     */
    public static function getWorkingDir()
    {
        return Mage::getBaseDir('media') . DS . 'pricelists' . DS;
    }

    /**
     * Import source file structure to DB.
     *
     * @return bool
     */
    public function importSource()
    {
        $this->setData(array(
            'entity'   => self::getDataSourceModel()->getEntityTypeCode(),
            'behavior' => self::getDataSourceModel()->getBehavior()
        ));
        $this->addLogComment(Mage::helper('importexport')->__('Begin import of "%s" with "%s" behavior', $this->getEntity(), $this->getBehavior()));
        $result = $this->_getEntityAdapter()->importData();
        $this->addLogComment(array(
            Mage::helper('importexport')->__('Checked rows: %d, checked entities: %d, invalid rows: %d, total errors: %d', $this->getProcessedRowsCount(), $this->getProcessedEntitiesCount(), $this->getInvalidRowsCount(), $this->getErrorsCount()),
            Mage::helper('importexport')->__('Import has been done successfuly.')
        ));
        return $result;
    }

    /**
     * Move uploaded file and create source adapter instance.
     *
     * @throws Mage_Core_Exception
     * @return string Source file path
     */
    public function uploadSource()
    {
        $entity    = $this->getEntity();
        $uploader  = Mage::getModel('core/file_uploader', self::FIELD_NAME_SOURCE_FILE);
        $uploader->skipDbProcessing(true);
        $result    = $uploader->save(self::getWorkingDir());
        $extension = pathinfo($result['file'], PATHINFO_EXTENSION);

        $uploadedFile = $result['path'] . $result['file'];
        if (!$extension) {
            unlink($uploadedFile);
            Mage::throwException(Mage::helper('importexport')->__('Uploaded file has no extension'));
        }
        $sourceFile = self::getWorkingDir() . $entity;

        $sourceFile .= '.' . $extension;

        if(strtolower($uploadedFile) != strtolower($sourceFile)) {
            if (file_exists($sourceFile)) {
                unlink($sourceFile);
            }

            if (!@rename($uploadedFile, $sourceFile)) {
                Mage::throwException(Mage::helper('importexport')->__('Source file moving failed'));
            }
        }
        // trying to create source adapter for file and catch possible exception to be convinced in its adequacy
        try {
            $this->_getSourceAdapter($sourceFile);
        } catch (Exception $e) {
            unlink($sourceFile);
            Mage::throwException($e->getMessage());
        }
        return $sourceFile;
    }


    public function loadTask($id)
    {
        return Mage::getModel('company/parser_task')->load($id);
    }

    public function updatePriceLists()
    {
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

                if($this->run($task, $adapter, $entity)) {
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
     * Run parsing process
     * @return bool
     * @throws Exception
     */
    public function run($task, $adapter, $entity)
    {
        $task->setProcessAt();
        $sheet = $adapter;
        //$params = array('object' => $this, 'field' => $field, 'value'=> $id);
        //$params = array_merge($params, $this->_getEventData());
        Mage::dispatchEvent($this->_eventPrefix.'_task_run_before', array($this->_eventObject => $this));
        // Iterate
        foreach($sheet as $row){
            //if($_lastPos = $this->checkLastPosition($sheet->key())){
            if(!is_null($_lastPos = $this->getLastRow()) && $_lastPos != $sheet->key()){
                $sheet->seek($_lastPos);
                continue;
            }
            $data = new Varien_Object(array(
                'company_id'            => $task->getCompanyId(),
                'task_id'               => $task->getId(),
                'line_num'              => $sheet->key(),
                'content'               => serialize($row),
                'raw_data'              => $row,
                'catalog_product_id'    => null,
                'company_product_id'    => null
            ));
            $entity->update($data);
            $task->setReadRowNum($sheet->key());
        }
        $task->setSpentTime();
        $task->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_COMPLETE);
        $task->save();
        Mage::dispatchEvent($this->_eventPrefix.'_task_run_after', array($this->_eventObject => $this));
        return true;
    }
}