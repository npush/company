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
        $this->fixDespription('tooltops');

    }

    public function fixDespription(){
        $count = 0;
        $productIds = Mage::getModel('catalog/product')->getCollection()->getAllIds();
        $product = Mage::getModel('catalog/product');
        /** @var $product Mage_Catalog_Model_Product */

        foreach($productIds as $productId){
            $product->load($productId);
            $description = $product->getData('description');
            //$description = preg_replace('/(<p><\/p>[\n\r]*)*/u','', $description);
            $description = preg_replace('/<p><\/p>/u','', $description);
            $description = preg_replace('/[\n\r]{2,}/u','', $description);
            $product->setData('short_description', $description);
            $product->setData('description', $description);
            $product->save();
            printf("Saved modified product: %s \n", $product->getSku());
            $count++;
        }
        printf("Modified % products \n", $count);
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