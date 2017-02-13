<?php
class Stableflow_Pricelists_Block_Adminhtml_Pricelists extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    protected function _construct() {
        parent::_construct();
        $this->_addButtonLabel = Mage::helper('stableflow_pricelists')->__('Add New Price list');
        $this->_blockGroup = 'stableflow_pricelists';
        $this->_controller = 'adminhtml_pricelists';
        $this->_headerText = Mage::helper('stableflow_pricelists')->__('Price lists');
    }
}
