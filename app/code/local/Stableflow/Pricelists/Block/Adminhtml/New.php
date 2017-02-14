<?php
class Stableflow_Pricelists_Block_Adminhtml_New extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct() {
        parent::__construct();

        $this->_blockGroup = 'stableflow_pricelists';
        $this->_mode = 'new';
        $this->_controller = 'adminhtml';
    }

    public function getHeaderText() {
        return $this->helper('stableflow_pricelists')->__("Add new pricelist");
    }
}