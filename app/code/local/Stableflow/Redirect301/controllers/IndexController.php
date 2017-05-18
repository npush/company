<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/17/17
 * Time: 12:30 PM
 */
class Stableflow_Redirect301_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        $request = Mage::app()->getRequest()->getRequestUri();;
        echo Mage::app()->getRequest()->getModuleName();
        echo Mage::app()->getRequest()->getControllerName();
        echo Mage::app()->getRequest()->getActionName();

        echo $request;
        die();
    }

    /**
     * Redirect from old url
     */
    public function redirectAction(){
        if($old_id = Mage::app()->getRequest()->getParam('old_product_id')) {
            $id = Mage::getResourceModel('redirect301/redirect')->getRedirect($old_id);
            $url = Mage::helper('redirect301')->getRedirectUrl('product', $id);
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $url, 301)->sendResponse();
            return;
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_catalog_id')){
            $url = Mage::helper('redirect301')->getRedirectUrl('category', $old_id);
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $url, 301)->sendResponse();
            return;
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_product_type_id')){
            // add 1 to old id
            $id = '1'.$old_id;
            $url = Mage::helper('redirect301')->getRedirectUrl('category', $id);
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $url, 301)->sendResponse();
            return;
        }
        $this->_redirect('/');
    }
}