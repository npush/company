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
                str_replace(':', '-', $this->getProcessedAt()),
                $this->getScheduledOperationId(),
                $this->getEntity()
            ));
            $dirPath = Mage::getBaseDir('var') . DS . self::LOG_DIRECTORY . $dirName;
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0750, true);
            }
            $fileName = substr(strstr(self::LOG_DIRECTORY, DS), 1) . $dirName . $fileName . '.log';
            $this->_logInstance = Mage::getModel('core/log_adapter', $fileName);
        }
        $this->_logInstance->log($debugData);
        return $this;
    }

    /**
     * Log debug data to DB.
     *
     * @param mixed $debugData
     * @return Stableflow_Company_Model_Parser_Abstract
     */
    public function addDbLogComment($debugData)
    {
        return $this;
    }
}