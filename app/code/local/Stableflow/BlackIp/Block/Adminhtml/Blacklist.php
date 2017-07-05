<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 4:59 PM
 */
class Stableflow_BlackIp_Block_Adminhtml_Blacklist extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_blacklist';
        $this->_blockGroup = 'sf_blackip';

        $this->_headerText = Mage::helper('sf_blackip')->__('Black ip list');
        $this->_addButton("view_list", array(
            'label' => Mage::helper('sf_blackip')->__('View list'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/index') . "')")
        );
        $this->_addButton("view_blocked", array(
            'label' => Mage::helper('sf_blackip')->__('add blocked'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/blocked') . "')")
        );
        $this->_addButton("one_ip", array(
            'label' => Mage::helper('sf_blackip')->__('Block ip classes'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/oneip') . "')")
        );

        parent::__construct();
        $this->removeButton('add');
    }

}