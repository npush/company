<?php
/**
 * upgrade-0.1.5-0.1.7.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 11/8/17
 *
 */

/** @var  $installer Stableflow_Company_Model_Resource_Setup*/
$installer = $this;
$installer->startSetup();

/**
 * Create table 'catalog/product_attribute_manufacturer_number'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/product_attribute_manufacturer_number'))
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
    ->addIndex($installer->getIdxName('company/product_attribute_manufacturer_number', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('company/product_attribute_manufacturer_number', array('entity_id')),
        array('entity_id'))
    ->addForeignKey(
        $installer->getFkName(
            'company/product_attribute_manufacturer_number',
            'attribute_id',
            'eav/attribute',
            'attribute_id'
        ),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'company/product_attribute_manufacturer_number',
            'entity_id',
            'catalog/product',
            'entity_id'
        ),
        'entity_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Manufacturer Number Attribute Backend Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();