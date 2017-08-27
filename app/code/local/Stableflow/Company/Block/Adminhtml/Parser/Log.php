<?php

/**
 * Log.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 8/25/17
 *
 */
class Stableflow_Company_Block_Adminhtml_Parser_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_log';
        $this->_headerText = Mage::helper('company')->__('Parser log');

        parent::__construct();
    }
}