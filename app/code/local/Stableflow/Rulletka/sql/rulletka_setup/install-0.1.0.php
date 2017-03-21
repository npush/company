<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/21/16
 * Time: 11:22 AM
 */

//$installer = $this;
$installer = Mage::getModel( 'eav/entity_setup', 'core_setup' );
$installer->startSetup();


$attributes = array(
    'sale' => array(
        'group'             => 'General',
        'label'             => 'Sale',
        'note'              => 'Sale',
        'type'              => 'int',
        'input'             => 'boolean',
        'backend'           => '',
        'source'            => 'eav/entity_attribute_source_boolean',
        'frontend'          => '',
        'required'          => false,
        'unique'            => false,
        'default'           => '0',
        'user_defined'      => true,
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
        // -- catalog
        'used_in_product_listing' => true,
        'visible_on_front'  => true,

    ),
    'promo' => array(
        'group'             => 'General',
        'label'             => 'Promo',
        'note'              => 'Promo',
        'type'              => 'int',
        'input'             => 'boolean',
        'backend'           => '',
        'source'            => 'eav/entity_attribute_source_boolean',
        'frontend'          => '',
        'required'          => false,
        'unique'            => false,
        'user_defined'      => true,
        'default'           => '0',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
        // -- catalog
        'used_in_product_listing' => true,
        'visible_on_front'  => true,
    ),
    'out_of_production' => array(
        'group'             => 'General',
        'label'             => 'Out Of Production',
        'note'              => 'Out Of Production',
        'type'              => 'int',
        'input'             => 'boolean',
        'backend'           => '',
        'source'            => 'eav/entity_attribute_source_boolean',
        'frontend'          => '',
        'required'          => false,
        'unique'            => false,
        'filterable'        => false,
        'user_defined'   => true,
        'default'           => '0',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
        // -- catalog
        'used_in_product_listing' => true,
        'visible_on_front'  => true,

    ),
    'file_upload' => array(
        'group'             => 'General',
        'label'             => 'User manual',
        'note'              => 'User manual',
        'type'              => 'varchar',
        'input'             => 'jvs_file',
        'backend'           => 'jvs_fileattribute/attribute_backend_file',
        'source'            => '',
        'frontend'          => '',
        'required'          => false,
        'unique'            => false,
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
        // -- catalog
        'user_defined'   => true,
        'visible_on_front'  => true,

    )
);

foreach($attributes as $attrName => $attrVal){
    $installer->addAttribute('catalog_product', $attrName, $attrVal);
}

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
        'visible_on_front'  => true,
    )
);

$installer->endSetup();
$installer->getConnection()->resetDdlCache();