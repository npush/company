<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/2/16
 * Time: 4:43 PM
 */

/** @var $install Cybage_Marketplace_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/** @var $adapter Varien_Db_Adapter_Pdo_Mysql */
$adapter = $installer->getConnection();

/**
 *
 */
$table = $adapter->newTable($installer->getTable('marketplace/seller_prices'))
    ->addColumn('seller_prices_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Seller Prices Id')
    ->addColumn('seller_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false
    ), 'Seller ID')
    ->addColumn('price_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false
    ), 'Price ID')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true
    ), 'Updated At')
    ->addForeignKey(
        $installer->getFkName('marketplace/seller_prices', 'product_id', $installer->getTable('catalog/product'), 'entity_id'),
        'admin_id',
        $installer->getTable('admin/user'),
        'user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName('marketplace/seller_prices', 'seller_id', $installer->getTable('customer/entity'), 'entity_id'),
        'admin_id',
        $installer->getTable('admin/user'),
        'user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setOption('type', 'InnoDB');

$adapter->createTable($table);

$installer->endSetup();
