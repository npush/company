<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 8/25/17
 * Time: 10:45 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceType_Form extends Mage_Adminhtml_Block_Widget_Form{

    protected function _prepareForm(){
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/parser_parser/savePriceType',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam('store')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );

        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('company')->__('Type Description'),
                'class' => 'fieldset-wide',
            )
        );
        $fieldset->addField('stores', 'textarea', array(
            'label' => Mage::helper('company')->__('Description'),
            'name' => 'description',
            'values' => ''
        ));
        $form->setDataObject(Mage::getModel('company/parser_price_type'));
        $attributes = $this->getAttributes();
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::getModel('company/parser_price_type')->load(Mage::registry('company_id'))->getData();
        $form->addValues($formValues);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}