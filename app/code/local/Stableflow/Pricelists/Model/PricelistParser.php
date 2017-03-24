<?php

/**
 * @property $price
 * @property $code
 * */
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

    public $skippedItems = 0;
    public $savedItems = 0;

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
    public function parseFile($firstRow = 0, $lastRow = false) {
        $includePath = Mage::getBaseDir() . "/lib/stableflow/PhpExcel/Classes";
        set_include_path(get_include_path() . PS . $includePath);

        try {
            $inputFileType = PHPExcel_IOFactory::identify($this->_pathToFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->_pathToFile);
        } catch (\PHPExcel_Exception $e) {
            die($e->getMessage());
        }

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

    /**
     * @return bool
     */
    protected function validate($column, $value) {
        switch ($column) {
            case 'price':
                return (Zend_Validate::is($value , 'Int') || Zend_Validate::is($value , 'Float'));
                break;

            case 'code':
                return Zend_Validate::is($value , 'NotEmpty');
                break;
        }

        return true;
    }

    /**
     * Normalize format data
     * @var string $column
     * @var mixed $value
     * @return mixed
     * */
    protected function normalizeData($column, $value) {
        switch ($column) {
            case 'price':
                return Mage::helper('core')->currency($value,false,true);
                break;
            default:
                return $value;
        }
    }


    /**
     * Updating price
     * */
// First variant
//    public function updatePrice() {
//
//        $newProductData = $this->_data;
//        $status = false;
//        if (!empty($newProductData)) {
//            $status = true;
//
//            foreach ($newProductData as $attributes) {
//                /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
//                $productCollection = Mage::getModel('catalog/product')->getCollection();
//                $products = $productCollection
//                    ->addAttributeToSelect(array('manufacturer_number', 'entity_id'))
//                    ->addAttributeToFilter('manufacturer_number', array('eq' => $attributes['code']))
//                    ->load();
//
//                if (count($products) == 0) {
//                    Mage::log("Product with {$attributes['code']} does`t exist", null, 'updating_price.log');
//                    $this->skippedItems++;
//                    continue;
//                }
//$start = microtime(true);
//                $product = $products->getFirstItem();
//                $productId = $product->getData('entity_id');
//
//                /** @var Stableflow_Company_Model_Product $companyProduct */
//                $companyProduct = Mage::getModel('company/product');
//                $companyProduct = $companyProduct->load($productId);
//
//
//                /** @var Magento_Db_Adapter_Pdo_Mysql $connection */
//                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
//
//                $result = $connection->update(
//                    'company_product_entity_decimal',
//                    array('value' => $attributes['price']),
//                    array("entity_id = {$companyProduct->getId()}", "entity_type_id = {$companyProduct->getEntityTypeId()}")
//                );
//
//                if ($result > 0) {
//                    $this->savedItems++;
//                } else {
//                    Mage::log("Product not saved", null, 'updating_price.log');
//                }
//$res[] = microtime(true) - $start;
//            }
//echo (array_sum($res) / count($res));
//        }
//
//        return [
//            'status' => $status,
//            'skipped' => $this->skippedItems,
//            'saved' => $this->savedItems,
//            'total' => $this->getTotalRow()
//        ];
//    }

    public function getTotalRow() {
        return $this->skippedItems + $this->savedItems;
    }


    /**
     * _________________________________________________________________________________________________________________
     * */

    /**
     * Uppdating prices
     * @return array of results
     * */
    public function updatePrice() {

        $newProductData = $this->_data;
        $status = false;

        if (!empty($newProductData)) {
            $status = true;
            /** @var Magento_Db_Adapter_Pdo_Mysql $connection */
            $connection_write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $connection_read = Mage::getSingleton('core/resource')->getConnection('core_read');

            foreach ($newProductData as $attributes) {
                /** @var Mage_Catalog_Model_Resource_Product_Collection $productCollection */
                $productCollection = Mage::getModel('catalog/product')->getCollection();

                $products = $productCollection
                    ->addAttributeToSelect(array('manufacturer_number', 'entity_id'))
                    ->addAttributeToFilter('manufacturer_number', array('eq' => $attributes['code']))->load();

                if (count($products) == 0) {
                    Mage::log("Product with {$attributes['code']} does`t exist", null, 'updating_price.log');
                    $this->skippedItems++;
                    continue;
                }

                $product = $products->getFirstItem();
                $productId = $product->getData('entity_id');

                /** @var Stableflow_Company_Model_Product $companyProduct */
                $entity_type_id = $connection_read
                    ->fetchOne("SELECT `entity_type_id` FROM `company_product_entity` WHERE `entity_id` = {$productId} LIMIT 1");

                $result = $connection_write
                    ->query(
                        'UPDATE `company_product_entity_decimal` SET `value` = ? WHERE `entity_id` = ? AND `entity_type_id` = ?',
                        array($attributes['price'], $productId, $entity_type_id)
                    );

                $this->savedItems += $result->rowCount();
            }
        }

        return [
            'status' => $status,
            'skipped' => $this->skippedItems,
            'saved' => $this->savedItems,
            'total' => $this->getTotalRow()
        ];
    }


}