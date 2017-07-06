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
            /** @var Mage_Cms_Model_Resource_Block_Collection $collection */
            $collection = Mage::getModel('cms/block')->getCollection();
            $collection->addStoreFilter(1);
            $collection->addFieldToFilter('identifier', 'blacklist');

            $block = $collection->getFirstItem();
            echo $block->getData('content');
            exit();
        }
        return;
    }
}