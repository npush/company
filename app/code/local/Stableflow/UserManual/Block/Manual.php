<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/6/17
 * Time: 12:32 PM
 */
class Stableflow_UserManual_Block_Manual extends Mage_Core_Block_Template{

    const BASE_MANUAL_PATH = 'catalog/product_manual';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }

    /**
     * initialize
     */

    public function _construct(){
        parent::_construct();
        $manual = $this->getManual();
        $this->setManual($manual);
    }

    /**
     * get the current product
     *
     * @return mixed (Stableflow_UserManual_Model_Manual|null)
     */

    public function getCurrentProduct(){
        return Mage::registry('current_product');
    }

    /**
     * Retrieve manuals for current product
     * @return mixed
     */

    public function getManual(){
        $productId = $this->getCurrentProduct()->getId();
        $manualArray = null;
        $manualPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . self::BASE_MANUAL_PATH;
        $storeId = $this->getStore();
        if($productId) {
            $manuals = Mage::getModel('user_manual/manual')
                ->getCollection()
                ->addFieldToFilter('entity_id', $productId);
            $i = 0;
            foreach($manuals as $manual){
                $manualArray[$i] = array(
                    'label' => $manual->getLabel(),
                    'file' => $manualPath . $manual->getValue(),
                    'description' => $manual->getDescription(),
                );
                $i++;
            }
        }
        return $manualArray;
    }

    public function getStore(){
        return (int)Mage::app()->getStore()->getId();
    }
}