<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 11:31 AM
 */
class Stableflow_UserManual_Block_Adminhtml_Catalog_Product_Manual extends Mage_Adminhtml_Block_Template{

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('user_manual/catalog/product/tab/manual.phtml');
    }

    public function getManual(){
        $productId = $this->getRequest()->getParam('id');
        $manualArray = null;
        $manualPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product_manual/';
        $storeId = $this->getStore();
        if($productId) {
            $manuals = Mage::getModel('user_manual/manual')
                ->getCollection()
                ->addFieldToFilter('entity_id', $productId);
            //return $manuals;
            $i = 0;
            foreach($manuals as $manual){
                $manualArray = [
                    $i =>[
                        'label' => $manual->getLabel(),
                        'file' => $manualPath . $manual->getValue(),
                    ]
                ];
                $i++;
            }
            return $manualArray;
        }else {
            return $manualArray;
        }
    }

    public function getStore(){
        return (int)Mage::app()->getStore()->getId();
    }
}