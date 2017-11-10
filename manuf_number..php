<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/10/17
 * Time: 2:28 PM
 */

require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

/** @var  $resource Mage_Core_Model_Resource*/
$resource = Mage::getSingleton('core/resource');
/** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
$readConnection = $resource->getConnection('core_read');
/** @var  $writeConnection Varien_Db_Adapter_Pdo_Mysql */
$writeConnection = $resource->getConnection('core_write');

$manufNumbTable = 'catalog_product_entity_manufacturer_number';

$productTable = 'catalog_product_entity_varchar';
$attrNameId = 146;
$query = 'SELECT `attribute_id`, `entity_id`, `value` FROM ' . $productTable . ' WHERE `attribute_id` = ' . $attrNameId;

$data = $readConnection->fetchAll($query);
foreach($data as $entity){
    $codes = explode('|', $entity['value']);
    foreach($codes as $code) {
        $code = trim($code);
        $queryWrite = 'INSERT INTO ' . $manufNumbTable . ' (`attribute_id`,`entity_id`,`value`) VALUES (?, ?, ? ) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';
        $writeConnection->query($queryWrite, array($entity['attribute_id'], $entity['entity_id'], $code));
    }

}