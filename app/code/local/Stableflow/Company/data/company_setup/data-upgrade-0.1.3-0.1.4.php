<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/21/17
 * Time: 1:49 PM
 */
?>

<?php
$installer = $this;
$installer->run("UPDATE company_product_entity as cpe,
       (SELECT ctp.`company_id`, ctp.`product_id`, ctp.`company_product_id` FROM company_to_products ctp) as source
  SET cpe.company_id = source.company_id, cpe.catalog_product_id = source.product_id
  WHERE cpe.entity_id = source.company_product_id;");

$connection = $installer->getConnection();

$productCollection = Mage::getModel('company/product')->getCollection()
    ->addFieldToSelect(array('entity_id, catalog_product_id, name'));
foreach($productCollection as $product){
    $productCatalogId = $product->getData('catalog_product_id');
    //$name = Mage::getModel('catalog/product')->load($productCatalogId)->getData('name');

    $productTable = 'catalog_product_entity_varchar';
    $attrNameId = 71;
    $query = 'SELECT `value` FROM ' . $productTable . ' WHERE `attribute_id` = ' . $attrNameId . ' AND `entity_id` = ' . $productCatalogId;
    $name = $connection->fetchOne($query);
    $product->setData('name', $name);
    $product->save();
}