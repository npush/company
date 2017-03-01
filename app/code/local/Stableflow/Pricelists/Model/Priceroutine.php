<?php

/**
 * Description of Stableflow_Company_Model_Priceroutine
 *
 * @author bokorok
 *
 * @property Stableflow_Pricelists_Model_Pricelist $priceList
 * @property string $articleNumber
 * @property string $manufacturer
 * @property string $price
 * @property int $availability
 * @property int $savedItems
 * @property int $skippedItems
 * @property string $filePath
 * @property int $companyConstant
 * @property array $config
 * @property int $firstRow
 * @property int $currentRow
 * @property string $errors
 */
class Stableflow_Pricelists_Model_Priceroutine extends Mage_Core_Model_Abstract {

    // companies constant
    const COMPANY_PRICE = 1; // ПРАЙС_2.xls
    const COMPANY_BJB = 2; // BJB_прайс 14.06.12.xls
    const COMPANY_TRIDONIC = 3; // Tridonic_прайс 16.06.12.xls
    const COMPANY_LEGRAND = 4; // LEGRAND_прайслист УКРАИНА v.02.04.2012.xls
    // file fields
    public $articleNumber = null;
    public $manufacturer = null;
    public $price = null;
    public $availability = null;
    // count items
    public $savedItems;
    public $skippedItems;
    // settings
    protected $filePath;
    protected $companyConstant;
    protected $config;
    protected $firstRow;
    // row
    protected $currentRow;
    protected $errors = '';

    /**
     * @param string $filePath
     * @param integer $companyConstant
     */
    public function init($filePath, $companyConstant) {
        $this->filePath = $filePath;
        $this->companyConstant = $companyConstant;
        $this->getConfig();
        $this->savedItems = 0;
        $this->skippedItems = 0;
    }

    protected function getConfig() {
        switch ($this->companyConstant) {
            case self::COMPANY_PRICE:
                $this->config = [
                    0 => &$this->manufacturer,
                    1 => &$this->articleNumber,
                    4 => &$this->availability,
                    6 => &$this->price,
                ];
                $this->firstRow = 4;
                break;
            case self::COMPANY_BJB:
                $this->config = [
                    0 => &$this->articleNumber,
                    6 => &$this->price,
                    7 => &$this->availability,
                ];
                $this->firstRow = 10;
                break;
            case self::COMPANY_TRIDONIC:
                $this->config = [
                    0 => &$this->articleNumber,
                    7 => &$this->price,
                    9 => &$this->availability,
                ];
                $this->firstRow = 10;
                break;
            case self::COMPANY_LEGRAND:
                $this->config = [
                    0 => &$this->articleNumber,
                    7 => &$this->price,
                    8 => &$this->availability,
                ];
                $this->firstRow = 6;
                break;
        }
    }

    /**
     * @return array
     */
    public function getCurrentAssocRowData() {
        return [
            'articleNumber' => $this->articleNumber,
            'manufacturer' => $this->manufacturer,
            'price' => $this->price,
            'availability' => $this->availability,
        ];
    }

    /**
     * @return bool
     */
    protected function checkItem() {
        $this->articleNumber;
        // to do

        $query = rand(0, 1);
        $result = $query; // if item exist must be TRUE

        return $result;
    }

    protected function normalizeData() {
        if (!is_null($this->price)) {
            $this->price = round($this->price, 2);
        }

        if (is_null($this->availability) || !$this->availability) {
            $this->availability = 0;
        }
    }

    /**
     * @return bool
     */
    protected function validate() {
        $is_valid = true;
        $this->normalizeData();
        if (is_null($this->articleNumber)) {
            $is_valid = false;
            $this->errors .= 'article number in empty';
        }
        if (!$this->checkItem()) {
            $is_valid = false;
            $this->errors .= 'item doesn\'t exist';
        }

        if (is_null($this->price)) {
            $is_valid = false;
            $this->errors .= 'price is empty';
        }

        if (!$is_valid) {
            $this->writeLog();
        }

        return $is_valid;
    }

    /**
     * @return int
     */
    protected function updatePrice() {
        // to do 
        echo 'Row: ' . $this->currentRow . ', articleNumber: "' . $this->articleNumber . '" is updated' . PHP_EOL;

        return $this->savedItems++;
    }

    /**
     * @return int
     */
    protected function writeLog() {
        // to do 
        echo 'Row: ' . $this->currentRow . ', articleNumber: "' . $this->articleNumber . '" - ' . $this->errors . PHP_EOL;

        $this->errors = '';
        return $this->skippedItems++;
    }

    /**
     * @return array if file exist else FALSE
     */
    public function parseFile() {
        $includePath = Mage::getBaseDir() . "/lib/stablwflow/PhpExcel/Classes";
        set_include_path(get_include_path() . PS . $includePath);
        try {
            $inputFileType = PHPExcel_IOFactory::identify($this->filePath);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->filePath);

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = $this->firstRow; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $this->currentRow = $row;
            $skip = false;

            foreach ($rowData[0] as $index => $value) {
                if ($index == 0 && empty($value)) {
                    echo $this->currentRow. "<br>";
                    $skip = true;
                    continue;
                }
                $this->config[$index] = $value;
            }

            if (!$skip/* && $this->validate()*/) {
                $this->updatePrice();
            } else {
                $this->skippedItems++;
            }
        }

        return [
            'saved' => $this->savedItems,
            'skipped' => $this->skippedItems,
            'total' => $this->savedItems + $this->skippedItems,
        ];
    }

}
