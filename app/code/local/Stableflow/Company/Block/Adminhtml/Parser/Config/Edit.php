<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/28/17
 * Time: 12:19 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Config_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_config';
    }
}