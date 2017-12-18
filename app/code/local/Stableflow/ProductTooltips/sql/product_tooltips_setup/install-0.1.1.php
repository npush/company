<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/2/17
 * Time: 1:08 PM
 */

$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer->startSetup();


/**
 * Create table 'product_tooltips/tooltip'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('product_tooltips/tooltip'))
    ->addColumn('tooltip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Tooltip ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Value')
    ->setComment('Catalog Product Tooltip Attribute');
$installer->getConnection()->createTable($table);

/**
 * Create table 'product_tooltips/tooltip_value'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('product_tooltips/tooltip_value'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Value Id')
    ->addColumn('tooltip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Tooltip Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Store Id')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
    ), 'Description')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
    ), 'Title')
    ->addIndex($installer->getIdxName('product_tooltips/tooltip_value', array('tooltip_id')),
        array('tooltip_id'))
    ->addIndex($installer->getIdxName('product_tooltips/tooltip_value', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName('product_tooltips/tooltip_value', 'tooltip_id', 'product_tooltips/tooltip', 'tooltip_id'),
        'tooltip_id',
        $installer->getTable('product_tooltips/tooltip'),
        'tooltip_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('product_tooltips/tooltip_value', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Tooltip Attribute Value');
$installer->getConnection()->createTable($table);

//$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');;
//$installer->startSetup();

// adding the manufacturer_number attribute as static attribute
//$installer->addAttribute( 'catalog_product', 'user_manual', array(
//    'label'             => 'User Manual',
//    'type'              => 'varchar',
//    'input'             => 'multiselect',
//    'backend'           => '', //'user_manual/manual_attribute_backend_manual',
//    'source'            => 'product_tooltips/tooltip_attribute_source_manual',
//    'frontend'          => '', //'user_manual/manual_attribute_frontend_manual',
//    'required'          => false,
//    'unique'            => false,
//    'filterable'        => false,
//    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
//    'searchable'        => false,
//    'visible_on_front'  => true,
//    'visible'           => true,
//    'user_defined'      => true
//) );
//
//$installer->endSetup();