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
        $this->_addButton("Add New IP", array(
                'label' => Mage::helper('sf_blackip')->__('Add New IP'),
                'onclick' => "setLocation('" . $this->getUrl('*/*/addNew') . "')",
            )
        );

        parent::__construct();
        $this->removeButton('add');
    }

}