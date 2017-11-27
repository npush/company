<?php
/**
 * Log.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 11/25/17
 *
 */
class Stableflow_Company_Block_Adminhtml_Parser_AddCodes extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_addCodes';
        $this->_headerText = Mage::helper('company')->__('Parser Add Codes');

        parent::__construct();

        $this->_removeButton('add');
    }
}