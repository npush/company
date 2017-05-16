<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 5/16/17
 * Time: 4:46 PM
 */
?>
<?php
$installer = $this;
$installer->startSetup();
$installer->addAttribute('customer', 'company_owner', array(
    //'group'             => 'General',
    'type'              => 'int',
    'frontend'          => '',
    'label'             => 'Can Manage Company',
    'input'             => 'boolean',
    'backend'           => 'company/owner_attribute_backend_data_boolean',
    'source'            => 'company/owner_attribute_source_owner',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'          => '',
    'user_defined'      => true,
    'default'           => false,
    'unique'            => false,
    'position'          => 7,
    'note'              => 'Select "Yes" if Customer can manage Company',
    'visible'           => '1',

));

$companyOwnerAttribute = Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'company_owner');
$companyOwnerAttribute->setData('used_in_forms', array(
    'adminhtml_customer'
));
$companyOwnerAttribute->save();

$installer->endSetup();
