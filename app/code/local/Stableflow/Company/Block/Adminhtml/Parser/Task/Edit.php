<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/31/17
 * Time: 3:47 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Task_Edit  extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_task';
    }

    public function getHeaderText()
    {
        return Mage::helper('company')->__('Task Edit');
    }
}