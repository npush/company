<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 3:24 PM
 */
class Stableflow_BlackIp_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
}