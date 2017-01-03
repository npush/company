<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/3/17
 * Time: 1:19 PM
 */

$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn(
        $installer->getTable('catalog/eav_attribute'),
        'position_on_frontend',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0'
    ), 'Position on Frontend');
$installer->endSetup();