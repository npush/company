<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 12:34 PM
 */

class Stableflow_ProductTooltips_Block_Adminhtml_Catalog_Product_Tooltip extends Mage_Adminhtml_Block_Template{

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('product_tooltips/catalog/product/tab/tooltip.phtml');
    }

}