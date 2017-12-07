<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */
require_once Mage::getBaseDir() . "/lib/PHPExcel/Classes/PHPExcel.php";

abstract class Stableflow_Company_Model_Parser_Adapter_Office extends Stableflow_Company_Model_Parser_Adapter_Abstract
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
    protected $_logFileName;

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

    /** @var PHPExcel_Worksheet_RowCellIterator */
    protected $_rowIterator;

    /**
     * Close file handler on shutdown
     */
    function destruct()
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
        foreach($this->_colNames as $key => $val){
            $row[$key] = array_key_exists($val,$this->_currentRow) ? $this->_currentRow[$val] : null;
        }
        $row['manufacturer'] = $this->getSettings()->getSheet($this->_currentSheetIdx)->getManufacturer();
        return $row;
    }

    /**
     * Next element
     */
    public function next()
    {
        $this->_rowIterator->next();
        $this->_currentKey = $this->_rowIterator->key();
        $this->_currentRow = $this->_getRow();
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
        }
        if($this->_currentSheetIdx == $_sheetIdx && $_row <= $this->_highestRow){
            $this->_rowIterator->seek($_row);
            $this->_currentKey = $this->_rowIterator->key();
            $this->_currentRow = $this->_getRow();
        }else{
            throw new OutOfBoundsException(Mage::helper('company')->__('Invalid seek position'));
        }
    }

    protected function _initSheets()
    {
        $this->_sheetsIdx = $this->getSettings()->getSheetsIds();
        reset($this->_sheetsIdx);
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
        array_walk($this->getSettings()->getSheet($sheetIdx)->getFieldMap(), function($value, $key){
            if($value == ''){
                $this->_colNames[$key] = null;
            }
            $this->_colNames[$key] = strtoupper($value);
        }, $this->_colNames);

        $this->_sheet = $this->_objPHPExcel->getSheet($sheetIdx);
        //$this->_sheet = $this->_objPHPExcel->getSheetByName($this->_settings->getSheetName());
        $this->_firstRow = $this->getSettings()->getSheet($sheetIdx)->getStartRow();
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