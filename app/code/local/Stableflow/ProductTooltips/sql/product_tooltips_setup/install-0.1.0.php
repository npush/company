<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/8/17
 * Time: 3:20 PM
 */

$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer->startSetup();

$tableName = $this->getTable('product_tooltips/tooltip');
if ($installer->getConnection()->isTableExists($tableName) != true) {
    $table = $this->getConnection()
        ->newTable($tableName)
        ->addColumn('tooltip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Tooltip ID')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false,
        ), 'Title')
        ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false,
        ), 'Description')
        ->addColumn('image_file', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Image file')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'Enabled')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Tooltip Modification Time')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Tooltip Creation Time')
        ->setComment('Tooltips Table');
    $this->getConnection()->createTable($table);
}

$tableName = $this->getTable('product_tooltips/product_tooltip');
if ($installer->getConnection()->isTableExists($tableName) != true) {
    $table = $this->getConnection()
        ->newTable($tableName)
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ), 'ID')
        ->addColumn('tooltip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Tooltip ID')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Product ID')
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'default' => '0',
        ), 'Position')
        ->addIndex(
            $this->getIdxName('product_tooltips/product_tooltip', array('product_id')), array('product_id'))
        // fkName table colName refTaleName refColName
        ->addForeignKey(
        // tableName colName refTableName refColName
            $this->getFkName(
                'product_tooltips/product_tooltip', 'id',
                'product_tooltips/tooltip', 'tooltip_id'
            ),
            'id',
            $this->getTable('product_tooltips/tooltip'),
            'tooltip_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                'product_tooltips/product_tooltip', 'product_id',
                'catalog/product', 'entity_id'
            ),
            'product_id',
            $this->getTable('catalog/product'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addIndex(
            $this->getIdxName(
                'product_tooltips/product_tooltip',
                array('tooltip_id', 'product_id'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('tooltip_id', 'product_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->setComment('Tooltip to Product Relation Table');

    $this->getConnection()->createTable($table);
}

$installer->endSetup();