<?php
/**
 * Created by nick
 * Project direct-fabrics.co.uk
 * Date: 3/14/17
 * Time: 12:42 PM
 */

require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
$installer = new Mage_Sales_Model_Mysql4_Setup;
/*$attribute  = array(
    'type'          => 'text',
    'backend_type'  => 'text',
    'frontend_input' => 'text',
    'is_user_defined' => true,
    'label'         => 'Your attribute label',
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false,
    'searchable'    => false,
    'filterable'    => false,
    'comparable'    => false,
    'default'       => ''
);
$installer->addAttribute('quote', 'source_r4q_id', $attribute);
$installer->addAttribute('order', 'source_r4q_id', $attribute);*/

// adding the manufacturer attribute
$installer->addAttribute( 'catalog_product', 'manufacturer_new', array(
    'group'             => 'General',
    'label'             => 'Manufacturer',
    'note'              => 'Manufacturer',
    'type'              => 'int',
    'frontend_input' => 'select',
    'backend'           => 'company/company_attribute_backend_company',
    'source'            => 'company/company_attribute_source_company',
    'frontend'          => '',
    'required'          => false,
    'unique'            => false,
    'filterable'        => true,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'searchable'        => true,
    'visible_on_front'  => true,
    'visible'           => true,
    'is_user_defined'   => true
) );

$installer->endSetup();