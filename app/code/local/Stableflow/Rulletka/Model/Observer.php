<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/3/17
 * Time: 1:20 PM
 */

class Stableflow_Rulletka_Model_Observer extends Mage_Core_Model_Observer {
    /**
     * @param $form Varien_Data_Form
     * @param $attributeObject
     */
    public function addPositionToAttribute_($form, $attributeObject){
        //create new custom fieldset 'website'
        /* @var $fieldset Varien_Data_Form_Element_Fieldset */
        $fieldset = $form->getForm()->getElement('base_fieldset');
        //add new website field
        $fieldset->addField('position_on_frontend', 'text', array(
            'name' => 'position_on_frontend',
            'label' => Mage::helper('catalog')->__('Position on Frontend'),
            'title' => Mage::helper('catalog')->__('Position on Frontend'),
            'note' => Mage::helper('catalog')->__('Position of attribute in product view block'),
            'class' => 'validate-digits',
        ));
    }
    public function addPositionToAttribute($observer){
        $block = $observer->getEvent()->getBlock();
        if (!isset($block)) {
            return $this;
        }
        if ($block->getType() == 'adminhtml/catalog_product_attribute_edit_tab_front') {
            $form = $block->getForm();
            //create new custom fieldset 'website'
            $fieldset = $form->getElement('base_fieldset');
            //add new website field
            $fieldset->addField('position_on_frontend', 'text', array(
                'name' => 'position_on_frontend',
                'label' => Mage::helper('catalog')->__('Position on Frontend'),
                'title' => Mage::helper('catalog')->__('Position on Frontend'),
                'note' => Mage::helper('catalog')->__('Position of attribute in product view block'),
                'class' => 'validate-digits',
            ));
        }
    }
}