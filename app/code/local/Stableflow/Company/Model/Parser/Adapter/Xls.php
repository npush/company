<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */
require_once Mage::getBaseDir() . "/lib/PHPExcel/Classes/PHPExcel/IOFactory.php";

class Stableflow_Company_Model_Parser_Adapter_Xls extends Stableflow_Company_Model_Parser_Adapter_Abstract
{

    /**
     * Debug file name
     * @var string
     */
    protected $_logFileName = 'xls-parser.log';

    protected $_objPHPExcel = null;

    protected $_objReader = null;

    /**
     * Current sheet
     * @var
     */
    protected $_sheet;

    /**
     * The number of row, that have correspond data
     * @var int
     */
    protected $_firstRow;

    /**
     * Max row number
     * @var int
     */
    protected $_highestRow = null;

    /**
     * Max column number
     * @var int
     */
    protected $_highestColumn = null;

    protected $_rowIterator = null;

    /**
     * Method called as last step of object instance creation. Can be overrided in child classes.
     *
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    protected function _init()
    {
        Mage::log("Initialize parser", Zend_Log::INFO, $this->_logFileName);
        $this->init();
        $this->rewind();
        return $this;
    }

    /**
     * Initialize PHPExcel Reader
     */
    protected function init()
    {
        try {
            $inputFileType = PHPExcel_IOFactory::identify($this->_source);
            $this->_objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $this->_objReader->setReadDataOnly(true);
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
            //$this->_objReader->setReadFilter(new Stableflow_Company_Model_Parser_Adapter_Xls_ReaderFilter($init));
            //$this->_objReader->setLoadSheetsOnly( array("Sheet 1") );
            $this->_objPHPExcel = $this->_objReader->load($this->_source);
        } catch (PHPExcel_Exception $e) {
            die($e->getMessage());
        }
        $this->_colNames = array_flip($this->_settings->getFieldMap());
        $this->_sheet = $this->_objPHPExcel->getSheet($this->_settings->getCurrentSheetNum());
        $this->_firstRow = $this->_settings->getStartRow();
        $this->_highestRow = $this->_sheet->getHighestRow();
        $this->_highestColumn = $this->_sheet->getHighestColumn();
        $this->_rowIterator = $this->_sheet->getRowIterator();
    }

    /**
     * Close file handler on shutdown
     */
    public function destruct()
    {
        if ($this->_objPHPExcel) {
            $this->_objPHPExcel->disconnectWorksheets();
            unset($this->_objPHPExcel);
        }
        $memUse = sprintf(" Peak memory usage: %d MB", (memory_get_peak_usage(true) / 1024 / 1024));
        Mage::log($memUse, Zend_Log::INFO, $this->_logFileName);
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        $temp = array();
        $row = $this->_rowIterator->current();
        $cellIterator = $row->getCellIterator();
        //$cellIterator->setIterateOnlyExistingCells(true);
        foreach($cellIterator as $cell){
            $temp[$this->_colNames[strtolower($cell->getColumn())]] = $cell->getCalculatedValue();
        }
//        $ar1 = $this->_colNames;
//        array_walk($ar1, function (&$value, $key, $temp){
//            $value = $temp[strtoupper($value)];
//        }, $temp);
//        $this->_currentRow = $ar1;
        $this->_currentRow = $temp;
        return $this->_currentRow;
    }

    /**
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_rowIterator->next();
        $this->_currentKey = $this->_rowIterator->key();
    }

    public function rewind()
    {
        $this->_rowIterator->seek($this->_firstRow);
        $this->_currentKey = $this->_rowIterator->key();
    }

    public function seek($position)
    {
        $this->_rowIterator->seek($position);
        $this->_currentKey = $this->_rowIterator->key();
    }

    public function valid()
    {
        return !empty($this->_currentRow);
    }

    public function validateConfig(){}

    /**
     * @return bool
     */
    protected function validateRow($row) {
        switch ($column) {
            case 'price':
                return (Zend_Validate::is($value , 'Int') || Zend_Validate::is($value , 'Float'));
                break;

            case 'code':
                return Zend_Validate::is($value , 'NotEmpty');
                break;
        }

        return true;
    }
}