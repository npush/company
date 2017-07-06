<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/6/17
 * Time: 12:00 PM
 */
class Stableflow_BlackIp_Block_Adminhtml_Blacklist_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm() {

        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            )
        );

        $base_fieldset = $form->addFieldset(
            'base', array(
                'legend' => Mage::helper('sf_blackip')->__('Black ip'),
            )
        );


        /*$base_fieldset->addField(
            'authorize_btn', 'button', array(
                'name' => 'authorize_btn',
                'label' => Mage::helper('sf_blackip')->__(
                    'Click on folowing link to test popup Dialog:'
                ),
                'value' => $this->helper('sf_blackip')->__('Test popup dialog >>'),
                'class' => 'form-button',
                'onclick' => 'javascript:openMyPopup()'
            )
        );*/

        $base_fieldset->addField('black_ip', 'text', array(
            'label'     => Mage::helper('sf_blackip')->__('blackip'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'black_ip',
            'onclick' => "",
            'onchange' => "alert('on change');",
            'style'   => "border:10px",
            'value'  => 'hello !!',
            'disabled' => false,
            'readonly' => false,
            'after_element_html' => '<small>black_ip</small>',
            'tabindex' => 1
        ));

        $base_fieldset->addField('comment', 'text', array(
            'label'     => Mage::helper('sf_blackip')->__('comment'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'comment',
            'onclick' => "",
            'onchange' => "alert('on change');",
            'style'   => "border:10px",
            'value'  => 'hello !!',
            'disabled' => false,
            'readonly' => false,
            'after_element_html' => '<small>black_ip</small>',
            'tabindex' => 1
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        parent::_prepareForm();
    }
}