<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/7/17
 * Time: 3:56 PM
 */

require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

/*$categories = Mage::getModel('catalog/category')->getCollection();
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
}*/

$repaceValues = array(
    '&degC'         => '&deg;C',
    '&degС'         => '&deg;C',        // Russian "C"
    '&amp;degC'     => '&deg;C',
    '&amp;degС'     => '&deg;C',        // Russian "C"
    '&amp;deg;C'    => '&deg;C',
    '&amp;deg;С'    => '&deg;C',       // Russian "C"
    '°C'            => '&deg;C',
    '°С'            => '&deg;C',        // Russian "C"
    '&sup2.'        => '&sup2;.',
    '&sup2 '        => '&sup2; ',
    '&sup2)'        => '&sup2;)',
);

/** @var  $resource Mage_Core_Model_Resource*/
$resource = Mage::getSingleton('core/resource');
/** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
$readConnection = $resource->getConnection('core_read');
/** @var  $writeConnection Varien_Db_Adapter_Pdo_Mysql */
$writeConnection = $resource->getConnection('core_write');

$productTable = 'catalog_product_entity_text';

$query = 'SELECT `value_id`,  `value` FROM ' . $productTable . ' WHERE value LIKE "%deg%" OR value LIKE "%&sup2%" OR value LIKE "°С"' ;
$data = $readConnection->fetchPairs($query);
foreach($data as $value_id => $value){
    $result = str_replace(array_keys($repaceValues), $repaceValues, $value);
    try{
        if($result != $value){
            $witeQuery = 'UPDATE ' . $productTable . ' SET `value` = \'' . $result . '\' WHERE value_id = '. $value_id;
            $writeConnection->query($witeQuery);
            $result = null;
        }
    }catch (Exception $e){
        Mage::log($e, null, 'fix.log');
    }

}
