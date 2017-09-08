<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/25/17
 * Time: 2:49 PM
 */

/** @var  $installer Stableflow_Company_Model_Resource_Setup*/
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/price_type'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('company_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Company Id')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Description')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')
    ->addForeignKey($installer->getFkName('company/price_type', 'company_id', 'company/company_entity', 'entity_id'),
        'company_id',
        $installer->getTable('company/company_entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/parser_config'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('price_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Price Type Id')
    ->addColumn('config', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'config')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Description')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE
    ), 'Updated At')
    ->addForeignKey($installer->getFkName('company/parser_config', 'price_type_id', 'company/price_type', 'entity_id'),
        'price_type_id',
        $installer->getTable('company/price_type'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/parser_tasks'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('config_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Config Id')
//    ->addColumn('company_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
//        'unsigned'  => true,
//        'nullable'  => false,
//    ), 'Company Id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'File Name')
    ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'status Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')
    ->addColumn('process_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
    ), 'Updated At')
    ->addColumn('time_spent', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default' => 0
    ), 'Time Spent')
//    ->addForeignKey($installer->getFkName('company/price_type', 'company_id', 'company/company_entity', 'entity_id'),
//        'company_id',
//        $installer->getTable('company/company_entity'),
//        'entity_id',
//        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->addForeignKey($installer->getFkName('company/parser_tasks', 'config_id', 'company/parser_config', 'entity_id'),
        'config_id',
        $installer->getTable('company/parser_config'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
;
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/parser_log'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('task_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Task Id')
    ->addColumn('line', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Line Number')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
        'nullable'  => false,
    ), 'Line Content')
    ->addColumn('data', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
        'nullable'  => false,
    ), 'Stored Data')
    ->addColumn('error_text', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(
        'nullable'  => false,
    ), 'error_text')
    ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Status Id')
    ->addColumn('company_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Company Product Id')
    ->addForeignKey($installer->getFkName('company/parser_log', 'task_id', 'company/parser_tasks', 'entity_id'),
        'task_id',
        $installer->getTable('company/parser_config'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/parser_queue'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('task_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unique'    => true,
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Task Id')
    ->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Status Id')
    ->addForeignKey($installer->getFkName('company/parser_queue', 'task_id', 'company/parser_tasks', 'entity_id'),
        'task_id',
        $installer->getTable('company/parser_tasks'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION);
$installer->getConnection()->createTable($table);

$installer->endSetup();