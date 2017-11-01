<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/11/17
 * Time: 3:10 PM
 */
class Stableflow_Company_Model_Parser_Abstract extends Varien_Object
{
    /**
     * Log directory
     *
     */
    const LOG_DIRECTORY = 'log/price_parser/';

    /**
     * Loger instance
     * @var Mage_Core_Model_Log_Adapter
     */
    protected $_logInstance;

    /**
     * Fields that should be replaced in debug with '***'
     *
     * @var array
     */
    protected $_debugReplacePrivateDataKeys = array();

    /**
     * Loger instance
     * @var Stableflow_Company_Model_Parser_Log
     */
    protected $_logDbInstance;

    /**
     * Log debug data to file.
     * Log file dir: var/log/price_parser/%Y/%m/%d/%time%_%operation_type%_%entity_type%.log
     *
     * @param mixed $debugData
     * @return Stableflow_Company_Model_Parser_Abstract
     */
    public function addLogComment($debugData)
    {
        if (!$this->_logInstance) {
            $dirName  = date('Y' . DS .'m' . DS .'d' . DS);
            $fileName = join('_', array(
                str_replace(':', '-', $this->getProcessAt()),
                $this->getScheduledOperationId(),
                $this->getEntity()
            ));
            $dirPath = Mage::getBaseDir('var') . DS . self::LOG_DIRECTORY . $dirName;
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0750, true);
            }
            $fileName = substr(strstr(self::LOG_DIRECTORY, DS), 1) . $dirName . $fileName . '.log';
            $this->_logInstance = Mage::getModel('core/log_adapter', $fileName);
                //->setFilterDataKeys($this->_debugReplacePrivateDataKeys);
        }
        $this->_logInstance->log($debugData);
        return $this;
    }

    /**
     * Log Parsing info data to DB.
     *
     * @param array $messages array ('notice, error, success') Stableflow_Company_Model_Parser_Log_Message_Abstract
     * @return Stableflow_Company_Model_Parser_Abstract
     */
    public function addDbParserLog($messages)
    {
        if (!$this->_logInstance) {
            $this->_logDbInstance = Mage::getModel('company/parser_log');
        }
        Mage::log($messages, null, 'addDbParserLog.log');
//        foreach ($messages as $status => $message){
//            $this->_logDbInstance->log($status, $message);
//        }
//        $this->_logDbInstance->save();
        return $this;
    }
}