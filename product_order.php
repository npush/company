<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/7/17
 * Time: 3:56 PM
 */

require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
Mage::getModel('company/parser_queue')->performQueue();
exit();
$categories = Mage::getModel('catalog/category')->getCollection();
foreach($categories as $category){
    echo "Category Id: " . $category->getId() . "\n";
    $new_order = 1;
    $cat_api = new Mage_Catalog_Model_Category_Api;
    $products = $category->getProductCollection();

    foreach ($products as $product){
        $cat_api->assignProduct($category->getId(), $product->getId(), $new_order);
        echo "product ID: " . $product->getId() . ". New order: " . $new_order . "\r";
        $new_order ++;
    }
echo "\n";
}

