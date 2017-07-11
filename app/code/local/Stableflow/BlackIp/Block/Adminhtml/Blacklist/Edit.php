<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/6/17
 * Time: 4:11 PM
 */
class Stableflow_BlackIp_Block_Adminhtml_Blacklist_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'sf_blackip';
        $this->_controller = 'adminhtml_blacklist';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('sf_blackip')->__('Save IP')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('sf_blackip')->__('Delete IP')
        );
    }
}