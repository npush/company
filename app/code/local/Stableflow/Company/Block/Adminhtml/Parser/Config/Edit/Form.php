<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/30/17
 * Time: 5:21 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Config_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
            'action'  => $this->getUrl('*/parser_parser/saveConfiguration', array('_current' => true)),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }

    protected function _prepareForm()
    {
        $priceType = Mage::getModel('company/parser_price_type')->getPriceTypeCollection($this->getCompanyId());
        foreach($priceType as $_priceType){
            $values[] =  array(
                'value'     => $_priceType->getId(),
                'label'     => $_priceType->getData('description'),
            );
        }
        $config = Mage::registry('current_config');
        $fieldset = $this->getForm()->addFieldset('config_form', array(
            'legend' => Mage::helper('company')->__('Configuration')
        ));
        $fieldset->addField('description', 'text', array(
            'label' => Mage::helper('company')->__('Description'),
            'name' => 'description',
            'values' => ''
        ));
        $fieldset->addField('price_type_id', 'select', array(
            'label' => Mage::helper('company')->__('Select Price Type'),
            'name'  => 'price_type_id',
            'class' => 'required-entry',
            'required' => true,
            'values' => $values
        ));
        $fieldset->addField('status_id', 'select', array(
            'label'     => Mage::helper('company')->__('Is Active'),
            'name'      => 'status_id',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('company')->__('Yes'),
                ),
                array(
                    'value'     => 2,
                    'label'     => Mage::helper('company')->__('No'),
                ),
            ),
        ));
        $this->getForm()->addValues($config->getData());
        return parent::_prepareForm();
    }

    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }
}