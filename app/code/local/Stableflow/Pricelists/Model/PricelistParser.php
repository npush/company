<?php

class Stableflow_Pricelists_Model_PricelistParser {

    protected $_pathToFile;
    protected $_config;
    protected $_data = [];

    public function init($pathToFile, $config) {
        $this->_config = $config;
        $this->_pathToFile = $pathToFile;
    }

    /**
     * Parse document
     * @param int $firstRow
     * @param $lastRow
     * @return array
     */
    public function parseFile($firstRow, $lastRow = false) {
        $includePath = Mage::getBaseDir() . "/lib/rulletka/PhpExcel/Classes";
        set_include_path(get_include_path() . PS . $includePath);
        try{
            $inputFileType = PHPExcel_IOFactory::identify($this->_pathToFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->_pathToFile);
        } catch(\PHPExcel_Exception $e) {
            die($e->getMessage());
        }
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = (int)($lastRow) ? ($lastRow + $firstRow) - 1 : $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $data = [];

        for ($row = $firstRow; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, TRUE);
            $fill = 0;
            foreach ($rowData[0] as $index => $value) {
                if ($type = array_search($index, $this->_config)) {
                    if (!empty($value)) {
                        $fill++;
                    }
                    $data[$row][$type] = ($type == 'price') ? round($value, 2) : $value;
                }
            }
            if($fill == 0) {
                unset($data[$row]);
            }
        }
        $this->_data = $data;

        return $data;
    }

}