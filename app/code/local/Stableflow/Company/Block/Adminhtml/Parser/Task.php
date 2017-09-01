<?php

/**
 * Task.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 8/25/17
 *
 */
class Stableflow_Company_Block_Adminhtml_Parser_Task extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_task';
        $this->_headerText = Mage::helper('company')->__('Parser task');

        parent::__construct();

        $this->_updateButton('add', 'label', Mage::helper('company')->__('Add Task'));
        $this->_updateButton('add', 'onclick',  'addNewTask(1)');
    }
}