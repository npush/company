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
class Stableflow_Company_Block_Adminhtml_Parser_AddCode extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_addCode';
        $this->_headerText = Mage::helper('company')->__('Parser Add Code');

        parent::__construct();

        $this->_updateButton('add', 'label', Mage::helper('company')->__('New'));
        $this->_updateButton('add', 'onclick',  'newAddCode()');
    }
}