<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/12/17
 * Time: 12:49 PM
 */

//include_once('lib/PHPExcel/Classes/PHPExcel/Reader/IReadFilter.php');

class Stableflow_Company_Model_Parser_Adapter_Xlsx_ReaderFilter implements PHPExcel_Reader_IReadFilter
{
    protected $_range;

    public function __construct($range)
    {
        $this->_range = $range;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if(in_array($column, $this->_range)){
            return true;
        }
        return false;
    }
}