<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:35 PM
 */

class Stableflow_Company_Block_Company_Product_List extends Mage_Catalog_Block_Product_List{

    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            $productIds = $this->getCompanyProducts(Mage::registry('current_company')->getId());
            $productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', array('in' => $productIds));
            //$productCollection->getSelect()->order("find_in_set(entity_id,'".implode(',',$productIds)."')");
            $this->_productCollection = $productCollection;
        }
        return $this->_productCollection;
    }

    protected function getCompanyProducts($companyId){
        $a = Mage::getModel('company/company')->load($companyId)->getProducts();
        $productIds = null;
        $productCollection = Mage::getModel('company/relation')
            ->getCollection()
            ->addFieldToFilter('company_id', $companyId);
        foreach($productCollection as $productRelation){
            $productIds[] = $productRelation->getProductId();
        }
        return $productIds;
    }

    public function getPriceHtml($product){
        $productId = $product->getId();
        $companyId = Mage::registry('current_company')->getId();
        $rel = Mage::getModel('company/relation')->getCollection()
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('company_id' , $companyId)
            ->getFirstItem();
        $price = Mage::getModel('company/product')->load($rel->getData('company_product_id'));
        return Mage::helper('core')->formatPrice($price->getData('price'),true);
    }
}