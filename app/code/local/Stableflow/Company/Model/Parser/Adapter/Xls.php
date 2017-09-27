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
     * Method called as last step of object instance creation.
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
            $this->_objReader->setLoadSheetsOnly($this->_settings->getSheetName());
            $this->_objPHPExcel = $this->_objReader->load($this->_source);

            $this->_colNames = $this->_initColNames();
            //$this->_sheet = $this->_objPHPExcel->getSheet($this->_settings->getCurrentSheetNum());
            $this->_sheet = $this->_objPHPExcel->getSheetByName($this->_settings->getSheetName());
            $this->_firstRow = $this->_settings->getStartRow();
            $this->_highestRow = $this->_sheet->getHighestRow();
            $this->_highestColumn = $this->_sheet->getHighestColumn();
            $this->_rowIterator = $this->_sheet->getRowIterator();
        } catch (PHPExcel_Exception $e){
            die($e->getMessage());
        }
    }

    protected function _initColNames()
    {
        $tmp = $this->_settings->getFieldMap();
        array_walk($tmp, function(&$value, $key){
            if($value == ''){
                $value = null;
            }
            $value = strtoupper($value);
        });
        return $tmp;
    }

    /**
     * array = array(
     * 'A' => value
     * 'B' => value
     * ....
     * )
     * @return array
     */
    protected function _getRow()
    {
        $rowData = array();
        $row = $this->_rowIterator->current();
        $cellIterator = $row->getCellIterator();
        // Iterate only on
        //$cellIterator->setIterateOnlyExistingCells(true);
        foreach($cellIterator as $cell){
            $format = (string)$cell->getStyle()
                ->getNumberFormat();
            $rowData[$cell->getColumn()] = $cell->getFormattedValue();
        }
        return $rowData;
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

    public function current()
    {
        $tmp = $this->_colNames;
        array_walk($tmp, function (&$value, $key, $ar2){
            $value = $ar2[$value];
        }, $this->_currentRow);
        return $tmp;
    }

    public function next()
    {
        if($this->_rowIterator->key() <= $this->_highestRow){
            $this->_rowIterator->next();
            $this->_currentKey = $this->_rowIterator->key();
            $this->_currentRow = $this->_getRow();
        }else{
            $this->_currentKey = null;
        }
    }

    public function rewind()
    {
        $this->_rowIterator->seek($this->_firstRow);
        $this->_currentKey = $this->_rowIterator->key();
        $this->_currentRow = $this->_getRow();
    }

    public function seek($position)
    {
        if($position <= $this->_highestRow){
            $this->_rowIterator->seek($position);
            $this->_currentKey = $this->_rowIterator->key();
            $this->_currentRow = $this->_getRow();
        }else{
            throw new OutOfBoundsException(Mage::helper('company')->__('Invalid seek position'));
        }
    }

    public function valid()
    {
        return $this->_currentKey <= $this->_highestRow;
    }

    /**
     * Validate config array
     *
     * @return bool
     */
    public function validateConfig()
    {
        return true;
    }
}