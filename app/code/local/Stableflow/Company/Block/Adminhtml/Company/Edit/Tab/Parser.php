<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/26/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Parser extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct(){
        parent::__construct();
        $this->setTemplate('company/tab/parser.phtml');
    }
}