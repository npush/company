<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/27/16
 * Time: 3:21 PM
 */

$installer = $this;

$setup = Mage::getModel( 'eav/entity_setup', 'core_setup' );

$installer->startSetup();

// adding the manufacturer_number attribute as static attribute
$setup->addAttribute( 'catalog_product', 'manufacturer_number', array(
    'group'             => 'General',
    'label'             => 'Manufacturer Number',
    'note'              => 'Manufacturer Number',
    'type'              => 'static',
    'input'             => 'text',
    'backend'           => 'eav/entity_attribute_backend_default',
    'source'            => '',
    'frontend'          => '',
    'required'          => false,
    'unique'            => true,
    'filterable'        => true,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
) );

$installer->getConnection()->addColumn(
    $installer->getTable( 'catalog/product' ),
    'manufacturer_number',
    array(
        'type'          => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'        => 64,
        'comment'       => 'Manufacturer Number'
    )
);

$installer->endSetup();
$installer->getConnection()->resetDdlCache();