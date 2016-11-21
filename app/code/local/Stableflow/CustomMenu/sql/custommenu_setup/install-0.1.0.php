<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/21/16
 * Time: 11:22 AM
 */

$setup = $this;

$setup->startSetup();

$setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'category_icon',
    array(
        'backend'           => '',
        'default'           => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'group'             => 'General Information',
        'input'             => 'text',
        'label'             => 'Show Category Icon',
        'position'          => 100,
        'required'          => false,
        'source'            => '',
        'type'              => 'varchar',
        'user_defined'      => true,
        'visible'           => true,
        'visible_on_front'  => true,
    )
);

$setup->endSetup();