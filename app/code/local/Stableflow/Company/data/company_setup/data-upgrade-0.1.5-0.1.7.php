<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/14/17
 * Time: 12:24 PM
 */

/** @var  $installer Stableflow_Company_Model_Resource_Setup*/
$installer = $this;
/** @var Mage_Eav_Model_Entity_Attribute $attr */
$attr = Mage::getModel('eav/entity_attribute')
    ->loadByCode(Mage_Catalog_Model_Product::ENTITY, 'manufacturer_number');
$attrId = $attr->getAttributeId();
$query = $installer->getConnection()->select()
    ->from('catalog_product_entity_varchar', array('attribute_id', 'entity_id', 'value'))
    ->where('attribute_id = (?)', $attrId);
$data = $installer->getConnection()->fetchAll($query);
foreach ($data as $entity){
    $codes = explode('|', $entity['value']);
    foreach($codes as $code) {
        $code = trim($code);
        $installer->getConnection()
            ->insert($installer->getTable('company/parser_additional_code'), array(
                'attribute_id' => $entity['attribute_id'],
                'entity_id' => $entity['entity_id'],
                'value' => $code
            ));
    }
}