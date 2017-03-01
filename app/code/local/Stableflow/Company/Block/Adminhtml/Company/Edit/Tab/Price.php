<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 1:56 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Price extends Mage_Adminhtml_Block_Widget_Form{

    public function __construct(){
        parent::__construct();
        $this->setTemplate('company/tab/price.phtml');
    }
}