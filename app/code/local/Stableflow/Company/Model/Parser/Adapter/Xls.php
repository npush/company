<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */
class Stableflow_Company_Model_Parser_Adapter_Xls extends Stableflow_Company_Model_Parser_Adapter_Abstract
{

    protected $_logFileName = 'xls-parser.log';

    protected $_objPHPExcel = null;

    protected $_objReader = null;

    protected $_sheet;

    protected $_firstRow;

    protected $_highestRow;

    protected $_highestColumn;

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
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next()
    {}

    public function rewind()
    {}

    public function seek($position)
    {}

    /**
     * Initialize PHPExcel Reader
     */
    protected function init()
    {
        require_once Mage::getBaseDir() . "/lib/PHPExcel/Classes/PHPExcel/IOFactory.php";
        try {
            $inputFileType = PHPExcel_IOFactory::identify($this->_source);
            $this->_objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $this->_objReader->setReadDataOnly(true);
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
            $this->_objPHPExcel = $this->_objReader->load($this->_source);
        } catch (PHPExcel_Exception $e) {
            die($e->getMessage());
        }
        $this->_sheet = $this->_objPHPExcel->getSheet(0);
        $this->_firstRow = $this->_settings->getStartRow();
        $this->_highestRow = $this->_sheet->getHighestRow();
        $this->_highestColumn = $this->_sheet->getHighestColumn();
    }

    function parse()
    {
        Mage::log("Parse...", Zend_Log::INFO, $this->_logFileName);
        $firstRow = $this->_settings->getStartRow(0);
        $sheet = $this->_objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $data = [];

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
            // even if it is not set.
            // By default, only cells
            // that are set will be
            // iterated.
            foreach ($cellIterator as $cell) {
                $data[]= $cell->getValue();
            }
        }

        for ($row = (int) $firstRow; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $skip = false;
            foreach ($rowData[0] as $index => $value) {
                if ($column = array_search($index, $this->_map)) {
                    if ($this->validate($column, $value)) {
                        $value = $this->normalizeData($column, $value);
                        $data[$row][$column] = $value;
                    } else {
                        $skip = true;
                        continue;
                    }
                }
            }

            if ($skip) {
                $this->skippedItems++;
                unset($data[$row]);
                Mage::log("Not valid data in row {$row}", null, 'updating_price.log');
            }
        }

        $this->_data = $data;

        return $data;
    }

    public function validateConfig(){}
}