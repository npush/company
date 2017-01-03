<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/21/16
 * Time: 11:22 AM
 */

$installer = $this;
$setup = Mage::getModel( 'eav/entity_setup', 'core_setup' );
$installer->startSetup();

// add category icon attribute
$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'category_icon',
    array(
        'backend'           => '',
        'default'           => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group'             => 'General Information',
        'input'             => 'text',
        'label'             => 'Show Category Icon',
        'position'          => 100,
        'required'          => false,
        'source'            => '',
        'type'              => 'varchar',
        'user_defined'      => true,
        'visible'           => true,
        'visible_on_front'  => true,
    )
);

// Add attribute sorting on frontend

$installer->getConnection()
    ->addColumn(
        $installer->getTable('catalog/eav_attribute'),
        'position_on_frontend',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0'
    ), 'Position on Frontend');


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