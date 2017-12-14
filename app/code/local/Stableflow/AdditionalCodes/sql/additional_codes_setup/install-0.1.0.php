<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/15/17
 * Time: 7:02 PM
 */

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');;
$installer->startSetup();

// adding the manufacturer_number attribute as static attribute
$installer->addAttribute( 'catalog_product', 'additional_codes', array(
    'label'             => 'Manufacturer Numbers',
    'note'              => '',
    'type'              => 'varchar',
    'input'             => 'text',
    'backend'           => 'additional_codes/product_attribute_backend_manufnumber',//'additional_codes/product_attribute_backend_codes',
    'source'            => '',
    'frontend'          => '',
    'required'          => false,
    'unique'            => false,
    'filterable'        => false,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'searchable'        => true,
    'visible_on_front'  => true,
    'visible'           => true,
    'user_defined'   => true
) );

$installer->endSetup();
$installer = $this;


/**
 * Create table 'catalog/product_attribute_manufacturer_number'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('additional_codes/product_attribute_manufacturer_number'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Value ID')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Attribute ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Entity ID')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Value')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Store ID')
    ->addIndex($installer->getIdxName('additional_codes/product_attribute_manufacturer_number', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('additional_codes/product_attribute_manufacturer_number', array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(
            'additional_codes/product_attribute_manufacturer_number',
            'attribute_id',
            'eav/attribute',
            'attribute_id'
        ),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'additional_codes/product_attribute_manufacturer_number',
            'entity_id',
            'catalog/product',
            'entity_id'
        ),
        'entity_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Manufacturer Number Attribute Backend Table');
$installer->getConnection()->createTable($table);


/**
 * Create table 'additional_codes/product_attribute_additional_codes'
 */

if($installer->getConnection()->isTableExists($installer->getTable('additional_codes/product_attribute_additional_codes')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('additional_codes/product_attribute_additional_codes'))
        ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Value ID')
        ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Attribute ID')
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Entity ID')
        ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Value')
        ->addIndex($installer->getIdxName('additional_codes/product_attribute_additional_codes', array('attribute_id')),
            array('attribute_id'))
        ->addIndex($installer->getIdxName('additional_codes/product_attribute_additional_codes', array('entity_id')),
            array('entity_id'))
        ->addForeignKey(
            $installer->getFkName(
                'additional_codes/product_attribute_additional_codes',
                'attribute_id',
                'eav/attribute',
                'attribute_id'
            ),
            'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->addForeignKey(
            $installer->getFkName(
                'additional_codes/product_attribute_additional_codes',
                'entity_id',
                'catalog/product',
                'entity_id'
            ),
            'entity_id', $installer->getTable('catalog/product'), 'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Additional Codes Attribute Backend Table');
    $installer->getConnection()->createTable($table);
}
$installer->endSetup();