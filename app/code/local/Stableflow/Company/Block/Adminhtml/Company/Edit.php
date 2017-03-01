<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:38 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_company';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('company')->__('Save Company')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('company')->__('Delete Company')
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
        if (Mage::registry('current_company') && Mage::registry('current_company')->getId()) {
            return Mage::helper('company')->__(
                "Edit Company '%s'",
                $this->escapeHtml(Mage::registry('current_company')->getName())
            );
        } else {
            return Mage::helper('company')->__('Add Company');
        }
    }
}