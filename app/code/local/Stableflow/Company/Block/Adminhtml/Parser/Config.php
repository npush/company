<?php

/**
 * Config.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 8/25/17
 *
 */
class Stableflow_Company_Block_Adminhtml_Parser_Config extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_config';
        $this->_headerText = Mage::helper('company')->__('Parser Settings');
        parent::__construct();
        $this->addButton('delete', array(
            'label'     => Mage::helper('company')->__('Delete Settings'),
            'onclick'   => '',
            'class'     => 'delete'
        ));
        $this->_updateButton('add', 'label', Mage::helper('company')->__('Add Settings'));
        //$this->_updateButton('add', 'onclick',  'setLocation(\'' . $this->getUrl('*/parser_parser/newSettings') .'\')');
        $this->_updateButton('add', 'onclick',  'newConfiguration()');
        $this->addButton('type', array(
            'label'     => Mage::helper('company')->__('Manage Type'),
            'onclick'   => 'editPriceType()',
            'class'     => 'add'
        ));
    }
}