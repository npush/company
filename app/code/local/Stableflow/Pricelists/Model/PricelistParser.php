<?php

class Stableflow_Pricelists_Model_PricelistParser {

    protected $_pathToFile;

    /**
     * array(
     *   'columnName1' => 'columnNum1',
     *   'columnName2' => 'columnNum2',
     * )
     * @var $_map
     */
    protected $_map;
    protected $_data = [];

    /**
     * @param string $pathToFile
     * @param array $map
     */
    public function init($pathToFile = '/', $map) {
        $this->_map = $map;
        $this->_pathToFile = $pathToFile;
    }

    /**
     * Parse document
     * @param int $firstRow
     * @param $lastRow
     * @return array
     */
    public function parseFile($firstRow, $lastRow = false) {
        $includePath = Mage::getBaseDir() . "/lib/stableflow/PhpExcel/Classes";
        set_include_path(get_include_path() . PS . $includePath);

        try{
            $inputFileType = PHPExcel_IOFactory::identify($this->_pathToFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->_pathToFile);
        } catch(\PHPExcel_Exception $e) {
            die($e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = (int) ($lastRow) ? ($lastRow + $firstRow) - 1 : $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $data = [];

        for ($row = (int) $firstRow; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, TRUE);
            $fill = 0;
            foreach ($rowData[0] as $index => $value) {
                if ($type = array_search($index, $this->_map)) {
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