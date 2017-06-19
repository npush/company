<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 12:43 PM
 */
?>
<?php
$installer = $this;
$installer->startSetup();

/**
 * Create ref table
 */

if($installer->getConnection()->isTableExists($installer->getTable('sf_log404/log404')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('sf_log404/log404'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Entity ID')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned'  => true,
            'default'   => '0',
        ), 'Store id')
        ->addColumn('requested_url', Varien_Db_Ddl_Table::TYPE_TEXT, '2k', array(
            'default'  => null,
        ), 'Requested URL')
        ->addColumn('referrer_url', Varien_Db_Ddl_Table::TYPE_TEXT, '2k', array(
            'default'  => null,
        ), 'Referrer URL')
        ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
            'default'  => null,
        ), 'Description')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable'  => false,
        ), 'Issue create date')

        ->setComment('Reference from ol to new ID`s');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();