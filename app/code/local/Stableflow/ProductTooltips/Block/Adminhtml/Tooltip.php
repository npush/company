<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 12:25 PM
 */

class Stableflow_ProductTooltips_Block_Adminhtml_Tooltip extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        parent::__construct();
        $this->_controller         = 'adminhtml_tooltip';
        $this->_blockGroup         = 'product_tooltips';

        //$this->setTemplate('widget/grid/container.phtml');
        $this->_headerText         = Mage::helper('product_tooltips')->__('Products Tooltips');
        //$this->_removeButton('add');
        //$this->setTemplate('company/grid.phtml');
    }
}