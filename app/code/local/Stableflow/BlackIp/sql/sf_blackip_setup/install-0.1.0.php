<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/5/17
 * Time: 4:49 PM
 */
?>
<?php
$installer = $this;
$installer->startSetup();

/**
 * Create table
 */

if($installer->getConnection()->isTableExists($installer->getTable('sf_blackip/blacklist')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('sf_blackip/blacklist'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Entity Id')
        ->addColumn('black_ip', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Black-IP')
        ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Comment')
        ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Black IP Creation Time')
        ->setComment('Black List IP Table');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();