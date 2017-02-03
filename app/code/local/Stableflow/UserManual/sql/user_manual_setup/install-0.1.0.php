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
 * Create table 'user_manual/manual'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_manual/manual'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Value ID')
//    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
//        'unsigned'  => true,
//        'nullable'  => false,
//        'default'   => '0',
//    ), 'Attribute ID')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Entity ID')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Value')
//    ->addIndex($installer->getIdxName('user_manual/manual', array('attribute_id')),
//        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_manual/manual', array('entity_id')),
        array('entity_id'))
//    ->addForeignKey(
//        $installer->getFkName(
//            'user_manual/manual',
//            'attribute_id',
//            'eav/attribute',
//            'attribute_id'
//        ),
//        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
//        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'user_manual/manual',
            'entity_id',
            'catalog/product',
            'entity_id'
        ),
        'entity_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Manual Attribute Backend Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_manual/manual_value'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_manual/manual_value'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Value ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        'default'   => '0',
    ), 'Store ID')
    ->addColumn('label', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Label')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Position')
    ->addColumn('disabled', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Disabled')
    ->addIndex($installer->getIdxName('user_manual/manual_value', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'user_manual/manual_value',
            'value_id',
            'user_manual/manual',
            'value_id'
        ),
        'value_id', $installer->getTable('user_manual/manual'), 'value_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'user_manual/manual_value',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Manual Attribute Value Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();