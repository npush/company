<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/12/17
 * Time: 3:07 PM
 */
class Stableflow_Company_Model_Parser_Adapter_Xlsx extends Stableflow_Company_Model_Parser_Adapter_Office
{
    /**
     * Debug file name
     * @var string
     */
    protected $_logFileName = 'xlsx-parser.log';



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
            //->setReadFilter(new Stableflow_Company_Model_Parser_Adapter_Xlsx_ReaderFilter($init));
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
        $cellIterator->setIterateOnlyExistingCells(true);
        /** @var PHPExcel_Cell $cell */
        foreach($cellIterator as $cell){
            $format = (string)$cell->getStyle()->getNumberFormat()->getFormatCode();
            $rowData[$cell->getColumn()] = $cell->getFormattedValue();
        }
        return $rowData;
    }
}