<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/9/17
 * Time: 1:35 PM
 */
class Stableflow_ProductTooltips_Block_Tooltip extends Mage_Core_Block_Template{

    const BASE_TOOLTIPS_PATH = 'tooltips';

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
        $manual = $this->getTooltips();
        $this->setTooltip($manual);
    }

    /**
     * get the current product
     *
     * @return mixed (Stableflow_UserManual_Model_Manual|null)
     */

    public function getCurrentProduct(){
        return Mage::registry('current_product');
    }

    public function getTooltips(){
        $tooltips = null;
        $tolltipArray = null;
        $productId = $this->getCurrentProduct()->getId();
        $product = $this->getCurrentProduct();
        $manualArray = null;
        $imgPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . self::BASE_TOOLTIPS_PATH;
        $tooltips =  $product->getResource()->getAttribute('tooltips')->getFrontend()->getValue($product);
        if($tooltips){
            $tooltipIds = explode('|', trim($tooltips,'|'));
            $i = 0;
            foreach($tooltipIds as $tooltipId){
                $modelTooltip = Mage::getModel('product_tooltips/tooltip')->load($tooltipId);
                $tolltipArray[$i] = array(
                    'title' => $modelTooltip->getTitle(),
                    'file' => $imgPath . $modelTooltip->getImageFile(),
                    'description' => $modelTooltip->getDescription(),
                );
                $i++;
            }
        }
        return $tolltipArray;
    }
}