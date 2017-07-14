<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/13/17
 * Time: 5:06 PM
 */
?>
<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('company/product_entity'), 'catalog_product_id', array(
        'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned' => true,
        'nullable' => false,
        'after'     => 'entity_type_id',
        'comment' => 'Catalog Product ID'
));
$installer->getConnection()
    ->addConstraint($installer->getFkName('company/product_entity', 'catalog_product_id', 'catalog/product', 'entity_id'),
        $installer->getTable('company/product_entity'),
        'catalog_product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );

$installer->getConnection()->addColumn($installer->getTable('company/product_entity'), 'company_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned' => true,
    'nullable' => false,
    'after'     => 'catalog_product_id',
    'comment' => 'Company ID'
));
$installer->getConnection()
    ->addConstraint($installer->getFkName('company/product_entity', 'company_id', 'company/company_entity', 'entity_id'),
        $installer->getTable('company/product_entity'),
        'company_id',
        $this->getTable('company/company_entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );

$installer->endSetup();
