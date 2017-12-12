<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:01 PM
 */
abstract class Stableflow_Company_Model_Parser_Entity_Abstract
{

    /**
     * Has data process validation done?
     *
     * @var bool
     */
    protected $_dataValidated = false;

    /**
     * Entity type id.
     *
     * @var int
     */
    protected $_entityTypeId;

    /**
     * Error codes with arrays of corresponding row numbers.
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Error counter.
     *
     * @var int
     */
    protected $_errorsCount = 0;

    /**
     * Array of invalid rows numbers.
     *
     * @var array
     */
    protected $_invalidRows = array();

    /**
     * Parsing process messages.
     *
     * @var array Stableflow_Company_Model_Parser_Log_Message
     */
    protected $_messages = array();

    /**
     * Entity model parameters.
     *
     * @var array
     */
    protected $_parameters = array();

    /**
     * Number of entities processed by validation.
     *
     * @var int
     */
    protected $_processedEntitiesCount = 0;

    /**
     * Number of rows processed by validation.
     *
     * @var int
     */
    protected $_processedRowsCount = 0;

    /**
     * Array of numbers of validated rows as keys and boolean TRUE as values.
     *
     * @var array
     */
    protected $_validatedRows = array();

    /**
     * Source model.
     *
     * @var Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    protected $_source;

    /**
     * Task model.
     *
     * @var Stableflow_Company_Model_Parser_Task
     */
    protected $_task;

    /**
     * @var Stableflow_Company_Model_Parser_Log_Message
     */
    protected $_messagesEntity;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $entityType = Mage::getSingleton('eav/config')->getEntityType($this->getEntityTypeCode());
        $this->_entityTypeId    = $entityType->getEntityTypeId();
//        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_messagesEntity = Mage::getSingleton('company/parser_log_message');
    }

    /**
     * Add error with corresponding current data source row number.
     *
     * @param string $statusCode Error code or simply column name
     * @param array $row
     * @param array $processData
     * @param int $errorRowNum Row number.
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function addRowError($statusCode, $row, $company_id, $errorRowNum)
    {
        $this->_errors[$statusCode][] = array(
            'row_number' => $errorRowNum,
            'company_id' => $company_id,
            'content' => $row
        );
        $this->_invalidRows[$errorRowNum] = true;
        $this->_errorsCount ++;
        return $this;
    }

    public function getErrors()
    {
        $errors = array();
        foreach ($this->_errors as $statusCode => $errorRows){
            foreach ($errorRows as $errorRowData) {
                $key = sprintf("%s at %s",$statusCode, $errorRowData['row_number']);
                $errors[$statusCode][$errorRowData['row_number']] = $errorRowData;
            }
        }
        return $errors;
    }

    /**
     * Add message template for specific status code from outside.
     *
     * @param string $type Error code or simply column name
     * @param string $statusCode
     * @param array $row
     * @param array $processData
     * @param string $rowNum
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function addMessage($statusCode, $row, $company_id, $rowNum)
    {
        $this->_messages[$statusCode][] = array(
            'row_number' => $rowNum,
            'company_id' => $company_id,
            'content' => $row
        );

        return $this;
    }

    /**
     * Returns information grouped by status code and translated (if possible).
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = array();

        foreach ($this->_messages as $statusCode => $rows){
            foreach ($rows as $rowData) {
                $messages[$statusCode][$rowData['row_number']] = $rowData;
            }
        }
        return $messages;

//        foreach ($this->_errors as $statusCode => $errorRows) {
//            if (isset($this->_messages[$statusCode])) {
//                $statusCode = Mage::helper('company')->__($this->_messages[$statusCode]);
//            }
//            foreach ($errorRows as $errorRowData) {
//                $key = $errorRowData[1] ? sprintf($statusCode, $errorRowData[1]) : $statusCode;
//                $messages[$key][] = $errorRowData[0];
//            }
//        }
//        return $messages;
    }

    /**
     * Returns error counter value.
     *
     * @return int
     */
    public function getErrorsCount()
    {
        return $this->_errorsCount;
    }

    /**
     * Returns invalid rows count.
     *
     * @return int
     */
    public function getInvalidRowsCount()
    {
        return count($this->_invalidRows);
    }

    /**
     * Returns number of checked entities.
     *
     * @return int
     */
    public function getProcessedEntitiesCount()
    {
        return $this->_processedEntitiesCount;
    }

    /**
     * Returns number of checked rows.
     *
     * @return int
     */
    public function getProcessedRowsCount()
    {
        return $this->_processedRowsCount;
    }

    /**
     * Source object getter.
     *
     * @throws Exception
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    public function getSource()
    {
        if (!$this->_source) {
            Mage::throwException(Mage::helper('company')->__('Source is not set'));
        }
        return $this->_source;
    }


    /**
     * Source model setter.
     *
     * @param Stableflow_Company_Model_Parser_Adapter_Abstract $source
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function setSource(Stableflow_Company_Model_Parser_Adapter_Abstract $source)
    {
        $this->_source = $source;
        $this->_dataValidated = false;

        return $this;
    }

    /**
     * @return Stableflow_Company_Model_Parser_Log_Message
     * @throws Mage_Core_Exception
     */
    public function getMessageEntity()
    {
        if(!$this->_messagesEntity){
            Mage::throwException(Mage::helper('company')->__('Message entity is not set'));
        }
        return $this->_messagesEntity;
    }

    /**
     * Task object getter.
     *
     * @throws Exception
     * @return Stableflow_Company_Model_Parser_Task
     */
    public function getTask()
    {
        if (!$this->_task) {
            Mage::throwException(Mage::helper('company')->__('Task is not set'));
        }
        return $this->_task;
    }

    public function setTask(Stableflow_Company_Model_Parser_Task $task)
    {
        $this->_task = $task;
        return $this;
    }

    /**
     * Validate data.
     *  Check Fields (Price Code Manufacturer Company)
     * @throws Exception
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function validateData()
    {
        if (!$this->_dataValidated) {
            Mage::throwException(
                Mage::helper('company')->__('Can not find required columns: %s', implode(', ', $colsAbsent))
            );
        }
    }

    abstract function runParsingProcess();

}