<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 8/25/17
 * Time: 8:21 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceType extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_priceType';
        $this->_headerText = Mage::helper('company')->__('Parser Settings');
        parent::__construct();
        $this->_updateButton('add', 'label', Mage::helper('company')->__('Add Price Type'));
        $this->_updateButton('add', 'onclick',  'addPriceType()');

    }
}