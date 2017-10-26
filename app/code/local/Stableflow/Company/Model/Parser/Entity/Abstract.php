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
//        $entityType = Mage::getSingleton('eav/config')->getEntityType($this->getEntityTypeCode());
//        $this->_entityTypeId    = $entityType->getEntityTypeId();
//        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_messagesEntity = Mage::getSingleton('company/parser_log_message');
    }

    /**
     * Add error with corresponding current data source row number.
     *
     * @param string $errorCode Error code or simply column name
     * @param string $errorRowNum Page and Row number.
     * @param string $colName OPTIONAL Column name.
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function addRowError($errorCode, $errorRowNum, $colName = null)
    {
        $this->_errors[$errorCode][] = array($errorRowNum, $colName); // one added for human readability
        $this->_invalidRows[$errorRowNum] = true;
        $this->_errorsCount ++;

        return $this;
    }

    /**
     * Add message template for specific error code from outside.
     *
     * @param string $errorCode Error code
     * @param string $message Message template
     * @return Stableflow_Company_Model_Parser_Entity_Abstract
     */
    public function addMessage($errorCode, $message)
    {
        $this->_messages[$errorCode] = $message;

        return $this;
    }

    /**
     * Returns error information grouped by error types and translated (if possible).
     *
     * @return array
     */
    public function getErrorMessages()
    {
        $translator = Mage::helper('company');
        $messages   = array();

        foreach ($this->_errors as $errorCode => $errorRows) {
            if (isset($this->_messageTemplates[$errorCode])) {
                $errorCode = $translator->__($this->_messageTemplates[$errorCode]);
            }
            foreach ($errorRows as $errorRowData) {
                $key = $errorRowData[1] ? sprintf($errorCode, $errorRowData[1]) : $errorCode;
                $messages[$key][] = $errorRowData[0];
            }
        }
        return $messages;
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
     * Returns model notices.
     *
     * @return array
     */
    public function getNotices()
    {
        return $this->_notices;
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