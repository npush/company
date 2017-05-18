<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/17/17
 * Time: 11:51 AM
 */
?>
<?php
$installer = $this;
$installer->startSetup();

/**
 * Create ref table
 */

if($installer->getConnection()->isTableExists($installer->getTable('redirect301/ref_table')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('redirect301/ref_table'))
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Old Product Id')
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
            'unsigned'  => true,
        ), 'Product Id')
        ->addForeignKey($installer->getFkName('redirect301/ref_table', 'entity_id', 'catalog/product', 'entity_id'),
            'entity_id',
            $installer->getTable('catalog/product'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Reference from ol to new ID`s');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();