<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 8/25/17
 * Time: 10:45 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceType_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{

    protected function _construct()
    {
        parent::_construct();

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
            'action'  => $this->getUrl('*/parser_parser/savePriceType', array('_current' => true)),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }

    protected function _prepareForm(){
        $priceType = Mage::registry('current_price_type');
        $fieldset = $this->getForm()->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('company')->__('Type Description'),
                'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('description', 'text', array(
            'label' => Mage::helper('company')->__('Description'),
            'name' => 'description',
            'values' => ''
        ));
        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('company')->__('Is Active'),
            'name' => 'is_active',
            'values' => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('company')->__('Yes'),
                ),
                array(
                    'value'     => 2,
                    'label'     => Mage::helper('company')->__('No'),
                ),
            )
        ));
        $this->getForm()->addValues($priceType->getData());
        return parent::_prepareForm();
    }

    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }
}