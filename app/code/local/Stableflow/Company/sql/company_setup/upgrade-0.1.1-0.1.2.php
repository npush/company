<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/10/17
 * Time: 5:36 PM
 */
?>

<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('company/eav_attribute'), 'is_wysiwyg_enabled', array(
    'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'unsigned' => true,
    'nullable' => false,
    'default' => '0',
    'comment' => 'Is WYSIWYG Enabled'
));

$installer->getConnection()
    ->addColumn($installer->getTable('company/address_entity'), 'parent_id', array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned' => true,
    'nullable' => false,
    'comment' => 'Parent Id')
    );
$installer->getConnection()
    ->addConstraint($installer->getFkName('company/address_entity','parent_id','customer/entity', 'entity_id'),
        $installer->getTable('company/address_entity'),
        'parent_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );


$table = $installer->getConnection()
    ->newTable($installer->getTable('company/company_owner'))
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
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Customer Id')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
    ), 'Updated At')
    ->addForeignKey($installer->getFkName('company/company_owner', 'company_id', 'company/company_entity', 'entity_id'),
        'company_id',
        $installer->getTable('company/company_entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Company Owner table')
    ->addForeignKey($installer->getFkName('company/company_owner', 'costumer_id', 'customer/entity', 'entity_id'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);
$installer->getConnection()->createTable($table);


$installer->endSetup();