<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/13/16
 * Time: 5:59 PM
 */

$this->startSetup();
$table = $this->getConnection();

$table = $this->getConnection()
    ->newTable($this->getTable('mageplaza_betterblog/post_product_category'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'category_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ),
        'Category ID'
    )
    ->addForeignKey(
        $this->getFkName(
            'mageplaza_betterblog/post_product_category',
            'post_id',
            'mageplaza_betterblog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('mageplaza_betterblog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'mageplaza_betterblog/post_product_category',
            'category_id',
            'catalog/category',
            'entity_id'
        ),
        'category_id',
        $this->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'mageplaza_betterblog/post_product_category',
            array('post_id', 'category_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('post_id', 'category_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Post to Category Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
