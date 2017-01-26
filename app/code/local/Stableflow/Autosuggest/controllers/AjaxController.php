<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/25/17
 * Time: 1:11 PM
 */
class Stableflow_Autosuggest_AjaxController extends Mage_Core_Controller_Front_Action{


    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function searchAction(){
        $this->loadLayout();

        $this->getLayout()->getBlock('root')->search();
        $this->renderLayout();
    }
}