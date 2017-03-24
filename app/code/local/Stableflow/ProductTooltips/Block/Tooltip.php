<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/9/17
 * Time: 1:35 PM
 */
class Stableflow_ProductTooltips_Block_Tooltip extends Mage_Core_Block_Template{

    const BASE_TOOLTIPS_PATH = 'tooltips';

    protected $tooltipArray = null;

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

    /**
     * get
     * @return null
     */

    public function getTooltips(){
        if(is_null($this->tooltipArray)) {
            $product = $this->getCurrentProduct();
            $this->tooltipArray = $product->getResource()->getAttribute('tooltips')->getFrontend()->getValue($product);
        }
        return $this->tooltipArray;
    }
}