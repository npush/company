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
    'increment_per_store'   =>'0'
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
    'increment_per_store'   =>'0'
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
    'increment_per_store'   =>'0'
));

/**
 * Create table 'customer/eav_attribute'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('customer/eav_attribute'))
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
    ->addForeignKey($installer->getFkName('customer/eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Customer Eav Attribute');
$installer->getConnection()->createTable($table);


/**
 * Create table 'customer/eav_attribute_website'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('customer/eav_attribute_website'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Attribute Id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Website Id')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Is Visible')
    ->addColumn('is_required', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Is Required')
    ->addColumn('default_value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Default Value')
    ->addColumn('multiline_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
    ), 'Multiline Count')
    ->addIndex($installer->getIdxName('customer/eav_attribute_website', array('website_id')),
        array('website_id'))
    ->addForeignKey(
        $installer->getFkName('customer/eav_attribute_website', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('customer/eav_attribute_website', 'website_id', 'core/website', 'website_id'),
        'website_id', $installer->getTable('core/website'), 'website_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Customer Eav Attribute Website');
$installer->getConnection()->createTable($table);


/**
 * Create table 'customer_group'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('customer/customer_group'))
    ->addColumn('customer_group_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Customer Group Id')
    ->addColumn('customer_group_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Customer Group Code')
    ->addColumn('tax_class_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Tax Class Id')
    ->setComment('Customer Group');
$installer->getConnection()->createTable($table);


// insert default customer groups
$installer->getConnection()->insertForce($installer->getTable('customer/customer_group'), array(
    'customer_group_id'     => 0,
    'customer_group_code'   => 'NOT LOGGED IN',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('customer/customer_group'), array(
    'customer_group_id'     => 1,
    'customer_group_code'   => 'General',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('customer/customer_group'), array(
    'customer_group_id'     => 2,
    'customer_group_code'   => 'Wholesale',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('customer/customer_group'), array(
    'customer_group_id'     => 3,
    'customer_group_code'   => 'Retailer',
    'tax_class_id'          => 3
));
$installer->installEntities();

$installer->endSetup();