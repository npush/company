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
        $this->_headerText = Mage::helper('company')->__('Parser Configuration');

        parent::__construct();

        $this->addButton('delete', array(
            'label'     => Mage::helper('company')->__('Delete Configuration'),
            'onclick'   => 'deleteConfiguration()',
            'class'     => 'delete'
        ));
        $this->_updateButton('add', 'label', Mage::helper('company')->__('New Configuration'));
        $this->_updateButton('add', 'onclick',  'addParserConfiguration()');

        $this->addButton('type', array(
            'label'     => Mage::helper('company')->__('Manage Price Type'),
            'onclick'   => 'viewPriceType()',
            'class'     => 'add'
        ));
    }
}