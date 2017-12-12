<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/20/17
 * Time: 2:54 PM
 */
class Stableflow_Company_Block_Adminhtml_Product_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_product';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('company')->__('Save Product')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('company')->__('Delete Product')
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText(){
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            return Mage::helper('company')->__(
                "Edit Product '%s'",
                $this->escapeHtml($id)
            );
        } else {
            return Mage::helper('company')->__('Add new product');
        }
    }
}