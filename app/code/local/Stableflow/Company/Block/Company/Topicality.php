<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/7/17
 * Time: 1:33 PM
 */
class Stableflow_Company_Block_Company_Topicality extends Mage_Core_Block_Template{

    protected $_currentCompany = null;

    public function getCompanyTopicality(){

    }

    protected function getCompany(){
        if(!$this->_currentCompany) {
            $this->_currentCompany = Mage::registry('current_company');
        }
        return $this->_currentCompany;
    }
}