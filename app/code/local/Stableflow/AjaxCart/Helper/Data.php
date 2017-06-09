<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/8/17
 * Time: 6:02 PM
 */

class Stableflow_AjaxCart_Helper_Data extends Mage_Core_Helper_Data
{
    public function updateCartCount()
    {
        $count = Mage::helper('checkout/cart')->getSummaryCount();
        Mage::getSingleton('customer/session')->setCartCount($count);
    }

    public function getCartItemCount()
    {
        $allitems = Mage::getSingleton('checkout/cart')->getItems();
        return count($allitems);
    }

    public function getDeleteUrl($itemid)
    {
        return Mage::getUrl('checkout/cart/delete', array('id' => $itemid, Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => Mage::helper('core/url')->getEncodedUrl()));
    }

    public function getCartCount()
    {
        return Mage::helper('checkout/cart')->getSummaryCount();
    }

    public function getUpdateUrl()
    {
        return Mage::getUrl('checkout/cart/updatePost');
    }
}