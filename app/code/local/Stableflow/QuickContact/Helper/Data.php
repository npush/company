<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/4/17
 * Time: 11:10 AM
 */


class Stableflow_QuickContact_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED   = 'contacts/contacts/enabled';

    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLED );
    }

    public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    public function logRequest(){
        $userAgent = Mage::helper('core/http')->getHttpUserAgent();
        $userIP = Mage::helper('core/http')->getRemoteAddr();
        $userHeaders = Mage::helper('core/http')->getRemoteAddrHeaders();
        $message = sprintf('from IP:%s, UserAgent: %s, Headers: %s', $userIP, $userAgent, $userHeaders);
        Mage::log($message, Zend_Log::INFO, 'contact-us.log');
    }
}
