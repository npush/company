<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:33 PM
 */
class Stableflow_Company_Block_Company extends Mage_Core_Block_Template{

    public function _construct(){}

    protected function _prepareLayout(){
        parent::_prepareLayout();
        $id = $this->getCompany()->getId();
        $this->setCompany(Mage::getModel('company/company')->load($id));
        return $this;
    }

    public function getCompany(){
        return Mage::registry('current_company');
    }
}