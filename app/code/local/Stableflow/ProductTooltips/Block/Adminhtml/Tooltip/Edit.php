<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:38 PM
 */
class Stableflow_ProductTooltips_Block_Adminhtml_Tooltip_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'product_tooltips';
        $this->_controller = 'adminhtml_tooltip';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('product_tooltips')->__('Save Tooltip')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('product_tooltips')->__('Delete Tooltip')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('company')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText(){
        if (Mage::registry('current_tooltip') && Mage::registry('current_tooltip')->getId()) {
            return Mage::helper('product_tooltips')->__(
                "Edit Tooltip '%s'",
                $this->escapeHtml(Mage::registry('current_tooltip')->getTitle())
            );
        } else {
            return Mage::helper('product_tooltips')->__('Add Tooltip');
        }
    }
}