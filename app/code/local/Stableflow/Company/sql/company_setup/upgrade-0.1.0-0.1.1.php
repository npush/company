<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/13/17
 * Time: 3:18 PM
 */

$installer = $this;
$installer->startSetup();
$installer->addAttribute('company_company', 'url_key', array(
    'type'              => 'varchar',
    'backend'           => 'company/company_attribute_backend_urlkey',
    'frontend'          => '',
    'label'             => 'URL key',
    'input'             => 'text',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'          => '',
    'user_defined'      => false,
    'default'           => '',
    'unique'            => false,
    'position'          => 25,
    'note'              => '',
    'visible'           => '1',
    'wysiwyg_enabled'   => '0',
));

$installer->endSetup();