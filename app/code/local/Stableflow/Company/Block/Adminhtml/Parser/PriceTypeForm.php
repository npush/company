<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 8/25/17
 * Time: 11:00 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceTypeForm extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('delete');
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser';
        $this->_mode = 'PriceType';
    }
}