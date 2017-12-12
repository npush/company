<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:35 PM
 */

class Stableflow_Company_Block_Company_Product_List extends Mage_Catalog_Block_Product_List{

    protected $_productIds;

    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            $companyId = Mage::registry('current_company')->getId();
            $productIds = Mage::getModel('company/product')->getCatalogProductIds($companyId);
            if(!$productIds) $productIds = null;
            $this->_productCollection = Mage::getModel('company/product')->getCatalogProductCollection($productIds)->addAttributeToSelect('*');
        }
        return $this->_productCollection;
    }

    public function getPriceHtml($product){
        $productId = $product->getId();
        $companyId = Mage::registry('current_company')->getId();
        $product = Mage::getModel('company/product')->getProductByCatalogProductId($productId, $companyId);
        return Mage::helper('core')->formatPrice($product->getData('price'),true);
    }
}