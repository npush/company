<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/13/17
 * Time: 11:48 AM
 */
class Stableflow_AutoSku_Model_Observer extends Mage_Core_Model_Observer
{
    /**
     * Generate Sku
     * @param $observer
     */
    public function generateSku($observer){
        $helper = Mage::helper('autosku');
        if($helper->autosku_enable()){
            $data = Mage::app()->getRequest()->getPost();
            $product = $observer->getEvent()->getProduct();
            $productId = $product->getId();
            $categoryIds = $product->getCategoryIds();
        }
    }
}