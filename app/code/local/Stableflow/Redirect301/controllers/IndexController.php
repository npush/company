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
        $url = null;
        if($old_id = Mage::app()->getRequest()->getParam('old_product_id')) {
            $id = Mage::getResourceModel('redirect301/redirect')->getRedirect($old_id);
            $url = Mage::helper('redirect301')->getRedirectUrl('product', $id);
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_catalog_id')){
            $url = Mage::helper('redirect301')->getRedirectUrl('category', $old_id);
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_product_type_id')){
            // add 1 to productType_id
            $id = '1'.$old_id;
            $url = Mage::helper('redirect301')->getRedirectUrl('category', $id);
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_article_id')){
            $url = Mage::helper('redirect301')->getRedirectUrl('article', $old_id);
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_company_id')){
            $url = Mage::helper('redirect301')->getRedirectUrl('company', $old_id);
        }
        if($url){
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $url, 301)->sendResponse();
            return;
        }else {
            $this->_redirect('/');
        }
    }
}