<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/30/17
 * Time: 5:21 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_AddCode_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
            'action'  => $this->getUrl('*/parser_parser/saveAddCode', array('_current' => true)),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }

    protected function _prepareForm()
    {
        $config = Mage::registry('current_addCode');
        $fieldset = $this->getForm()->addFieldset('config_form', array(
            'legend' => Mage::helper('company')->__('Add Codes')
        ));
//        $fieldset->addField('entity_id', 'hidden', array(
//            'name' => 'entity_id',
//            'values' => ''
//        ));
        $fieldset->addField('company_id', 'hidden', array(
            'name' => 'company_id',
            'value' => $this->getCompanyId()
        ));
        $fieldset->addField('base_code', 'text', array(
            'label' => Mage::helper('company')->__('Base Code'),
            'name' => 'base_code',
            'values' => ''
        ));
        $fieldset->addField('wrong_code', 'text', array(
            'label' => Mage::helper('company')->__('Wrong Code'),
            'name' => 'wrong_code',
            'values' => ''
        ));
        $fieldset->addField('base_company_name', 'text', array(
        'label' => Mage::helper('company')->__('Base company name'),
        'name' => 'base_company_name',
        'values' => ''
    ));
        $fieldset->addField('wrong_company_name', 'text', array(
            'label' => Mage::helper('company')->__('Wrong company name'),
            'name' => 'wrong_company_name',
            'values' => ''
        ));

        $this->getForm()->addValues($config->getData());
        return parent::_prepareForm();
    }

    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }
}