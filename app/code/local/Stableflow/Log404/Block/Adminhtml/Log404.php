<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 4:23 PM
 */

class Stableflow_Log404_Block_Adminhtml_Log404 extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'sf_log404';
        $this->_controller = 'adminhtml_log404';
        $this->_headerText = $this->__('Log 404');

        parent::__construct();
        $this->_removeButton('add');
    }
}