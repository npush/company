<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/12/17
 * Time: 3:07 PM
 */
class Stableflow_Company_Model_Parser_Adapter_Xlsx extends Stableflow_Company_Model_Parser_Adapter_Xls
{

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