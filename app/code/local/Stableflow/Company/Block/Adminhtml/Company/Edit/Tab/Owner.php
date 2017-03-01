<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/22/16
 * Time: 6:02 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Owner extends Mage_Adminhtml_Block_Widget_Form{

    public function __construct(){
        parent::__construct();
        $this->setTemplate('company/tab/owner.phtml');
    }
}