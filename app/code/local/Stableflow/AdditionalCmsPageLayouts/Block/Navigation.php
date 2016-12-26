<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/5/16
 * Time: 12:14 PM
 */
class Stableflow_AdditionalCmsPageLayouts_Block_Navigation extends Mage_Catalog_Block_Navigation{

    /**
     * Disable cache in block ...
     */
    protected function _construct(){

    }

    public function getRecursionChild($_category){
        $text = '<ul class="ul-outside">';
        $childrenCategoryIds = explode(',', $_category->getChildren());
        foreach($childrenCategoryIds as $_childId) {
            $childCategory = Mage::getModel('catalog/category')->load($_childId);
            if ($childCategory->getIsActive()) {
                $text .= '<li><a href="' . $childCategory->getUrlPath() . '">' . $this->htmlEscape($childCategory->getName()) . '</a></li>';
            }
            if($childCategory->hasChildren()){
                $text .= '<li class="list-unstyled">' . $this->getRecursionChild($childCategory) . '</li>';
            }
        }
        $text .= '</ul>';
        return $text;
    }
}