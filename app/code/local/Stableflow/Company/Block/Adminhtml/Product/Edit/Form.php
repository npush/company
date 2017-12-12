<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/20/17
 * Time: 3:02 PM
 */
class Stableflow_Company_Block_Adminhtml_Product_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm() {
        $id= Mage::app()->getRequest()->getParam('id');
        $p = Mage::getModel('company/product')->load($id);
        $form = new Varien_Data_Form(array(
//                'id' => 'product_form_edit',
//                'action' => $this->getUrl('*/*/saveProduct'),
//                'method' => 'post',
//                'enctype' => 'multipart/form-data',
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/saveProduct',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam('store')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );

        $form->setDataObject($p);

        $base_fieldset = $form->addFieldset(
            'base', array(
                'legend' => Mage::helper('company')->__('Edit Product'),
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity($p);
        }
        $this->_setFieldset($attributes, $base_fieldset, array());

        $base_fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('company')->__('Product Name'),
            'name'      => 'name',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('additional_code', 'text', array(
            'label'     => Mage::helper('company')->__('Additional Code'),
            'name'      => 'additional_code',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('remark', 'text', array(
            'label'     => Mage::helper('company')->__('Remark'),
            'name'      => 'remark',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('price', 'text', array(
            'label'     => Mage::helper('company')->__('Product Price'),
            'name'      => 'price',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('price_int', 'text', array(
            'label'     => Mage::helper('company')->__('Product Price Internal'),
            'name'      => 'price_int',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('price_wholesale', 'text', array(
            'label'     => Mage::helper('company')->__('Product Price Wholesale'),
            'name'      => 'price_wholesale',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('measure', 'text', array(
            'label'     => Mage::helper('company')->__('Qty Measure'),
            'name'      => 'measure',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));

        $base_fieldset->addField('qty', 'text', array(
            'label'     => Mage::helper('company')->__('Qty Available'),
            'name'      => 'qty',
            'style'   => "border:10px",
            'tabindex' => 1,
        ));


        /*$base_fieldset->addField('comment', 'text', array(
            'label'     => Mage::helper('sf_blackip')->__('Comment'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'comment',
            'style'   => "border:10px",
            'value'  => '',
            'after_element_html' => '<small>your comment</small>',
            'tabindex' => 1
        ));*/
        $form->addValues($p->getData());


        $form->setUseContainer(true);
        $this->setForm($form);

        parent::_prepareForm();
    }


//    protected function _prepareForm(){
//        $form = new Varien_Data_Form(
//            array(
//                'id'         => 'product_edit_form',
//                'action'     => $this->getUrl(
//                    '*/*/saveProduct',
//                    array(
//                        'id' => $this->getRequest()->getParam('id'),
//                        'store' => $this->getRequest()->getParam('store')
//                    )
//                ),
//                'method'     => 'post',
//                'enctype'    => 'multipart/form-data'
//            )
//        );
//        $form->setUseContainer(true);
//        $this->setForm($form);
//        return parent::_prepareForm();
//    }
}