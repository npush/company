<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/2/17
 * Time: 4:49 PM
 */
class Stableflow_UserManual_Model_Observer extends Mage_Core_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    public function fetchProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            $reqest = $observer->getEvent()->getRequest();

        }
    }

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            $product = $observer->getEvent()->getProduct();

            try {
                /**
                 * Perform any actions you want here
                 *
                 */
                $customFieldValue =  $this->_getRequest()->getPost('user_manual');

                /**
                 * Uncomment the line below to save the product
                 *
                 */
                //$product->save();
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}