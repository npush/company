<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 3:30 PM
 */
class Stableflow_BlackIp_Model_Observer extends Mage_Core_Model_Observer
{
    /**
     * Check if IP is in Block list
     */
    public function checkBlocked() {
        $helper = Mage::helper('sf_blackip');
        $ip = Mage::helper('core/http')->getRemoteAddr();
        if($helper->checkIfBlocked($ip)){
            header('HTTP/1.0 403 Access Denied/Forbidden');
            exit();
        }
        return;
    }
}