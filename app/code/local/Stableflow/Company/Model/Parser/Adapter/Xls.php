<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */

class Stableflow_Company_Model_Parser_Adapter_Xls extends Stableflow_Company_Model_Parser_Adapter_Office
{

    /**
     * Debug file name
     * @var string
     */
    protected $_logFileName = 'xls-parser.log';



    /**
     * Initialize PHPExcel Reader
     * @throws PHPExcel_Exception
     */
    protected function _init()
    {
        Mage::log("Initialize parser", Zend_Log::INFO, $this->_logFileName);
        try {
            PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip);
            PHPExcel_Settings::setLocale($this->_locale);
            $this->_objReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($this->_source))
                ->setReadDataOnly(true);
            //->setReadFilter(new Stableflow_Company_Model_Parser_Adapter_Xls_ReaderFilter($init));
            $sheetNames = $this->_objReader->listWorksheetNames($this->_source);
            $sheetsIds = $this->getSettings()->getSheetsIds();
            array_walk($sheetsIds, function(&$value, $key, $names){
                $value = $names[$value];
            }, $sheetNames);
            //$this->_objReader->setLoadSheetsOnly($sheetsIds);
            $this->_objReader->setLoadAllSheets();
            $this->_objPHPExcel = $this->_objReader->load($this->_source);
            $this->_initSheets();
        } catch (PHPExcel_Exception $e){
            Mage::log($e->getMessage(), null, $this->_logFileName);
        }
        return $this;
    }
}