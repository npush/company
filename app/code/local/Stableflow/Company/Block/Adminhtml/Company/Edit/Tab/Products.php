<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/21/17
 * Time: 5:13 PM
 */

class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Form{

    /**
     * 
     */
    public function __construct(){
        parent::__construct();
        $this->setTemplate('company/tab/products.phtml');
    }

    /**
     * Check block is readonly.
     *
     * @return boolean
     */
    public function isReadonly(){
        $customer = Mage::registry('current_customer');
        return $customer->isReadonly();
    }

    /**
     * Initialize form object
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
     */
    public function initForm(){
    }

    /**
     * get current entity
     *
     */
    public function getCompany(){
        return Mage::registry('current_company');
    }

}