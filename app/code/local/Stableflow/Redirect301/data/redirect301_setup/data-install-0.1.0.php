<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/18/17
 * Time: 11:13 AM
 */
?>
<?php
/**
 * Convert product SKU to old_product_id
 * Fill ref table based on:
 * old_product_id and product entity_id
 */

    /** @var  $resource Mage_Core_Model_Resource*/
    $resource = Mage::getSingleton('core/resource');
    /** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
    $readConnection = $resource->getConnection('core_read');
    /** @var  $writeConnection Varien_Db_Adapter_Pdo_Mysql */
    $writeConnection = $resource->getConnection('core_write');

    $productTable = $resource->getTableName('catalog/product');

    $query = 'SELECT `sku`,  `entity_id` FROM ' . $productTable ;
    $data = $readConnection->fetchPairs($query);

    $refTable = $resource->getTableName('redirect301/ref_table');
    $query = 'INSERT INTO ' . $refTable . ' (product_id, entity_id) VALUES(:old_product_id, :new_product_id)';
    foreach($data as $sku => $entity_id) {
        $old_id = substr($sku, strpos($sku, 't') + 1);
        $bind = array(
            ':old_product_id' => (int)$old_id,
            ':new_product_id' => (int)$entity_id
        );
        $writeConnection->query($query, $bind);
    }
