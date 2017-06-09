<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/8/17
 * Time: 5:53 PM
 */
class  Stableflow_AjaxCart_Block_Cart extends Mage_Core_Block_Template
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('sf_ajaxcart/ajax/cart_enabled');
    }

    public function getUpdateUrl()
    {
        return $this->helper('sf_ajaxcart')->getUpdateUrl();
    }
}