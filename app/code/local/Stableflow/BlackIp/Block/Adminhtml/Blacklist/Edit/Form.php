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
                'id' => 'editForm',
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
            'label'     => Mage::helper('sf_blackip')->__('Blocked IP'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'black_ip',
            'style'   => "border:10px",
            'value'  => '',
            'after_element_html' => '<small>enter IP (separated by . "dot")for blocking</small>',
            'tabindex' => 1,
            'comment'   => 'Enter . separated IP'
        ));

        $base_fieldset->addField('comment', 'text', array(
            'label'     => Mage::helper('sf_blackip')->__('Comment'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'comment',
            'style'   => "border:10px",
            'value'  => '',
            'after_element_html' => '<small>your comment</small>',
            'tabindex' => 1
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        parent::_prepareForm();
    }
}