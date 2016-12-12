<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:18 PM
 */
class Stableflow_Company_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction(){
        $companies = Mage::getModel('company/company')->getCollection()
            ->addAttributeToSelect('title')
            ->addAttributeToSelect('author');
        $companies->load();
        var_dump($companies);
    }

}