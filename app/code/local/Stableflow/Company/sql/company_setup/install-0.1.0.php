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
    $this->getTable('company/product_entity')
);

/**
 * Add Entity type
 */
$installer->addEntityType('company_product',Array(
    'entity_model'          =>'company/product',
    'attribute_model'       =>'company/attribute',
    'table'                 =>'company/product_entity',
    'increment_model'       =>'eav/entity_increment_numeric',
    //'increment_per_store'   =>'0'
));



/**
 * Create all entity tables
 */
/*$installer->createEntityTables(
    $this->getTable('company/price_entity')
);*/

/**
 * Add Entity type
 */
/*$installer->addEntityType('company_price',Array(
    'entity_model'          =>'company/price',
    'attribute_model'       =>'company/attribute',
    'table'                 =>'company/price_entity',
    'increment_model'       =>'eav/entity_increment_numeric',
    //'increment_per_store'   =>'0'
));*/

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

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_type'))
    ->addColumn('type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Company Type Id')
    ->addColumn('type_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Company Type Code')
    ->setComment('Company Type');
$installer->getConnection()->createTable($table);



$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 1,
    'type_code'   => 'Seller',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 2,
    'type_code'   => 'Producer',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 3,
    'type_code'   => 'Corporation',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 4,
    'type_code'   => 'Shop',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 5,
    'type_code'   => 'Entrepreneur',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_type'), array(
    'type_id'     => 6,
    'type_code'   => 'Mixed',
));


/**
 * Create table 'company_activity'
 */

$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_activity'))
    ->addColumn('activity_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Company Activity Id')
    ->addColumn('activity_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
    ), 'Company Activity Code')
    ->setComment('Company Activity');
$installer->getConnection()->createTable($table);

$installer->getConnection()->insertForce($installer->getTable('company/company_activity'), array(
    'activity_id'     => 1,
    'activity_code'   => 'Lights',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_activity'), array(
    'activity_id'     => 2,
    'activity_code'   => 'Electric',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_activity'), array(
    'activity_id'     => 3,
    'activity_code'   => 'Security',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_activity'), array(
    'activity_id'     => 4,
    'activity_code'   => 'Construction',
));
$installer->getConnection()->insertForce($installer->getTable('company/company_activity'), array(
    'activity_id'     => 5,
    'activity_code'   => 'Other',
));


/**
 * Create table 'company_to_products'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_to_products'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Relation Id')
    ->addColumn('company_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        //'primary'   => true,
    ), 'Company Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        //'primary'   => true,
    ), 'Product Id')
    ->addColumn('company_product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        //'primary'   => true,
    ), 'Company Product Id')
    ->addForeignKey($this->getFkName(
        'company/company_to_products',
        'company_id',
        'company/company_entity',
        'entity_id'
        ), 'company_id', $this->getTable('company/company_entity'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName(
        'company/company_to_products',
        'product_id',
        'catalog/product',
        'entity_id'
    ), 'product_id', $this->getTable('catalog/product'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName(
        'company/company_to_products',
        'company_product_id',
        'company/product_entity',
        'entity_id'
    ), 'company_product_id', $this->getTable('company/product_entity'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addIndex(
        $this->getIdxName(
            'company/company_to_products',
            array('company_id', 'product_id','company_product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('company_id', 'product_id','company_product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    );

$installer->getConnection()->createTable($table);


$installer->installEntities();

$installer->endSetup();