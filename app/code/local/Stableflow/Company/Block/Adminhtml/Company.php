<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 4:47 PM
 */
class Stableflow_Company_Block_Adminhtml_Company extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct(){
        $this->_controller         = 'adminhtml_company';
        $this->_blockGroup         = 'company';
        parent::__construct();
        $this->_headerText         = Mage::helper('company')->__('Company');
        $this->_updateButton('add', 'label', Mage::helper('company')->__('Add Company'));

        $this->setTemplate('company/grid.phtml');
    }
}