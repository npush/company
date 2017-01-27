<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/5/16
 * Time: 12:14 PM
 */
class Stableflow_Rulletka_Block_Navigation extends Mage_Catalog_Block_Navigation{

    /**
     * Disable cache in block ...
     */
    protected function _construct(){

    }

    public function getRecursionChild($_category){
        $text = '<ul class="ul-outside">';
        $cat =$_category->getChildrenCategories();
        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $productCollection->addCountToCategories($cat);
        foreach($cat as $_childId) {
            $childCategory = Mage::getModel('catalog/category')->load($_childId->getId());

            if ($childCategory->getIsActive()) {
                $text .= '<li><a href="' . $childCategory->getUrlPath() . '">' . $this->htmlEscape($childCategory->getName()) . '</a><span> (' . $childCategory->getProductCount() . ')</span></li>';
            }
            if($childCategory->hasChildren()){
                $text .= '<li class="list-unstyled">' . $this->getRecursionChild($childCategory) . '</li>';
            }
        }
        $text .= '</ul>';
        return $text;
    }

    public function getCountInCategories($rootCategoryId){

    }

    /**
     * Retrieve child categories of current category
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getChildCategories()
    {
        if (null === $this->_currentChildCategories) {
            $layer = Mage::getSingleton('catalog/layer');
            $category = $layer->getCurrentCategory();
            $this->_currentChildCategories = $category->getChildrenCategories();
            $productCollection = Mage::getResourceModel('catalog/product_collection');
            $layer->prepareProductCollection($productCollection);
            $productCollection->addCountToCategories($this->_currentChildCategories);
        }
        return $this->_currentChildCategories;
    }
}