<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:15 PM
 */

$installer = $this;
$installer->startSetup();

/**
 * Create all entity tables
 */
$installer->createEntityTables(
    $this->getTable('company/company_entity')
);

/**
 * Add Entity type
 */
$installer->addEntityType('company_company',Array(
    'entity_model'          =>'company/company',
    'attribute_model'       =>'company/attribute',
    'table'                 =>'company/company_entity',
    'increment_model'       =>'eav/entity_increment_numeric',
    //'increment_per_store'   =>'0'
));

/**
 * Create all entity tables
 */
$installer->createEntityTables(
    $this->getTable('company/address_entity')
);

/**
 * Add Entity type
 */
$installer->addEntityType('company_address',Array(
    'entity_model'          =>'company/address',
    'attribute_model'       =>'company/attribute',
    'table'                 =>'company/address_entity',
    'increment_model'       =>'eav/entity_increment_numeric',
    //'increment_per_store'   =>'0'
));

/**
 * Create all entity tables
 */
$installer->createEntityTables(
    $this->getTable('company/price_entity')
);

/**
 * Add Entity type
 */
$installer->addEntityType('company_price',Array(
    'entity_model'          =>'company/price',
    'attribute_model'       =>'company/attribute',
    'table'                 =>'company/price_entity',
    'increment_model'       =>'eav/entity_increment_numeric',
    //'increment_per_store'   =>'0'
));

/**
 * Create table 'company/eav_attribute'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/eav_attribute'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Attribute Id')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Visible')
    ->addColumn('input_filter', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Input Filter')
    ->addColumn('multiline_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Multiline Count')
    ->addColumn('validate_rules', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Validate Rules')
    ->addColumn('is_system', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is System')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('data_model', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Data Model')
    ->addForeignKey($installer->getFkName('company/eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('company Eav Attribute');
$installer->getConnection()->createTable($table);

/**
 * Create table 'company_type'
 */
/*
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_type'))
    ->addColumn('company_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Company Type Id')
    ->addColumn('company_type_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Company Type Code')
    ->setComment('Company Type');
$installer->getConnection()->createTable($table);
*/

/*
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 1,
    'company_type_code'   => 'Seller',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 2,
    'company_type_code'   => 'Producer',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 3,
    'company_type_code'   => 'Corporation',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 4,
    'company_type_code'   => 'Shop',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 5,
    'company_type_code'   => 'Entrepreneur',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'company_type_id'     => 6,
    'company_type_code'   => 'Mixed',
));
*/

/**
 * Create table 'company_activity'
 */
/*
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_activity'))
    ->addColumn('company_activity_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Company Type Id')
    ->addColumn('company_activity_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Company Type Code')
    ->setComment('Company Type');
$installer->getConnection()->createTable($table);
*/

/**
 * Create table 'company_products'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_products'))
    ->addColumn('company_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Company Type Id')
    ->addColumn('company_type_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Company Type Code')
    ->setComment('Company Type');
$installer->getConnection()->createTable($table);


$installer->installEntities();

$installer->endSetup();