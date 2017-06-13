<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/8/17
 * Time: 5:54 PM
 */

include_once("Mage/Checkout/controllers/CartController.php");
class Stableflow_AjaxCart_CartController extends Mage_Checkout_CartController
{
    public function addAction()
    {
Mage::log('before all', null, 'Ajax-cart.log');
        if ($this->getRequest()->getParam('return_url') || !Mage::getStoreConfig('sf_ajaxcart/general/enabled') || !Mage::getStoreConfig('sf_ajaxcart/ajax/ajax_enabled')):
            return parent::addAction();
        endif;

        $items = Mage::getSingleton('checkout/cart')->init()->getItems();
        $countbefore = count($items);
Mage::log('before parent::addAction()', null, 'Ajax-cart.log');
        parent::addAction();

        $this->getResponse()
            ->clearHeaders()
            ->clearBody();
        $this->getResponse()->setHeader("Content-Type", "text/html; charset=UTF-8")
            ->setHttpResponseCode(200)
            ->isRedirect(0);

        $lastmessage = Mage::getSingleton('checkout/session')->getMessages()->getLastAddedMessage();
        $result = $lastmessage->getType() == 'success' ? 'success' : false;

        $message = '';
        $linktext = '';
        $popuphtml = '';
        $minicarthtml = '';
        $imageurl = '';
        $productname = '';
        $itemid = '';
        $deleteurl = '';
        $product = $this->_initProduct();
        if($result == 'success'){
            $this->loadLayout()->_initLayoutMessages('checkout/session');
            $message = Mage::app()->getLayout()->getMessagesBlock()->toHtml();
            $message = strip_tags($message);
            $linktext = $this->_getLinkText();
            $popuphtml = $this->getLayout()->getBlock('ajaxcartpopup')->toHtml();
            $minicarthtml = $this->getLayout()->getBlock('mini.cart')->toHtml();
            if (Mage::app()->getRequest()->getParam('imagedetail')) {
                $imageurl = (string)Mage::helper('catalog/image')->init($product, 'small_image')->resize(135);
                $productname = addslashes($product->getName());
            }
        }elseif($this->getRequest()->getParam('isproductpage')) {
            $this->loadLayout()->_initLayoutMessages('checkout/session');
            $message = Mage::app()->getLayout()->getMessagesBlock()->toHtml();
            $message = strip_tags($message);
        }else{
            $result = $product->getProductUrl();
        }

        Mage::helper('sf_ajaxcart')->updateCartCount();

        if (Mage::helper('sf_ajaxcart')->getCartItemCount() > $countbefore) {
            $allitems = Mage::getSingleton('checkout/cart')->getItems()->getData();
            $itemid = array_pop($allitems);
            $itemid = $itemid['item_id'];
            $deleteurl = Mage::helper('sf_ajaxcart')->getDeleteUrl($itemid);
        }

        $this->getResponse()->setBody(Zend_Json::encode(array(
            'result' => $result,
            'message' => $message,
            'linktext' => $linktext,
            'popuphtml' => $popuphtml,
            'minicarthtml' => $minicarthtml,
            'imageurl' => $imageurl,
            'productname' => $productname,
            'itemid' => $itemid,
            'deleteurl' => $deleteurl
        )));
    }

    public function deleteAction()
    {
        parent::deleteAction();

        if ($this->getRequest()->getParam('ajaxcartpopupreq')):
            $result = 'success';
            foreach (Mage::getSingleton('checkout/session')->getMessages()->getItems() as $message):
                if ($message->getType() == 'error'):
                    $result = Mage::helper('checkout/cart')->getCartUrl();
                endif;
                break;
            endforeach;

            $this->getResponse()
                ->clearHeaders()
                ->clearBody();
            $this->getResponse()
                ->setHeader("Content-Type", "text/html; charset=UTF-8")
                ->setHttpResponseCode(200)
                ->isRedirect(0);

            if ($this->getRequest()->getParam('iscartpage')):
                $totals = '';
                if ($result == 'success'):
                    $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml')->toHtml();
                endif;

                $this->getResponse()->setBody(Zend_Json::encode(array(
                    'result' => $result,
                    'totals' => $totals
                )));
            else:
                $linktext = '';
                $popuphtml = '';
                $emptycart = '';
                $minicarthtml = "";
                if ($result == 'success'):
                    $this->loadLayout()->_initLayoutMessages('checkout/session');
                    $linktext = $this->_getLinkText();
                    $popuphtml = $this->getLayout()->getBlock('ajaxcartpopup')->toHtml();
                    $minicarthtml = $this->getLayout()->getBlock('mini.cart')->toHtml();
                    $emptycart = Mage::helper('sf_ajaxcart')->getCartCount() ? false : true;
                endif;

                $this->getResponse()->setBody(Zend_Json::encode(array(
                    'result' => $result,
                    'linktext' => $linktext,
                    'popuphtml' => $popuphtml,
                    'minicarthtml' => $minicarthtml,
                    'emptycart' => $emptycart
                )));
            endif;
        endif;
    }

    public function updatePostAction()
    {
        parent::updatePostAction();

        if ($this->getRequest()->getParam('ajaxcartpopupreq') && $this->getRequest()->getParam('ajaxupdatequantity')):
            $result = 'success';
            $this->loadLayout()->_initLayoutMessages('checkout/session');
            foreach (Mage::getSingleton('checkout/session')->getMessages()->getItems() as $message):
                if ($message->getType() == 'error' || $message->getType() == 'exception'):
                    $result = Mage::helper('checkout/cart')->getCartUrl();
                endif;
                break;
            endforeach;

            $this->getResponse()
                ->clearHeaders()
                ->clearBody();
            $this->getResponse()
                ->setHeader("Content-Type", "text/html; charset=UTF-8")
                ->setHttpResponseCode(200)
                ->isRedirect(0);

            if ($this->getRequest()->getParam('iscartpage')):
                $totals = '';
                if ($result == 'success'):
                    $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml')->toHtml();
                endif;

                $this->getResponse()->setBody(Zend_Json::encode(array(
                    'result' => $result,
                    'totals' => $totals
                )));
            else:
                $linktext = '';
                $popuphtml = '';
                $emptycart = '';
                $minicarthtml = '';
                if ($result == 'success'):
                    $this->loadLayout()->_initLayoutMessages('checkout/session');
                    $linktext = $this->_getLinkText();
                    $popuphtml = $this->getLayout()->getBlock('ajaxcartpopup')->toHtml();
                    $minicarthtml = $this->getLayout()->getBlock('mini.cart')->toHtml();
                    $emptycart = Mage::helper('sf_ajaxcart')->getCartCount() ? false : true;
                endif;

                $this->getResponse()->setBody(Zend_Json::encode(array(
                    'result' => $result,
                    'linktext' => $linktext,
                    'popuphtml' => $popuphtml,
                    'minicarthtml' => $minicarthtml,
                    'emptycart' => $emptycart
                )));
            endif;
        endif;
    }

    private function _getLinkText()
    {
        if ($block = $this->getLayout()->getBlock('minicart_head')):
            $cartlink = $block->toHtml();
            preg_match('/<a.+skip-cart.+>(.+)<\/a>/Us', $cartlink, $linktext);
        else:
            $toplinks = $this->getLayout()->getBlock('top.links')->toHtml();
            preg_match('/<a.+top-link-cart.+>(.+)<\/a>/Us', $toplinks, $linktext);
        endif;

        return $linktext[1];
    }
}