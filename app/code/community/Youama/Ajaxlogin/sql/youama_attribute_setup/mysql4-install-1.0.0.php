<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/8/16
 * Time: 6:01 PM
 */
?>
<?
$installer = $this;

$installer->startSetup ();

$setup = Mage::getModel ( 'customer/entity_setup' , 'core_setup' );

//add budget
$setup->addAttribute('customer', 'telephone', array(
    'type' => 'text',
    'input' => 'text',
    'label' => 'Telephone',
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 0,
    'default' => '',
    'visible_on_front' => 1,
    'source' =>   NULL,
    'comment' => 'Customer phone number:'
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'telephone')
    ->setData('used_in_forms', array('adminhtml_customer'))
    ->save();

$installer->endSetup ();
