<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/12/17
 * Time: 12:49 PM
 */

//include_once('lib/PHPExcel/Classes/PHPExcel/Reader/IReadFilter.php');

class Stableflow_Company_Model_Parser_Adapter_Xls_ReaderFilter implements PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= 1 && $row <= 7) {
            if(in_array($column,range('A','E'))){
                return true;
            }
        }return false;
    }
}