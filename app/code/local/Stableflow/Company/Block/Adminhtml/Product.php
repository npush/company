<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 12:25 PM
 */
class Stableflow_Company_Block_Adminhtml_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        $this->_controller         = 'adminhtml_product';
        $this->_blockGroup         = 'company';
        //parent::__construct();
        //$this->setTemplate('widget/grid/container.phtml');
        $this->_headerText         = Mage::helper('company')->__('Company Products');
        //$this->_updateButton('add', 'label', Mage::helper('company')->__('Add Company'));
        $this->_removeButton('add');
        $this->setTemplate('company/grid.phtml');
    }
}