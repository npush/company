<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:39 PM
 */

class Stableflow_ProductTooltips_Block_Adminhtml_Tooltip_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam('store')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );

        $base_fieldset = $form->addFieldset(
            'base', array(
                'legend' => Mage::helper('product_tooltips')->__('Tooltip'),
            )
        );
        $base_fieldset->addType('image_sf', 'Stableflow_ProductTooltips_Block_Adminhtml_Template_Form_Renderer_Image');
        $tooltip = Mage::registry('current_tooltip');

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

        $base_fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('product_tooltips')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
            'style'   => "border:10px",
            'value'  => '',
            'after_element_html' => '<small>Tooltip Title</small>',
            'tabindex' => 1,
            'comment'   => 'Title'
        ));

        $base_fieldset->addField('description', 'text', array(
            'label'     => Mage::helper('product_tooltips')->__('description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
            'style'   => "border:10px",
            'value'  => '',
            'after_element_html' => '<small>Tooltip Description</small>',
            'tabindex' => 2
        ));

        $base_fieldset->addField('value', 'image_sf', array(
            'label'     => Mage::helper('product_tooltips')->__('Image'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'value',
            'tabindex' => 3,
            'note' => '(*.jpg, *.png, *.gif)',
        ));

        $base_fieldset->addType('image_sf', 'Stableflow_ProductTooltips_Block_Adminhtml_Template_Form_Renderer_Image');

        $form->setValues($tooltip);

//        if ( Mage::getSingleton('adminhtml/session')->getTooltipData() )
//        {
//            $form->setValues(Mage::getSingleton('adminhtml/session')->getTooltipData());
//            Mage::getSingleton('adminhtml/session')->setTooltipData(null);
//        } elseif ( Mage::registry('tooltip_data') ) {
//            $form->setValues(Mage::registry('tooltip_data')->getData());
//        }

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}