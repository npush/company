<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/23/16
 * Time: 11:44 AM
 */

require_once 'abstract.php';

class Mage_Shell_FixProductDescription extends Mage_Shell_Abstract{

    /**
     * Run script
     *
     */
    public function run(){
        $this->fixDescriptionR('tooltops');

    }

    public function fixDescription(){
        $count = 0;
        $productIds = Mage::getModel('catalog/product')->getCollection()->getAllIds();
        $product = Mage::getModel('catalog/product');
        /** @var $product Mage_Catalog_Model_Product */

        foreach($productIds as $productId){
            try {
                $product->load($productId);
                $description = $product->getData('description');
                //$description = preg_replace('/(<p><\/p>[\n\r]*)*/u','', $description);
                $description = preg_replace('/<p><\/p>/u', '', $description);
                $description = preg_replace('/[\n\r]{2,}/u', '', $description);
                $product->setData('short_description', $description);
                $product->setData('description', $description);
                $product->save();
                printf("Saved modified product: %s \n", $product->getSku());
                $count++;
            }catch (Exeption $e){
                Mage::logException($e->getMessage());
            }
        }
        printf("Modified %s products \n", $count);
    }

    public function fixDescriptionR(){
        $collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('description');
        foreach($collection as $item) {
            $productId = (int)$item->getId();
            $description = $item->getDescription();
            $description = preg_replace('/<p><\/p>/u', '', $description);
            $description = preg_replace('/[\n\r]{2,}/u', '', $description);

            $description = addslashes($description);

            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            $table = $resource->getTableName('catalog/product');
            $query = "UPDATE `catalog_product_entity_text` SET VALUE = '{$description}' WHERE entity_id = '{$productId}' AND attribute_id = 73";
            $writeConnection->query($query);
            $query = "UPDATE `catalog_product_entity_text` SET VALUE = '{$description}' WHERE entity_id = '{$productId}' AND attribute_id = 72";
            $writeConnection->query($query);
            printf("Product : %s \n", $productId);
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f FixProductDescription.php -- [options]


  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_FixProductDescription();
$shell->run();