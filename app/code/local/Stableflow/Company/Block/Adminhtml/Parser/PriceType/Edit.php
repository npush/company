<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/30/17
 * Time: 6:12 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceType_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_priceType';
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('reset');
    }
}