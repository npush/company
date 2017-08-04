<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:10 PM
 */
class Stableflow_Company_Model_Parser_Adapter_Xls extends Stableflow_Company_Model_Parser_Adapter_Abstract
{


    /**
     * Source file handler.
     *
     * @var resource
     */
    protected $_fileHandler;

    /**
     * Method called as last step of object instance creation. Can be overrided in child classes.
     *
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    protected function _init()
    {
        $this->_fileHandler = fopen($this->_source, 'r');
        $this->rewind();
        return $this;
    }

    /**
     * Close file handler on shutdown
     */
    public function destruct()
    {
        if (is_resource($this->_fileHandler)) {
            fclose($this->_fileHandler);
        }
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


    public function init()
    {
        $includePath = Mage::getBaseDir() . "/lib/PhpExcel/Classes";
        set_include_path(get_include_path() . PS . $includePath);

        try {
            $inputFileType = PHPExcel_IOFactory::identify($this->_pathToFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->_pathToFile);
        } catch (\PHPExcel_Exception $e) {
            die($e->getMessage());
        }
    }

    function parse()
    {
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = (int)($lastRow) ? ($lastRow + $firstRow) - 1 : $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $data = [];

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

}