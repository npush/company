<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */
require_once Mage::getBaseDir() . "/lib/PHPExcel/Classes/PHPExcel.php";

class Stableflow_Company_Model_Parser_Adapter_Xls extends Stableflow_Company_Model_Parser_Adapter_Abstract
{

    /**
     * Default localisation
     * @var string
     */
    protected $_locale = 'ru';

    /**
     * Debug file name
     * @var string
     */
    protected $_logFileName = 'xls-parser.log';

    /** @var PHPExcel */
    protected $_objPHPExcel;

    /** @var PHPExcel_Reader_Abstract */
    protected $_objReader;

    /**
     * Current sheet index
     * @var int
     */
    protected $_currentSheetIdx;

    /**
     * Sheets index
     * @var  array
     */
    protected $_sheetsIdx;

    /**
     * Current sheet
     * @var
     */
    protected $_sheet;

    /**
     * The number of first row, that have correspond data
     * @var int
     */
    protected $_firstRow;

    /**
     * Max row number
     * @var int
     */
    protected $_highestRow;

    /**
     * Max column number
     * @var int
     */
    protected $_highestColumn;

    /** @var PHPExcel_Worksheet_Row */
    protected $_rowIterator;

    /**
     * Method called as last step of object instance creation.
     *
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    protected function _init()
    {
        Mage::log("Initialize parser", Zend_Log::INFO, $this->_logFileName);
        $this->init();
        return $this;
    }

    /**
     * Initialize PHPExcel Reader
     */
    protected function init()
    {
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
    }

    /**
     * Close file handler on shutdown
     */
    function __destruct()
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
        $tmp = $this->_colNames;
        array_walk($tmp, function (&$value, $key, $ar2){
            $value = $ar2[$value];
        }, $this->_currentRow);
        // !!!!!
        $tmp['manufacturer'] = $this->getSettings()->getManufacturer($this->_currentSheetIdx);
        return $tmp;
    }

    /**
     * Next element
     */
    public function next()
    {
        $this->_rowIterator->next();
        $this->_currentKey = $this->_rowIterator->key();
        $this->_currentRow = $this->_getRow();

//        if($this->_rowIterator->key() <= $this->_highestRow){
//            $this->_rowIterator->next();
//            $this->_currentKey = $this->_rowIterator->key();
//            $this->_currentRow = $this->_getRow();
//        }else{
//            // end of page
//            if(!$this->nextSheet()) {
//                $this->_currentKey = null;
//            }
//        }
    }

    /**
     * Rewind to start position
     */
    public function rewind()
    {
        $this->rewindSheets();
        $this->_rowIterator->seek($this->_firstRow);
        $this->_currentKey = $this->_rowIterator->key();
        $this->_currentRow = $this->_getRow();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if(!($this->_currentKey <= $this->_highestRow)) {
            return $this->nextSheet() ? true : false;
        }
        return true;
    }

    /**
     * Return the key of the current element.
     *
     * @return int More than 0 integer on success, integer 0 on failure.
     */
    public function key()
    {
        return $this->_currentSheetIdx.':'.$this->_currentKey;
    }

    /**
     * Seeks to a position.
     *
     * @param string $position The position to seek to.
     * @return void
     */
    public function seek($position)
    {
        list($_sheetIdx, $_row) =  explode(':', $position);
        if($this->_currentSheetIdx != $_sheetIdx && in_array($_sheetIdx, $this->_sheetsIdx)){
            $this->setSheet($_sheetIdx);
        }elseif($this->_currentSheetIdx == $_sheetIdx && $_row <= $this->_highestRow){
            $this->_rowIterator->seek($_row);
            $this->_currentKey = $this->_rowIterator->key();
            $this->_currentRow = $this->_getRow();
        }else{
            throw new OutOfBoundsException(Mage::helper('company')->__('Invalid seek position'));
        }
    }

    protected function _initSheets()
    {
        if($this->getSettings()->getSheetsCont() > 1){
            $this->_sheetsIdx = $this->getSettings()->getSheetsIds();
            reset($this->_sheetsIdx);
        }
        $this->setSheet(current($this->_sheetsIdx));
    }

    /**
     * Switch to net page
     * @return bool
     */
    protected function nextSheet()
    {
        $num = next($this->_sheetsIdx);
        if(!$num){
            // end of sheets
            return false;
        }
        $this->setSheet($num);
        return true;
    }

    protected function rewindSheets()
    {
        reset($this->_sheetsIdx);
        $this->setSheet(current($this->_sheetsIdx));
    }

    /**
     * Select document sheet by index
     * @param $sheetIdx int Sheet index
     * @return $this
     */
    protected function setSheet($sheetIdx)
    {
        array_walk($this->getSettings()->getFieldMap($sheetIdx), function($value, $key){
            if($value == ''){
                $this->_colNames[$key] = null;
            }
            $this->_colNames[$key] = strtoupper($value);
        }, $this->_colNames);

        $this->_sheet = $this->_objPHPExcel->getSheet($sheetIdx);
        //$this->_sheet = $this->_objPHPExcel->getSheetByName($this->_settings->getSheetName());
        $this->_firstRow = $this->getSettings()->getStartRow($sheetIdx);
        $this->_highestRow = $this->_sheet->getHighestRow();
        $this->_highestColumn = $this->_sheet->getHighestColumn();
        $this->_rowIterator = $this->_sheet->getRowIterator();

        $this->_rowIterator->seek($this->_firstRow);
        $this->_currentKey = $this->_rowIterator->key();
        $this->_currentRow = $this->_getRow();
        $this->_currentSheetIdx = $sheetIdx;
        return $this;
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
        /** @var PHPExcel_Cell $cell */
        foreach($cellIterator as $cell){
            $format = (string)$cell->getStyle()->getNumberFormat()->getFormatCode();
            $rowData[$cell->getColumn()] = $cell->getFormattedValue();
        }
        return $rowData;
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