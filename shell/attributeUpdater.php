<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/29/16
 * Time: 6:11 PM
 */

require_once 'abstract.php';

class Mage_Shell_AttributeUpdater extends Mage_Shell_Abstract{

    /**
     * Run script
     *
     */
    public function run(){
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            printf("reading data from %S \n", $path);
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    $this->_attributeUpdate($data);
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    private function _attributeUpdate($data){
        $product = Mage::getModel('catalog/product');
        /** @var $product Mage_Catalog_Model_Product */

        //$productIds = $product->getCollection()->getAllIds();

        try {
            $time_start = microtime();
            $productId = $product->getIdBySku($data[0]);
            if($product->load($productId)) {
                if ($data[3] != '\N') {
                    $product->setData('ask_4_price', false);
                    $product->getResource()->saveAttribute($product, 'ask_4_price');
                    $product->setData('price', $data[3]);
                    $product->getResource()->saveAttribute($product, 'price');
                } else {
                    $product->setData('ask_4_price', true);
                    $product->getResource()->saveAttribute($product, 'ask_4_price');
                    $product->setData('price', '0.00');
                    $product->getResource()->saveAttribute($product, 'price');
                }
                $product->setData('manufacturer_number', $data[1]);
                $product->getResource()->saveAttribute($product, 'manufacturer_number');
                printf("Saved modified product: Sku: %s ; Id: %s\n", $product->getSku(), $product->getId());
            }
            $time_end = microtime();
            printf("%f sec from query. \n\n ", $time_end - $time_start);
        }catch (Mage_Core_Exception $e){
            Mage::logException($e->getMessage());
        }
        $product = null;
    }

    private function _attributeUpdate1($attrCode){
        $csv                = new Varien_File_Csv();
        $data               = $csv->getData('prices.csv'); //path to csv
        array_shift($data);

        $message = '';
        $count   = 1;
        foreach($data as $_data){
            if($this->_checkIfSkuExists($_data[0])){
                try{
                    $this->_updatePrices($_data);
                    $message .= $count . '> Success:: While Updating Price (' . $_data[1] . ') of Sku (' . $_data[0] . '). <br />';

                }catch(Exception $e){
                    $message .=  $count .'> Error:: While Upating  Price (' . $_data[1] . ') of Sku (' . $_data[0] . ') => '.$e->getMessage().'<br />';
                }
            }else{
                $message .=  $count .'> Error:: Product with Sku (' . $_data[0] . ') does\'t exist.<br />';
            }
            $count++;
        }
        echo $message;
    }

    private function _getConnection($type = 'core_read'){
        return Mage::getSingleton('core/resource')->getConnection($type);
    }

    private function _getTableName($tableName){
        return Mage::getSingleton('core/resource')->getTableName($tableName);
    }

    private function _getAttributeId($attribute_code = 'price'){
        $connection = $this->_getConnection('core_read');
        $sql = "SELECT attribute_id
                FROM " . $this->_getTableName('eav_attribute') . "
            WHERE
                entity_type_id = ?
                AND attribute_code = ?";
        $entity_type_id = $this->_getEntityTypeId();
        return $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
    }

    private function _getEntityTypeId($entity_type_code = 'catalog_product'){
        $connection = $this->_getConnection('core_read');
        $sql        = "SELECT entity_type_id FROM " . $this->_getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
        return $connection->fetchOne($sql, array($entity_type_code));
    }

    private function _getIdFromSku($sku){
        $connection = $this->_getConnection('core_read');
        $sql        = "SELECT entity_id FROM " . $this->_getTableName('catalog_product_entity') . " WHERE sku = ?";
        return $connection->fetchOne($sql, array($sku));

    }

    private function _checkIfSkuExists($sku){
        $connection = $this->_getConnection('core_read');
        $sql        = "SELECT COUNT(*) AS count_no FROM " . $this->_getTableName('catalog_product_entity') . " WHERE sku = ?";
        $count      = $connection->fetchOne($sql, array($sku));
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }

    private function _updatePrices($data){
        $connection     = $this->_getConnection('core_write');
        $sku            = $data[0];
        $newPrice       = $data[1];
        $productId      = $this->_getIdFromSku($sku);
        $attributeId    = $this->_getAttributeId();

        $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_decimal') . " cped
                SET  cped.value = ?
            WHERE  cped.attribute_id = ?
            AND cped.entity_id = ?";
        $connection->query($sql, array($newPrice, $attributeId, $productId));
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f addAttrToSet.php -- [options]

    --attr <Attribute code>

  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_AttributeUpdater();
$shell->run();